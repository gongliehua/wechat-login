<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录</title>
    <style>
        body {margin: 0; padding: 0; font-size: 16px; background: #F7F7F7;}
        .content {width: 258px; margin: 50px auto;}
        .content h3 {font-size: 14px; font-weight: normal; text-align: center;}
    </style>
    <script>
        // 唯一编号过期后刷新页面
        var refreshPage = setTimeout(function(){
            location.reload()
        }, <?php echo $config['number_valid_time'] * 1000 ?>)
    </script>
</head>
<body>
<div class="content">
    <img src="./images/qrcode.jpg" alt="">
    <h3>关注微信公众号"接口测试", 发送"#<?php echo $_SESSION['unique_number'] ?>"登录</h3>
</div>
<script src="./js/jquery.min.js"></script>
<script>
    // 定时获取登录状态
    var getLoginStatus = setInterval(function(){
        $(function(){
            $.ajax({
                "url": "./login_status.php",
                "type": "GET",
                "dataType": "json",
                "success": function (data) {
                    if (data.code === 0) {
                        clearTimeout(refreshPage)
                        clearInterval(getLoginStatus)
                        alert("登录成功")
                        location.href = './index.php'
                    }
                },
                "error": function () {
                    alert("网络错误")
                }
            })
        });
    }, 3000)
</script>
</body>
</html>
