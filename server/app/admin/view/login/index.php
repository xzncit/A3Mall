{__NOLAYOUT__}<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>A3Mall | Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/bootstrap/css/bootstrap.min.css">
    <script src="{__SYSTEM_PATH__}/js/jquery/jquery.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/layui/layui.all.js"></script>
    <script src="{__SYSTEM_PATH__}/js/common/common.js"></script>
    <style type="text/css">
        input,button { outline:none; }
        ::-webkit-input-placeholder {
            color: #999;
            font-size: 15px;
        }
        ::-moz-placeholder {
            color: #999;
            font-size: 15px;
        }

        :-ms-input-placeholder {
            color: #999;
            font-size: 15px;
        }
        body { background-color: #3c8dbc; }
        #header { width: 100%; height: 90px; line-height: 90px; border-bottom: 1px solid #80b5d3; }
        .wrap { width: 1200px; margin: 0 auto; }
        .wrap h1 { float: left; font-size: 42px; color: #fff; }
        #main { width: 100%; }
        #main .wrap { margin-top: 60px; height: 476px; background: url({__SYSTEM_PATH__}/images/login-bg.png) no-repeat left center; }
        .form-box { float: right; margin-top: 20px; width: 440px; height: 430px; background-color: #fff; border-radius: 10px; }
        .form-tips { height: 88px; line-height: 88px; color: #2d83b5; font-size: 22px; text-align: center; width: 100%; }
        .form-fields { margin: 0 auto; margin-bottom: 30px; width: 370px; height: 48px; }
        .form-fields input { text-indent: 15px; width: 370px; height: 48px; border: 0; border-radius: 5px; background-color: #f5f6f8; font-size: 15px; color: #333; }
        .form-fields-code input { width: 210px; }
        .form-fields-code img { cursor: pointer; width: 150px; height: 48px; float: right; }
        .form-button { width: 370px; height: 48px; margin: 0 auto; }
        .form-button button { border: 0; background-color: #3084b5; width: 370px; height: 48px; border-radius: 5px; color: #fff; font-size: 17px; }
        .form-button button:hover { opacity: 0.8; }
        .form-button button.disable { color: #666; background-color: #eee; cursor:no-drop; }
        .copyright { width: 100%; font-size: 14px; color: #fff; text-align: center; position: absolute; left: 0; bottom: 10px; }
        .copyright a { color: #fff; font-size: 14px; }
        .copyright a:hover { text-decoration: underline; }
        .gov-icon { padding-left: 25px; background: url({__STATIC_PATH__}/images/yui_123.png) no-repeat; }
    </style>
</head>
<body>

    <div id="header">
        <div class="wrap">
            <h1>A3Mall</h1>
        </div>
    </div>

    <div id="main">
        <div class="wrap">
            <div class="form-box">
                <div class="form-tips">欢迎使用A3Mall商城系统！</div>
                <form id="theForm" method="post">
                    <div class="form-fields">
                        <input type="text" name="username" placeholder="用户名" autocomplete="off">
                    </div>
                    <div class="form-fields">
                        <input type="password" name="password" placeholder="密码" autocomplete="off">
                    </div>
                    <div class="form-fields form-fields-code">
                        <input type="text" name="code" maxlength="4" placeholder="验证码" autocomplete="off">
                        <img src="{:url('login/verify')}" alt="code">
                    </div>
                    <div class="form-button">
                        <button type="button">登 录</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="copyright">
        <span>Copyright 2020-<?php echo date("Y"); ?> <a href="http://www.a3-mall.com/" target="_blank">A3MALL</a>. All Rights Reserved.</span>
        {if isset($data.is_admin) && $data.is_admin==1}
            {if !empty($data.icp)}&nbsp;&nbsp;<span>备案信息：<a target="_blank" href="https://beian.miit.gov.cn/">{$data.icp|default=''}</a></span>{/if}
            {if !empty($data.gov_record)}&nbsp;&nbsp;<span class="gov-icon"><a target="_blank" href="{$data.gov_link|default=''}">公安备案：{$data.gov_record|default=''}</a></span>{/if}
            {if !empty($data.supervision_url)}&nbsp;&nbsp;<span>{$data.supervision_url|default=''}</span>{/if}
        {/if}
    </div>
    <script type="text/javascript">
        $('[name="username"]').focus();
        layui.use(["layer"],function(){
            var layer = layui.layer;

            $('[alt="code"]').on("click",function (){
                var timenow = new Date().getTime();
                var url = "{:createUrl('verify')}" + '?t=' + timenow;
                $(this).attr('src', url);
            });

            $(document).keypress(function(e) {
                if (e.ctrlKey && e.which == 13 || e.which == 10) {
                    $('[type="button"]').click();
                    document.body.focus();
                } else if (e.shiftKey && e.which == 13 || e.which == 10) {
                    $('[type="button"]').click();
                } else if (e.which == 13) {
                    $('[type="button"]').click();
                }
            });

            $('[type="button"]').on("click",function (){
                var that = $(this);
                if($(that).is(".disable")){
                    return false;
                }

                $(that).addClass("disable");
                $.post('{:createUrl("login/post")}',$("#theForm").serialize(),function(result){
                    if(result.code){
                        layer.msg(result.msg, { time:3000 },function (){
                            window.location.href = result.data;
                        });
                    }else{
                        layer.msg(result.msg);
                        $(that).removeClass("disable");
                        $('[alt="code"]').trigger("click");
                    }
                },"json");
                return false;
            });
        });
    </script>
</body>
</html>