<?php
declare(strict_types=1);

namespace App\Settings;


class DateTimeSettings
{
    private string $dateTimeFormat;

    public function __construct(string $dateTimeFormat)
    {
        $this->dateTimeFormat = $dateTimeFormat;
    }

    public function getDateTimeFormat(): string
    {
        return $this->dateTimeFormat;
    }
}