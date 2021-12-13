<?php
declare(strict_types=1);

namespace App\Repository\CurrencyRate\DBAL;


use App\DTO\Rate\CurrencyRate;
use App\Repository\CurrencyRate\CurrencyRateWriteRepositoryInterface;
use App\Repository\CurrencyRate\Exception\CurrencyRateWriteRepositoryException;

class CurrencyRateDBALWriteRepository extends AbstractCurrencyRateDBALRepository implements CurrencyRateWriteRepositoryInterface
{
    public function storeRates(CurrencyRate ...$rates): void
    {
        $values = [];
        foreach ($rates as $rate) {
            $values[] = '(' . implode(',', [
                "'" . $rate->getPair()->getBase() . "'",
                "'" . $rate->getPair()->getQuote() . "'",
                "'" . $rate->getTimestampableRate()->getDatetime()->format(static::DATETIME_FORMAT) . "'",
                $rate->getTimestampableRate()->getRate()
            ]) . ')';
        }

        $sql = 'INSERT INTO ' . static::TABLE_NAME . ' (base, quote, datetime, rate) VALUES ' . implode(',', $values);

        try {
            $this->connection->executeQuery($sql);
        } catch (\Throwable $e) {
            throw new CurrencyRateWriteRepositoryException('Could not write data to database', 0, $e);
        }
    }
}