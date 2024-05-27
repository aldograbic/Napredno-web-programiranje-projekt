<?php
print '
<main class="weather_main">
    <h1>Weather API</h1>
    <h2>Provjera vremenske prognoze</h2>
    <div class="weather-container">
        <form action="" method="POST" class="weather-form">
            <input type="text" id="city" name="city" placeholder="Unesite naziv grada" required>
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>
        </form>';

if (isset($_POST['city'])) {
    $city = $_POST['city'];
    $apiKey = '5816d0fe857a42de83f175756240802';
    $currentUrl = "http://api.weatherapi.com/v1/current.xml?key=$apiKey&q=$city";

    try {
        $currentWeatherData = @simplexml_load_file($currentUrl);
        if ($currentWeatherData === false || !isset($currentWeatherData->current)) {
            throw new Exception("Nije moguće dohvatiti podatke o vremenu za taj grad.");
        }

        $current = $currentWeatherData->current;
        $location = $currentWeatherData->location;

        $weatherInfo = 
        '<div class="weather-info">' .
            "<h2>{$location->name}, {$location->country}</h2>" .
            "<img src=\"{$current->condition->icon}\" alt=\"Weather icon\">" .
            "<p>{$current->temp_c}°C</p>" .
            "<p>{$current->condition->text}</p>" .
            '<div class="additional-info">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2C8.25 6 6 8.75 6 12a6 6 0 0 0 12 0c0-3.25-2.25-6-6-10z"/>
                        </svg>' .
                    "<p>{$current->humidity}%<br>Vlažnost zraka</p>" .
                '</div>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
                        <path fill="currentColor" d="M184 184a32 32 0 0 1-32 32c-13.7 0-26.95-8.93-31.5-21.22a8 8 0 0 1 15-5.56C137.74 195.27 145 200 152 200a16 16 0 0 0 0-32H40a8 8 0 0 1 0-16h112a32 32 0 0 1 32 32m-64-80a32 32 0 0 0 0-64c-13.7 0-26.95 8.93-31.5 21.22a8 8 0 0 0 15 5.56C105.74 60.73 113 56 120 56a16 16 0 0 1 0 32H24a8 8 0 0 0 0 16Zm88-32c-13.7 0-26.95 8.93-31.5 21.22a8 8 0 0 0 15 5.56C193.74 92.73 201 88 208 88a16 16 0 0 1 0 32H32a8 8 0 0 0 0 16h176a32 32 0 0 0 0-64"/>
                    </svg>' .
                    "<p>{$current->wind_kph} km/h <br>Vjetar</p>" .
                '</div>
            </div>
        </div>';

        $dates = [];
        for ($i = 1; $i <= 3; $i++) {
            $dates[] = date('d.n.Y.', strtotime("-$i day"));
        }

        $historicalInfo = '<div class="history-info">
        <h3>Temperatura zadnja 3 dana</h3>';
        
        foreach ($dates as $date) {
            $historyUrl = "http://api.weatherapi.com/v1/history.xml?key=$apiKey&q=$city&dt=" . date('Y-m-d', strtotime($date));
            $historyWeatherData = @simplexml_load_file($historyUrl);
            if ($historyWeatherData && isset($historyWeatherData->forecast->forecastday)) {
                $forecast = $historyWeatherData->forecast->forecastday[0]->day;
                $historicalInfo .= 
                '<div class="historical-day">' .
                    "<p>{$date}</p>" .
                    "<img src=\"{$forecast->condition->icon}\" alt=\"Weather icon\">" .
                    "<p>{$forecast->avgtemp_c}°C</p>" .
                    "<p>{$forecast->condition->text}</p>" .
                '</div>';
            }
        }
        
        $historicalInfo .= '</div>';
        
        echo $weatherInfo . $historicalInfo;
    } catch (Exception $e) {
        echo "<p class='error-message'>{$e->getMessage()}</p>";
    }
}
print'
    </div>
</main>';
?>
