<?php

namespace App\Controllers;

use App\Models\WeatherAsset;
use App\View;
use Carbon\Carbon;

class WeatherDataController
{
    public function index()
    {
        $yd = Carbon::now()->subday()->toDateString();
        $place = "Riga";

        $responseHistory = json_decode(file_get_contents('http://api.weatherapi.com/v1/history.json?key=9b2ff4a2bd594a2c88285229222107&q=' . $place . '&dt=' . $yd));
        $response = json_decode(file_get_contents('http://api.weatherapi.com/v1/forecast.json?key=9b2ff4a2bd594a2c88285229222107&q='. $place .'&days=2'));

        $weatherYesterday = [];
        $weatherToday = [];
        $weatherTomorrow = [];

        $i = intval(date('H'));

        if ($i + 12 >= 23) {
            $t = $i - 12;
            $x = 0;
        } else {
            $t = 0;
            $x = 1;
        }

        if ($i - 12 < 0) {
            $y = $i + 12;
            $z = $i + 12;
            $i = 0;
        } else {
            $y = 24;
            $z = 23;
            $i = $i - 12;
        }


        for ($y; $y <= 23; $y++) {
            $weatherYesterday[] = new WeatherAsset(
                $responseHistory->forecast->forecastday[0]->hour[$y]->time,
                $responseHistory->forecast->forecastday[0]->hour[$y]->temp_c,
                $responseHistory->forecast->forecastday[0]->hour[$y]->humidity
            );
        }

        for ($i; $i <= $z; $i++) {
            $weatherToday[] = new WeatherAsset(
                $response->forecast->forecastday[0]->hour[$i]->time,
                $response->forecast->forecastday[0]->hour[$i]->temp_c,
                $response->forecast->forecastday[0]->hour[$i]->humidity
            );
        }

        for ($x; $x <= $t; $x++) {
            $weatherTomorrow[] = new WeatherAsset(
                $response->forecast->forecastday[1]->hour[$x]->time,
                $response->forecast->forecastday[1]->hour[$x]->temp_c,
                $response->forecast->forecastday[1]->hour[$x]->humidity
            );
        }

        $currentTime = Carbon::now()->toDateTimeString();
        return new View('WeatherOutput.twig', ['place' => $place, 'hourlyReportYesterday' => $weatherYesterday, 'hourlyReport' => $weatherToday, 'hourlyReportTomorrow' => $weatherTomorrow, 'currentTime' => $currentTime]);
    }
}