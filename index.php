<?php

error_reporting(E_ERROR | E_PARSE); 

require 'connect/db.php';

if (!$language = $_GET['language']) {
    $language = 'uz';
}

if ($language == 'ru') {
    $toggle_language = 'uz';
    $to_lang = 'UZ';
} else if ($language == 'uz') {
    $toggle_language = 'ru';
    $to_lang = 'RU';
}

if (!$parent_id = $_GET['parent_id']) {
    $parent_id = '0';

    if ($language == 'ru') {
        $heading = 'Главная страница';
        $to_lang = 'UZ';
    } else if ($language == 'uz') {
        $heading = 'Bosh sahifa';
        $to_lang = 'RU';
    }
} else {
    $heading = R::findOne('product', 'id = ?', [$parent_id])['name_' . $language];
}

$current_product = R::findOne('product', 'id = ?', [$parent_id]);

if ($parent_id == '0') {
    $current_product['type'] = 'category';
}

if ($current_product['type'] == 'page') {
    $page = R::findOne('page', 'parent_id = ?', [$current_product['id']]);
} else if ($current_product['type'] == 'category') {
    $products = R::findAll('product', 'parent_id = ? AND visibility = ? ORDER BY sort_order DESC, id ASC', [$parent_id, 'true']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRB Products</title>
    <link rel="icon" type="image/png" href="img/logo_brb_16_16.png">
    <link rel="stylesheet" href="style.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="user-side">
    <div class="header-top">
        <img class="logo" src="img/brb_logo_with_name_white_stroke.png">
        <a class="header-top-button" href="index.php?parent_id=<?php echo $parent_id; ?>&language=<?php echo $toggle_language; ?>"><i class="bx bx-globe"></i><?php echo $to_lang; ?></a>
    </div>
    <div class="header">
        <a class="header-button" href="index.php?parent_id=<?php echo $current_product['parent_id']; ?>&language=<?php echo $language; ?>"><i class="bx bxs-chevron-left"></i></a>
        <a class="heading" href="index.php?parent_id=<?php echo $current_product['parent_id']; ?>&language=<?php echo $language; ?>"><?php echo $heading; ?></a>
        <a class="header-button" href="currency-exchange.php"><i class="bx bx-dollar"></i></a>
    </div>
    <?php if ($current_product['type'] == 'page') { ?>
        <div class="content">
            <?php if ($page['template_id'] == 0): ?>
                <?php echo $page['content_' . $language]; ?>
            <?php else: ?>
                <div class="<?php if ($page['template_id'] == 9) { echo 'exchange-page'; } ?>">
                    <?php if ($page['template_id'] == 9): ?>
                        <?php $headings = R::findAll('heading', 'template_id = ?', [$page['template_id']]); $headings = array_chunk($headings, 2); ?>
                        <?php foreach ($headings as $heading): ?>
                            <!-- <?php debug($headings); ?> -->
                            <div class="a">
                                <p  class="c"><?php echo explode(' ', $heading[0]['name_' . $language])[0]; ?>:</p>
                            </div>
                            <div class="a">
                                <p  class="c"><?php echo explode(' ', $heading[0]['name_' . $language])[1]; ?> / <?php echo explode(' ', $heading[1]['name_' . $language])[1]; ?>:</p>
                                <ul class="b">
                                    <li><?php echo R::findOne('text', 'heading_id = ? AND page_id = ?', [$heading[0]['id'], $page['id']])['text_' . $language]; ?> / <?php echo R::findOne('text', 'heading_id = ? AND page_id = ?', [$heading[1]['id'], $page['id']])['text_' . $language]; ?></li>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php $headings = R::findAll('heading', 'template_id = ?', [$page['template_id']]); ?>
                        <?php foreach ($headings as $heading): ?>
                            <div class="a">
                                <p  class="c"><?php echo $heading['name_' . $language]; ?>:</p>
                                <ul class="b">
                                    <li><?php echo R::findOne('text', 'heading_id = ? AND page_id = ?', [$heading['id'], $page['id']])['text_' . $language]; ?></li>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php } else if ($current_product['type'] == 'category') { ?>
        <div class="products" style="padding-bottom: 20px;">
            <?php foreach ($products as $product): ?>
                <?php
                    if (empty($product['icon'])) {
                        $product['icon'] = 'none.png';
                    }
                ?>
                <a class="product" href="index.php?parent_id=<?php echo $product['id']; ?>&language=<?php echo $language; ?>">
                    <div class="product-info">
                        <h1 class="product-heading"><?php echo $product['name_' . $language]; ?></h1>
                        <?php if (!empty($product['description_' . $language])): $description = $product['description_' . $language]; ?>
                            <p class="product-description">
                                <?php $exploded_description = explode(';', $description); ?>
                                <?php if (!empty($exploded_description[0])): ?>
                                    <span class="tag tag-1"><?php echo $exploded_description[0]; ?></span>
                                <?php endif; ?>
                                <?php if (!empty($exploded_description[1])): ?>
                                    <span class="tag tag-2"><?php echo $exploded_description[1]; ?></span>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <img class="product-icon" src="img/<?php echo $product['icon']; ?>" alt="">
                </a>
            <?php endforeach; ?>
        </div>
    <?php } ?>
    <div class="footer">
        <h1>QUANT ilovasida HUMO kartalaridan o‘tkazmalar bepul</h1>
        <a href="https://play.google.com/store/apps/details?id=com.qqb.quant"><img src="img/google-play.png" alt=""></a>
        <a href="https://apps.apple.com/ru/app/quant-uzbekistan/id1524422825"><img src="img/app-store.png" alt=""></a>
    </div>
</body>
</html>