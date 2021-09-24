<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>A3Mall | Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/font/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/css/AdminLTE.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/layui/css/layui.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/css/base.css">
    <script src="{__SYSTEM_PATH__}/js/jquery/jquery.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/bootstrap/js/bootstrap.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/layui/layui.js"></script>
    <script src="{__SYSTEM_PATH__}/js/fastclick/fastclick.js"></script>
    <script src="{__SYSTEM_PATH__}/js/adminlte/adminlte.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/common/common.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            contentType:"application/x-www-form-urlencoded;charset=utf-8",
            complete:function(XMLHttpRequest,textStatus){
                var res = XMLHttpRequest.responseText;
                try{
                    var jsonData = JSON.parse(res);
                    if(jsonData.code == -1000){
                        window.location.href = '{:createUrl("login/index")}';
                    }else if(jsonData.code == -999){
                        layer.msg(jsonData.msg,{ icon: 2, time: 5000 },function () {
                            window.history.go(-1);
                        });
                    }
                }catch(e){}
            }
        });
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini fixed">
<div class="wrapper">

    <header class="main-header">

        <!-- Logo -->
        <a href="{:createUrl('platform.index/index')}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A3</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>A3Mall</b></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <div class="navbar-custom-menu navbar-left navbar-list-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <ul class="nav navbar-nav">
                    {if isset($sidebar.top)}
                        {volist name="sidebar['top']" id="menu"}
                        <li class="{if $menu.active}active{/if}"><a href="{$menu.url}">{$menu.name}</a></li>
                        {/volist}
                    {/if}
                </ul>
            </div>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li><a href="/" target="_blank"><i class="fa fa-home"></i>&nbsp;网站前台</a></li>
                    <li><a href="{:url('login/logout')}"><i class="fa fa-sign-out"></i>&nbsp;退出</a></li>
                </ul>
            </div>

        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{__SYSTEM_PATH__}/images/avatar.jpeg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{:session("users.username")}</p>
                    <p style="font-size: 12px;">{:session("users.title")}</p>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">系统菜单</li>
                {if !empty($sidebar.menu)}
                {volist name="sidebar['menu']" id="side"}
                <li class="treeview{if $side.active} active menu-open{/if}">
                    <a href="javascript:;">
                        <i class="{$side.icon}"></i> <span>{$side.name}</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        {volist name="side['children']" id="children"}
                        <li {if $children.active}class="active"{/if}>
                        <a href="{$children.url}"><i class="fa fa-circle-o"></i>{$children.name}</a>
                        </li>
                        {/volist}
                    </ul>
                </li>
                {/volist}
                {/if}
                <li class="header">联系方式</li>
                <li><a href="http://www.a3-mall.com" target="_blank"><i class="fa fa-circle-o text-yellow"></i> <span>官方网站</span></a></li>
                <li><a href="http://wpa.qq.com/msgrd?v=3&uin=551040106&site=qq&menu=yes"><i class="fa fa-circle-o text-red"></i> <span>QQ：551040106</span></a></li>
                <li><a href="https://qm.qq.com/cgi-bin/qm/qr?k=lBxucAil6e6WTlwX0tNvQwpOtfLP2ptd&jump_from=webapi"><i class="fa fa-circle-o text-red"></i> <span>Q群：892150829</span></a></li>
                <li><a href="javascript:;"><i class="fa fa-circle-o text-aqua"></i> <span>手机：18026740326</span></a></li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <div class="content-wrapper clearfix">
        {__CONTENT__}
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> {$Think.config.version.version}
        </div>
        <strong>Copyright &copy; 2019-<?php echo date("Y"); ?> <a href="http://www.a3-mall.com">数循通云计算科技有限公司 | A3Mall</a>.</strong> All rights
        reserved.
    </footer>

</div>

</body>
</html>