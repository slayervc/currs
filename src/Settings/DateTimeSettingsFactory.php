<?php
declare(strict_types=1);

namespace App\Settings;


class DateTimeSettingsFactory
{
    private string $dateTimeFormat;

    public function __construct(string $dateTimeFormat)
    {
        $this->dateTimeFormat = $dateTimeFormat;
    }

    public function createSettings(): DateTimeSettings
    {
        return new DateTimeSettings($this->dateTimeFormat);
    }
}