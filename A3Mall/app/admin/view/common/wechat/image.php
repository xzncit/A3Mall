{__NOLAYOUT__}
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>A3Mall | Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/font/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/layui/css/layui.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/css/base.css">
    <script src="{__SYSTEM_PATH__}/js/jquery/jquery.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/layui/layui.js"></script>
    <script src="{__SYSTEM_PATH__}/js/common/common.js"></script>
    <style type="text/css">
        header { text-align: center; height: 55px; line-height: 55px; font-size: 12px; }
        header span { background: #999; color: #fff; padding: 5px; border-radius: 10px; }
        .avatar { border-radius: 50%; height: 50px; width: 50px; background: #ccc;float: left; }
        .info {position: relative;left: 20px;top: 5px;display: inline-block;padding: .8rem .8rem .8rem;border: 1px solid #ccc;border-radius: 5px;background: #fff;font-size: 14px;width: 70%;word-break:break-all}
        .arrow:before {position: absolute;left: -5px;top: 50%;transform: translateY(-50%);display:inline-block;width:0;height:0;border-top: 3px solid transparent;border-right: 5px solid #ccc;border-bottom: 3px solid transparent;content: " ";}
        .info img { width: 100%; height: 80px; }
    </style>
</head>
<body>

    <header>
        <span><?php echo date("H:i"); ?></span>
    </header>
    <div class="container">
        <div class="avatar"></div>
        <div class="info arrow">
            <img src="{$content}">
        </div>
    </div>

</body>
</html>
