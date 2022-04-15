<?php

include 'config.inc.php';

//echo $_REQUEST['echostr'] ?? 'echostr';

$input = file_get_contents('php://input');
if (!check_signature() || empty($input)) {
    exit('success');
}

libxml_disable_entity_loader(true);
$xml = simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
$xmlArr = json_decode(json_encode($xml), true);

switch ($xmlArr['MsgType']) {
    case 'text':
        if (preg_match('/^#\d{6}$/', $xmlArr['Content'])) {
            $key = substr($xmlArr['Content'], 1);
            if (!redis_connect()->exists($key) || redis_connect()->get($key) != '') {
                //echo reply_text($xmlArr['FromUserName'], $xmlArr['ToUserName'], "验证码已失效");
                echo 'success';
            } else {
                if (redis_connect()->setRange($key, 0, $xmlArr['FromUserName'])) {
                    //echo reply_text($xmlArr['FromUserName'], $xmlArr['ToUserName'], "登录成功");
                    echo 'success';
                } else {
                    //echo reply_text($xmlArr['FromUserName'], $xmlArr['ToUserName'], "登录失败");
                    echo 'success';
                }
            }
        } else {
            echo 'success';
        }
        break;

    default:
        echo 'success';
        break;
}
