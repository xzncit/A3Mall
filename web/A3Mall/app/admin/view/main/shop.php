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
                <ul class="nav navbar-nav">
                    {if !empty($sidebar.top)}
                    {volist name="sidebar['top']" id="menu"}
                    <li class="{if $menu.active}active{/if}"><a href="{$menu.url}">{$menu.name}</a></li>
                    {/volist}
                    {/if}
                </ul>
            </div>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li><a href="{:url('home/index/index')}" target="_blank"><i class="fa fa-home"></i>&nbsp;网站前台</a></li>
                    <li><a href="{:url('login/logout')}"><i class="fa fa-sign-out"></i>&nbsp;退出</a></li>
                </ul>
            </div>

        </nav>
    </header>

    <aside class="main-sidebar">
       <div class="sidebar-pages">
            <a href="javascript:;" class="active">
                <i class="fa fa-sticky-note-o"></i>
                页面
            </a>
           <a href="{:createUrl('select')}">
               <i class="fa fa-undo"></i>
               返回
           </a>
       </div>
       <div class="sidebar-pages-left">
           <div class="sidebar-pages-left-box">
               <ul class="layui-nav layui-nav-tree layui-inline custom-theme" style="width: 100%;" lay-shrink="all">
                   <li class="layui-nav-item layui-nav-itemed {if $Request.action == 'manage' || $Request.action == 'category'}layui-this{/if}">
                       <a href="javascript:;">店铺装修</a>
                       <dl class="layui-nav-child">
                           <dd class="{if $Request.action == 'manage' && $Request.param.code =='home'}layui-this{/if}">
                               <a href="{:createUrl('manage',['type'=>$Request.param.type,'code'=>'home'])}">首页装修</a>
                           </dd>
                           <dd class="{if $Request.action == 'category' && $Request.param.code =='category'}layui-this{/if}">
                               <a href="{:createUrl('category',['type'=>$Request.param.type,'code'=>'category'])}">分类页面</a>
                           </dd>
                       </dl>
                   </li>
                   <li class="layui-nav-item {if $Request.action == 'index'}layui-this{/if}">
                       <a href="{:createUrl('index',['type'=>$Request.param.type])}">自定义页</a>
                   </li>

               </ul>
           </div>
       </div>
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
<script>
    layui.use(['element'], function(){});
    $(function (){
        $(".sidebar-pages-left-box").slimScroll({
            width: '100%',
            height: ($(".sidebar-pages").outerHeight(true) - 50) + 'px',
            alwaysVisible: true,
            wheelStep: 5,
        });
    })
</script>
</body>
</html>