<?php

include 'config.inc.php';

if (isset($_SESSION['user'])) {
    header('Location: ./index.php');
    exit;
}

// 未设置过期时间(唯一编号) 或者 已过期，就获取新的编号
if (!isset($_SESSION['number_expired_at']) || (isset($_SESSION['number_expired_at']) && time() > $_SESSION['number_expired_at'])) {
    $_SESSION['unique_number'] = get_unique_number();
    $_SESSION['number_expired_at'] = time() + $config['number_valid_time'];
    if (!redis_connect()->set($_SESSION['unique_number'], '', $config['number_valid_time'])) {
        exit('页面出错了~');
    }
}

include 'templates/login.php';
