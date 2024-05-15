<?php

$db = require 'conf.php';
require 'rb.php';

function debug($value) {
    echo '<pre>' . print_r($value, true) . '</pre>';
}

R::setup($db['dsn'], $db['user'], $db['pass']);