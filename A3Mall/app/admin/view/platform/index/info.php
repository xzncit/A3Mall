<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;系统信息</a></li>
            <li><a href="javascript:;">详细信息</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-list-box">

        <div class="layui-col-md12" style="margin-bottom: 15px;">
            <div class="layui-card">
                <div class="layui-card-header">产品信息</div>
                <div class="layui-card-body">
                    <table class="layui-table">
                        <tbody>
                        <tr>
                            <td colspan='2'>产品名称：A3Mall</td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">程序版本：{$Think.config.base.app_version}</td>
                            <td>ThinkPHP版本	：{$think_ver}</td>
                        </tr>
                        <tr>
                            <td>项目地址：<a href="https://gitee.com/xzncit/A3Mall">gitee</a>&nbsp;<a href="https://github.com/xzncit/A3Mall">github</a></td>
                            <td>BUG反馈：<a href="https://gitee.com/xzncit/A3Mall">gitee</a></td>
                        </tr>
                        <tr>
                            <td>团队官网：<a href="http://www.a3-mall.com">http://www.a3-mall.com</a></td>
                            <td>开发团队：A3Mall</td>
                        </tr>
                        <tr>
                            <td colspan='2'>官方QQ群：
                                <a href="#" target="_blank">
                                    <a target="_blank" href="//shang.qq.com/wpa/qunwpa?idkey=de316f1a1dbf61859529484891ee50369e3c2bc6fe37e15bb94f8bf731cc3482"><img style="height:18px;width:auto" border="0" src="//pub.idqqimg.com/wpa/images/group.png" alt="A3Mall - 官方交流群" title="A3Mall - 官方交流群"></a>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">服务器信息</div>
                <div class="layui-card-body">
                    <table class="layui-table">
                        <tbody>
                        <tr>
                            <td style="width:50%;">操作系统：<?php echo PHP_OS; ?></td>
                            <td style="width:50%;">网站IP：{$ip}</td>
                        </tr>
                        <tr>
                            <td>PHP运行方式：<?php echo php_sapi_name(); ?></td>
                            <td>MySQL版本：{$version}</td>
                        </tr>
                        <tr>
                            <td>网站域名：<?php echo $_SERVER['SERVER_NAME']; ?></td>
                            <td>运行PHP版本：{$Think.PHP_VERSION}</td>
                        </tr>
                        <tr>
                            <td>magic_quotes_gpc：<?php echo (1 === get_magic_quotes_gpc()) ? 'YES' : 'OFF'; ?></td>
                            <td>register_globals：<?php echo get_cfg_var("register_globals") == "1" ? "YES" : "OFF"; ?></td>
                        </tr>

                        <tr>
                            <td>执行时间限制：<?php echo ini_get('max_execution_time'); ?>秒</td>
                            <td>运行环境：<?php echo $_SERVER["SERVER_SOFTWARE"]; ?></td>
                        </tr>
                        <tr>
                            <td>Web服务端口：<?php echo $_SERVER['SERVER_PORT'] ?></td>
                            <td>上传最大值：<?php echo ini_get('post_max_size'); ?></td>
                        </tr>
                        <tr>
                            <td>北京时间：<?php echo gmdate("Y-n-j H:i:s", time() + 8 * 3600); ?></td>
                            <td>服务器时间：<?php echo date("Y-n-j H:i:s"); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>