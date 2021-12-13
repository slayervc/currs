<?php
declare(strict_types=1);

namespace App\Repository\CurrencyRate\DBAL;


use Doctrine\DBAL\Connection;

class AbstractCurrencyRateDBALRepository
{
    protected const TABLE_NAME = 'currency_rate';
    protected const DATETIME_FORMAT = 'Y-m-d H:i:s';

    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}