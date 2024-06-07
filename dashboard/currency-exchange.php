<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

if (!empty($_POST)) {
    $currencies = [
        'usd', 'eur', 'jpy', 'gbp', 'chf', 'rub', 'atm_usd', 'quant_usd'
    ];

    $date = date('Y-m-d', strtotime($_POST['date']));

    foreach ($currencies as $currency) {
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
        if ($data['Date'] == date('d.m.Y', strtotime($date))) {
            $mb_rate = $data['Rate'];
        } else {
            $mb_rate = '';
        }

        $currency_exchange = R::dispense('exchange');
        $currency_exchange['date'] = $_POST['date'];
        $currency_exchange['currency'] = $currency;
        $currency_exchange['buy_rate'] = $_POST[$currency . '_buy'];
        $currency_exchange['sell_rate'] = $_POST[$currency . '_sell'];
        $currency_exchange['mb_rate'] = $mb_rate;
        R::store($currency_exchange);
    }

    header('Location: ../currency-exchange.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Exchange</title>

    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">
    <form class="form" method="POST" action="currency-exchange.php">
        <h1 class="heading">Добавить курс валют</h1>
        <div class="input-container">
            <label class="input-label">Дата</label>
            <input class="input" type="date" name="date" value="<?php echo date('Y-m-d'); ?>">
        </div>
        <hr>
        <div class="input-container">
            <label class="input-label">USD</label>
            <div>
                <input class="input" type="text" name="usd_buy" placeholder="Buy">
                <input class="input" type="text" name="usd_sell" placeholder="Sell">
            </div>
        </div>
        <hr>
        <div class="input-container">
            <label class="input-label">EUR</label>
            <div>
                <input class="input" type="text" name="eur_buy" placeholder="Buy">
                <input class="input" type="text" name="eur_sell" placeholder="Sell">
            </div>
        </div>
        <hr>
        <div class="input-container">
            <label class="input-label">JPY</label>
            <div>
                <input class="input" type="text" name="jpy_buy" placeholder="Buy">
                <input class="input" type="text" name="jpy_sell" placeholder="Sell">
            </div>
        </div>
        <hr>
        <div class="input-container">
            <label class="input-label">GBP</label>
            <div>
                <input class="input" type="text" name="gbp_buy" placeholder="Buy">
                <input class="input" type="text" name="gbp_sell" placeholder="Sell">
            </div>
        </div>
        <hr>
        <div class="input-container">
            <label class="input-label">CHF</label>
            <div>
                <input class="input" type="text" name="chf_buy" placeholder="Buy">
                <input class="input" type="text" name="chf_sell" placeholder="Sell">
            </div>
        </div>
        <hr>
        <div class="input-container">
            <label class="input-label">RUB</label>
            <div>
                <input class="input" type="text" name="rub_buy" placeholder="Buy">
                <input class="input" type="text" name="rub_sell" placeholder="Sell">
            </div>
        </div>
        <hr>
        <div class="input-container">
            <label class="input-label">USD ATM</label>
            <div>
                <input class="input" type="text" name="atm_usd_buy" placeholder="Buy">
                <input class="input" type="text" name="atm_usd_sell" placeholder="Sell">
            </div>
        </div>
        <hr>
        <div class="input-container">
            <label class="input-label">USD QUANT</label>
            <div>
                <input class="input" type="text" name="quant_usd_buy" placeholder="Buy">
                <input class="input" type="text" name="quant_usd_sell" placeholder="Sell">
            </div>
        </div>
        <hr>
        <div class="buttons">
            <button class="button green">Добавить</button>
            <a href="index.php" class="button" onclick="return confirmCancel();">Отменить</a>
        </div>
    </form>

    <script>
        function confirmCancel() {
            return confirm('Вы уверены?');
        }
    </script>
</body>
</html>