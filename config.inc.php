<?php

ini_set('display_errors', 'on');
error_reporting(-1);

date_default_timezone_set('Asia/Shanghai');
header('Content-Type: text/html; charset=UTF-8');

session_start();

$config = [
    'redis' => [
        'host' => '127.0.0.1',
        'port' => 6379,
        'password' => '',
        'db' => 0,
    ],
    'number_valid_time' => 120,
    'official_account' => [
        'app_id' => 'wxcb63cddba769af4d',
        'secret' => '5dd58adc05531e822461ccf507cccbfz',
        'token' => '123456',
        'aes_key' => '',
    ],
];

include __DIR__ . '/include/functions.php';
