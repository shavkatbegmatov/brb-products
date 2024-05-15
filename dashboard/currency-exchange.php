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

    foreach ($currencies as $currency) {
        $currency_exchange = R::dispense('exchange');
        $currency_exchange['date'] = $_POST['date'];
        $currency_exchange['currency'] = $currency;
        $currency_exchange['buy_rate'] = $_POST[$currency . '_buy'];
        $currency_exchange['sell_rate'] = $_POST[$currency . '_sell'];
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
    <a href="index.php" class="button small">Назад</a>
    <br>
    <br>

    <form class="form" method="POST" action="currency-exchange.php">
        <h1 class="heading">Добавить</h1>
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
        <button class="button" type="submit">Добавить</button>
    </form>
</body>
</html>