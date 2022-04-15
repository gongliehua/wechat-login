<?php

include 'config.inc.php';

header('Content-Type: application/json');

$success = json_encode(['code' => 0, 'data' => null, 'message' => '登录状态'], JSON_UNESCAPED_UNICODE);
$error = json_encode(['code' => -1, 'data' => null, 'message' => '还未登录'], JSON_UNESCAPED_UNICODE);

if (!isset($_SESSION['user'])) {
    if (!isset($_SESSION['unique_number'])) {
        echo $error;
    } else {
        if (redis_connect()->exists($_SESSION['unique_number']) && redis_connect()->get($_SESSION['unique_number']) != '') {
            $_SESSION['user'] = redis_connect()->get($_SESSION['unique_number']);
            redis_connect()->del($_SESSION['unique_number']);
            unset($_SESSION['unique_number'], $_SESSION['number_expired_at']);
            echo $success;
        } else {
            echo $error;
        }
    }
} else {
    echo $success;
}
