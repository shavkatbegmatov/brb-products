<?php

require 'connect/db.php';


if (isset($_GET['date'])) {
    $date = date('Y-m-d', strtotime($_GET['date']));
} else {
    $date = date('Y-m-d');
}

$text = '';

function getExchange($currency, $date) {
    $exchange = R::findOne('exchange', 'date <= ? AND currency = ?', [$date, $currency], 'ORDER BY date DESC');
    if ($exchange) {
        return $exchange;
    } else {
        return ['buy_rate' => '---', 'sell_rate' => '---'];
    }
}

function getExchangeMB($currency, $date) {
    $curl = curl_init();

    $url = "https://cbu.uz/uz/arkhiv-kursov-valyut/json/" . $currency . "/" . $date . "/";

    curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache"
    ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $data = json_decode($response, true)[0];

    return $data['Rate'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Exchange</title>

    <link rel="stylesheet" href="currency.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="header">
        <a href="index.php" class="header-button">Ortga</a>
        <h1 class="header-heading">Valyutalar kursi</h1>
    </div>
    <div class="content">
        <p><?php echo $text; ?></p>
        <form class="date-form" action="currency-exchange.php" id="date-form" method="GET">
            <input type="date" name="date" class="date-input" id="date-input" value="<?php echo date('Y-m-d', strtotime($date)); ?>">
            <button class="date-button">ОК</button>
        </form>
        <div class="currency">
            <h1 class="currency-name">
                <img src="currency-icons/usd.png" alt="" class="currency-icon">
                1 USD 
            </h1>
            <div class="currency-exchange-rate">
                <div>
                    <h1>Markaziy Bank:</h1>
                    <span><?php echo getExchangeMB('usd', $date); ?></span>
                </div>
                <div>
                    <h1>Olish:</h1>
                    <span><?php echo getExchange('usd', $date)['buy_rate']; ?></span>
                </div>
                <div>
                    <h1>Sotish:</h1>
                    <span><?php echo getExchange('usd', $date)['sell_rate']; ?></span>
                </div>
            </div>
        </div>
        <div class="currency">
            <h1 class="currency-name">
                <img src="currency-icons/eur.png" alt="" class="currency-icon">
                1 EUR
            </h1>
            <div class="currency-exchange-rate">
                <div>
                    <h1>Markaziy Bank:</h1>
                    <span><?php echo getExchangeMB('eur', $date); ?></span>
                </div>
                <div>
                    <h1>Olish:</h1>
                    <span><?php echo getExchange('eur', $date)['buy_rate']; ?></span>
                </div>
                <div>
                    <h1>Sotish:</h1>
                    <span><?php echo getExchange('eur', $date)['sell_rate']; ?></span>
                </div>
            </div>
        </div>
        <div class="currency">
            <h1 class="currency-name">
                <img src="currency-icons/jpy.png" alt="" class="currency-icon">
                1 JPY
            </h1>
            <div class="currency-exchange-rate">
                <div>
                    <h1>Markaziy Bank:</h1>
                    <span><?php echo getExchangeMB('jpy', $date); ?></span>
                </div>
                <div>
                    <h1>Olish:</h1>
                    <span><?php echo getExchange('jpy', $date)['buy_rate']; ?></span>
                </div>
                <div>
                    <h1>Sotish:</h1>
                    <span><?php echo getExchange('jpy', $date)['sell_rate']; ?></span>
                </div>
            </div>
        </div>
        <div class="currency">
            <h1 class="currency-name">
                <img src="currency-icons/gbp.png" alt="" class="currency-icon">
                1 GBP
            </h1>
            <div class="currency-exchange-rate">
                <div>
                    <h1>Markaziy Bank:</h1>
                    <span><?php echo getExchangeMB('gbp', $date); ?></span>
                </div>
                <div>
                    <h1>Olish:</h1>
                    <span><?php echo getExchange('gbp', $date)['buy_rate']; ?></span>
                </div>
                <div>
                    <h1>Sotish:</h1>
                    <span><?php echo getExchange('gbp', $date)['sell_rate']; ?></span>
                </div>
            </div>
        </div>
        <div class="currency">
            <h1 class="currency-name">
                <img src="currency-icons/chf.png" alt="" class="currency-icon">
                1 CHF
            </h1>
            <div class="currency-exchange-rate">
                <div>
                    <h1>Markaziy Bank:</h1>
                    <span><?php echo getExchangeMB('chf', $date); ?></span>
                </div>
                <div>
                    <h1>Olish:</h1>
                    <span><?php echo getExchange('chf', $date)['buy_rate']; ?></span>
                </div>
                <div>
                    <h1>Sotish:</h1>
                    <span><?php echo getExchange('chf', $date)['sell_rate']; ?></span>
                </div>
            </div>
        </div>
        <div class="currency">
            <h1 class="currency-name">
                <img src="currency-icons/rub.png" alt="" class="currency-icon">
                1 RUB
            </h1>
            <div class="currency-exchange-rate">
                <div>
                    <h1>Markaziy Bank:</h1>
                    <span><?php echo getExchangeMB('rub', $date); ?></span>
                </div>
                <div>
                    <h1>Olish:</h1>
                    <span><?php echo getExchange('rub', $date)['buy_rate']; ?></span>
                </div>
                <div>
                    <h1>Sotish:</h1>
                    <span><?php echo getExchange('rub', $date)['sell_rate']; ?></span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('date-input').addEventListener('change', function() {
            document.getElementById('date-form').submit();
        });
    </script>
</body>
</html>