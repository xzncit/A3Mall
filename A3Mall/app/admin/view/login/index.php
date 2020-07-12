{__NOLAYOUT__}<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>A3Mall | Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/font/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/css/AdminLTE.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/css/base.css">
    <script src="{__SYSTEM_PATH__}/js/jquery/jquery.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/fastclick/fastclick.js"></script>
    <script src="{__SYSTEM_PATH__}/js/adminlte/adminlte.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/layui/layui.all.js"></script>
    <script src="{__SYSTEM_PATH__}/js/common/common.js"></script>
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>A3Mall</b></a>
        </div>

        <div class="login-box-body">
            <p class="login-box-msg">欢迎使用A3Mall商城系统！</p>

            <form action="" id="theForm" method="post">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="username" placeholder="用户名">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="密码">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>

                <div class="row code-box">
                    <div class="col-xs-7">
                        <input type="text" name="code" class="form-control" placeholder="请输入验证码" autocomplete="off">
                        <span class="glyphicon glyphicon-exclamation-sign form-control-feedback" style="z-index:1;"></span>
                    </div>
                    <div class="col-xs-5">
                        <img src="{:url('login/verify')}" alt="code">
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">登录</button>
                    </div>
                </div>
            </form>


        </div>
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
                    $('[type="submit"]').click();
                    document.body.focus();
                } else if (e.shiftKey && e.which == 13 || e.which == 10) {
                    $('[type="submit"]').click();
                } else if (e.which == 13) {
                    $('[type="submit"]').click();
                }
            });

            $('[type="submit"]').on("click",function (){
                var that = $(this);
                if($(that).is(".on")){
                    return false;
                }

                $(that).addClass("on");
                $.post('{:createUrl("login/post")}',$("#theForm").serialize(),function(result){
                    if(result.code){
                        layer.msg(result.msg, {
                            offset: '60px', anim: 5, time:3000
                        },function (){
                            window.location.href = result.data;
                        });
                    }else{
                        layer.msg(result.msg, {
                            offset: '60px',
                            anim: 5
                        });
                        $(that).removeClass("on");
                        $('[alt="code"]').trigger("click");
                    }
                },"json");
                return false;
            });
        });
    </script>
</body>
</html>