<?php

namespace App\Models;
use Carbon\Carbon;

class WeatherAsset
{
    private string $time;
    private float $temp_c;
    private float $humidity;

    public function __construct(string $time, float $temp_c, float $humidity)
    {
        $this->time = $time;
        $this->temp_c = $temp_c;
        $this->humidity = $humidity;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function getTempC(): float
    {
        return $this->temp_c;
    }

    public function getHumidity(): float
    {
        return $this->humidity;
    }
    public function isCurrentHour(): bool
    {
        return date('H', strtotime($this->time)) == date('H');
    }
}