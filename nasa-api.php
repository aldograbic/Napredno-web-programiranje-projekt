<?php
print '
<main class="apod_main">
    <h1>NASA API</h1>
    <h2>Astronomska slika dana</h2>
    <div class="nasa-container">
        <form action="" method="POST" class="nasa-form">
            <div class="date-label">
                <label for="apod_date">Odaberite datum:</label>
                <input type="date" id="apod_date" name="apod_date">
            </div>
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                </svg>
            </button>
        </form>';

$apiKey = 'USMdcm6F8Ry6rg9wMZjpDII22qrTNXTso2Ev3MlT';

if (isset($_POST['apod_date'])) {
    $selectedDate = $_POST['apod_date'];
    $url = "https://api.nasa.gov/planetary/apod?date=$selectedDate&api_key=$apiKey";

    try {
        $response = @file_get_contents($url);
        if ($response === false) {
            throw new Exception("Za odabrani datum nije pronađena nijedna astronomska slika dana.");
        }

        $data = json_decode($response);
        if ($data) {
            $title = $data->title;
            $explanation = $data->explanation;
            $imageURL = $data->url;

            echo "<style>
                    .apod_main {
                        background-image: url('$imageURL');
                        background-size: cover;
                        background-repeat: no-repeat;
                        background-position: center;
                        color: white;
                        padding: 50px;
                        text-shadow: 2px 2px 8px #000;
                        height: 85dvh;
                    }
                    .nasa-form {
                        display: flex;
                        justify-content: center;
                    }
                  </style>";
                  
            echo "<h2>$title</h2>";
            echo "<p>$explanation</p>";
        } else {
            throw new Exception("Za odabrani datum nije pronađena nijedna astronomska slika dana.");
        }
    } catch (Exception $e) {
        echo "<p class='error-message-nasa'>{$e->getMessage()}</p>";
    }
}

print '
    </div>
</main>';
?>
