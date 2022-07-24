<?php

namespace App\Models;

class WeatherAsset
{
    private string $time;
    private float $temp_c;
    private float $humidity;
    private string $icon;

    public function __construct(string $time, float $temp_c, float $humidity, string $icon)
    {
        $this->time = $time;
        $this->temp_c = $temp_c;
        $this->humidity = $humidity;
        $this->icon = $icon;
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

    public function getIcon(): string
    {
        return $this->icon;
    }
    
    public function isCurrentHour(): bool
    {
        return date('H', strtotime($this->time)) == date('H');
    }
}
