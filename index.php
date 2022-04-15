<?php

include 'config.inc.php';

if (isset($_SESSION['user'])) {
    echo '<p>登录成功，用户Key: ' . $_SESSION['user'] . '</p>';
} else {
    echo '<p>未登录, <a href="./login.php">点击登录</a></p>';
}
