<?php
declare(strict_types=1);

namespace App\Repository\CurrencyRate\DBAL;


use App\DTO\Currency\CurrencyPair;
use App\DTO\Rate\SingleCurrencyPairRateCollection;
use App\DTO\Rate\TimestampableRate;
use App\Repository\CurrencyRate\CurrencyRateAggregateRepositoryInterface;
use App\Repository\CurrencyRate\Exception\CurrencyRateAggregateRepositoryException;

class CurrencyRateDBALAggregateRepository extends AbstractCurrencyRateDBALRepository implements CurrencyRateAggregateRepositoryInterface
{
    /**
     * @param  CurrencyPair            $currencyPair
     * @param  \DateTimeInterface      $from
     * @param  \DateTimeInterface|null $to
     * @param  int                     $stepSecs
     *
     * @return SingleCurrencyPairRateCollection
     * @throws CurrencyRateAggregateRepositoryException
     */
    public function getAllByDateTimeRangeWithStep(
        CurrencyPair $currencyPair,
        \DateTimeInterface $from,
        ?\DateTimeInterface $to,
        int $stepSecs
    ): SingleCurrencyPairRateCollection {
        $table = static::TABLE_NAME;
        $sql = <<<SQL
        SELECT rate, datetime
        FROM {$table} c
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

        try {
            $data = $this->connection->prepare($sql)->executeQuery(
                    [
                        'base'  => $currencyPair->getBase(),
                        'quote' => $currencyPair->getQuote(),
                        'step'  => $stepSecs,
                        'from'  => $from->format(static::DATETIME_FORMAT),
                        'to'    => $to->format(static::DATETIME_FORMAT)
                    ]
                )->fetchAllAssociative();
        } catch (\Throwable $e) {
            throw new CurrencyRateAggregateRepositoryException('Could not retrieve data from database', 0 ,$e);
        }

        $rates = [];
        foreach ($data as $row) {
            $rates[] = new TimestampableRate(new \DateTimeImmutable($row['datetime']), floatval($row['rate']));
        }

        return new SingleCurrencyPairRateCollection($currencyPair, ...$rates);
    }
}