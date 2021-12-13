<?php
declare(strict_types=1);

namespace App\Repository\CurrencyRate\DBAL;


use App\DTO\Currency\CurrencyPair;
use App\DTO\Currency\CurrencyRateCollection;
use App\DTO\Rate\TimestampableRate;
use App\Repository\CurrencyRate\CurrencyRateAggregateRepositoryInterface;
use Doctrine\DBAL\Connection;

class CurrencyRateDBALAggregateRepository implements CurrencyRateAggregateRepositoryInterface
{
    private const DATETIME_FORMAT = 'Y-m-d H:i:s';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getAllByDateTimeRangeWithStep(
        CurrencyPair $currencyPair,
        \DateTimeInterface $from,
        ?\DateTimeInterface $to,
        int $stepSecs
    ): CurrencyRateCollection {
        $sql = <<<'SQL'
        SELECT rate, datetime
        FROM currency_rate c
        INNER JOIN
        (SELECT MIN(datetime) as start
        FROM currency_rate
        WHERE base=:base AND quote=:quote AND datetime BETWEEN :from AND :to
        GROUP BY (
            ((CAST(to_char(datetime, 'YY') AS INTEGER)) * 366 + CAST(to_char(datetime, 'DDD') AS INTEGER))
              * 86400 + CAST(to_char(datetime, 'SSSS') AS INTEGER)
            ) / :step
        ) AS s
        ON c.datetime = s.start
        WHERE base=:base AND quote=:quote
        ORDER BY datetime;
        SQL;

        $data =$this->connection->prepare($sql)
            ->executeQuery([
                'base' => $currencyPair->getBase(),
                'quote' => $currencyPair->getQuote(),
                'step' => $stepSecs,
                'from' => $from->format(self::DATETIME_FORMAT),
                'to' => $to->format(self::DATETIME_FORMAT)
            ])
            ->fetchAllAssociative();

        $rates = [];
        foreach ($data as $row) {
            $rates[] = new TimestampableRate(new \DateTimeImmutable($row['datetime']), floatval($row['rate']));
        }

        return new CurrencyRateCollection($currencyPair, ...$rates);
    }
}