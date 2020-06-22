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
        ::-webkit-scrollbar{width:3px;height:3px}
        ::-webkit-scrollbar-track{background:#ccc!important}
        ::-webkit-scrollbar-thumb{background-color:#666!important}
        header { text-align: center; height: 55px; line-height: 55px; font-size: 12px; }
        header span { background: #999; color: #fff; padding: 5px; border-radius: 10px; }
        .avatar { border-radius: 50%; height: 50px; width: 50px; background: #ccc;float: left; }
        .info {position: relative;left: 20px;top: 5px;display: inline-block;padding: .8rem .8rem .8rem;border: 1px solid #ccc;border-radius: 5px;background: #fff;font-size: 14px;width: 70%;word-break:break-all}
        .arrow:before {position: absolute;left: -5px;top: 50%;transform: translateY(-50%);display:inline-block;width:0;height:0;border-top: 3px solid transparent;border-right: 5px solid #ccc;border-bottom: 3px solid transparent;content: " ";}
        .info img { width: 100%; height: 80px; }
        .news-empty{width: 100%;height: 60px;line-height: 60px;border: 1px solid #ccc;font-size: 15px;text-align: center;border-radius: 5px;}
        .news-box .news {height: 120px;display: block;position: relative;background: no-repeat center center;background-size: cover;overflow: hidden;}
        .news-box .news span {white-space:nowrap; left: 0;right: 0;bottom: 0;color: #fff;height: 30px;line-height: 30px;text-indent: 10px;font-size: 12px;overflow: hidden;position: absolute;margin: 0 -1px 0 -1px;text-overflow: ellipsis;background: rgba(0, 0, 0, .7);}
        .hr {color: #fff;height: 1px;margin: 3px 0;border-top: 1px dashed #e7eaec;}
        .news-box .other {height: 40px;line-height: 40px;display: block;position: relative;}
        .news-box .other span:first-child {color: #333;float: left;width: 60%;height: 40px;overflow: hidden;max-height: none;position: relative;background: 0 0;text-overflow: ellipsis;}
        .news-box .other span:last-child {width: 50px;height: 40px;float: right;max-height: none;position: relative;background-size: cover;background-position: center center;}
    </style>
</head>
<body>

    <header>
        <span><?php echo date("H:i"); ?></span>
    </header>
    <div class="container">
        <?php if(empty($news["article"])) : ?>
        <div class="news-empty">
            内容不存在！
        </div>
        <?php else : ?>
        <div class="news-box">
            <?php $i=0; foreach($news["article"] as $item) : ?>
            <?php if($i==0) : ?>
            <div class="news" style="background-image:url(<?php echo $item["local_url"]; ?>)">
                <span><?php echo $item["title"]; ?></span>
            </div>
            <div class="hr"></div>
            <?php else : ?>
            <div class="news other">
                <span><?php echo $item["title"]; ?></span>
                <span style="background-image:url(<?php echo $item["local_url"]; ?>);"></span>
            </div>
            <div class="hr"></div>
            <?php endif; ?>
            <?php $i++; endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</body>
</html>
