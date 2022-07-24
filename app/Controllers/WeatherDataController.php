class WeatherDataController
{
    public function index()
    {
        $yd = Carbon::now()->subday()->toDateString();
        $place = "Riga";

        $responseHistory = json_decode(file_get_contents('http://api.weatherapi.com/v1/history.json?key=9b2ff4a2bd594a2c88285229222107&q=' . $place . '&dt=' . $yd));
        $response = json_decode(file_get_contents('http://api.weatherapi.com/v1/forecast.json?key=9b2ff4a2bd594a2c88285229222107&q=' . $place . '&days=2'));

        $weatherYesterday = [];
        $weatherToday = [];

        $currTime = intval(date('H'));

        if ($currTime - 12 < 0) {
            $z = $currTime + 12;
            $y = 24 - abs($currTime - 12);
            for ($y; $y <= 23; $y++) {
                $weatherYesterday[] = new WeatherAsset(
                    $responseHistory->forecast->forecastday[0]->hour[$y]->time,
                    $responseHistory->forecast->forecastday[0]->hour[$y]->temp_c,
                    $responseHistory->forecast->forecastday[0]->hour[$y]->humidity,
                    $responseHistory->forecast->forecastday[0]->hour[$y]->condition->icon
                );
            }
            $j = 0;
        } else {
            $j = $currTime - 12;
            $z = 24 + abs($currTime - 12);
        }

        $x = 0;
        $f = $j;

        for ($j; $j <= $z; $j++) {
            if ($j == 24) {
                $f = 0;
                $x += 1;
            }
            $weatherToday[] = new WeatherAsset(
                $response->forecast->forecastday[$x]->hour[$f]->time,
                $response->forecast->forecastday[$x]->hour[$f]->temp_c,
                $response->forecast->forecastday[$x]->hour[$f]->humidity,
                $response->forecast->forecastday[$x]->hour[$f]->condition->icon
            );
            $f += 1;
        }

        $currentTime = Carbon::now()->toDateTimeString();
        return new View('WeatherOutput.twig', ['place' => $place, 'hourlyReportYesterday' => $weatherYesterday, 'hourlyReport' => $weatherToday, 'currentTime' => $currentTime]);
    }
}
