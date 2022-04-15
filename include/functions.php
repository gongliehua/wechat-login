<?php

function &redis_connect()
{
    global $config;
    static $redis;
    if (empty($redis)) {
        try {
            $redis = new \Redis();
            $redis->connect($config['redis']['host'], $config['redis']['port']);
            if ($config['redis']['password']) {
                $redis->auth($config['redis']['password']);
            }
            $redis->select($config['redis']['db']);
            $redis->ping('ok');
        } catch (\RedisException $redisException) {
            echo '<pre><code>';
            echo '<h1>' . htmlspecialchars($redisException->getMessage()) . '</h1>';
            echo htmlspecialchars($redisException->__toString());
            echo '</code></pre>';
            exit;
        }
    }
    return $redis;
}

function get_unique_number()
{
    static $loopNumber = 0;
    $loopNumber++;
    if ($loopNumber > 3) {
        exit('页面出错了~');
    }

    $number = mt_rand(1, 999999);
    $number = str_pad($number, 6, '0', STR_PAD_LEFT);

    if (redis_connect()->exists($number)) {
        return get_unique_number();
    }
    return $number;
}

function get_access_token()
{
    global $config;
    $key = 'access_token:' . $config['official_account']['app_id'];
    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $config['official_account']['app_id'] . '&secret=' . $config['official_account']['secret'];
    if (!redis_connect()->exists($key)) {
        $result = json_decode(file_get_contents($url), true);
        if (is_null($result) || !isset($result['access_token'])) {
            exit('页面出错了~');
        }
        if (!redis_connect()->set($key, $result['access_token'], 7200 - 10)) {
            exit('页面出错了~');
        }
        return $result['access_token'];
    }

    return redis_connect()->get($key);
}

function check_signature()
{
    global $config;

    $signature = $_GET['signature'] ?? '';
    $timestamp = $_GET['timestamp'] ?? '';
    $nonce = $_GET['nonce'] ?? '';

    $data = [$config['official_account']['token'], $timestamp, $nonce];
    sort($data, SORT_STRING);
    $tmp = implode($data);
    $tmp = sha1($tmp);

    return $signature == $tmp;
}

function reply_text($toUserName, $fromUserName, $content)
{
    $time = time();
    return <<<EOF
<xml>
<ToUserName><![CDATA[{$toUserName}]]></ToUserName>
<FromUserName><![CDATA[{$fromUserName}]]></FromUserName>
<CreateTime>{$time}</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[{$content}]]></Content>
</xml>
EOF;
}
