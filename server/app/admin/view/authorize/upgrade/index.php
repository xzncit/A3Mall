<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;授权管理</a></li>
            <li><a href="javascript:;">授权信息</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-list-box">

        <div class="layui-col-md12" style="margin-bottom: 15px;">
            <div class="layui-card">
                <div class="layui-card-header">授权信息</div>
                <div class="layui-card-body">
                    <table class="layui-table">
                        <tbody>
                        {if !empty($data)}
                            <tr>
                                <td colspan="2" style="text-align: center;">产品名称：{$Think.config.version.title}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">程序版本：v{$Think.config.version.version}</td>
                                <td style="width: 50%;">最新版本：v{$data.version}</td>
                            </tr>
                            <tr>
                                <td>授权状态</td>
                                <td>{$data.status}</td>
                            </tr>
                            <tr>
                                <td>用户名称</td>
                                <td>{$data.name}</td>
                            </tr>
                            <tr>
                                <td>授权时间</td>
                                <td>{$data.time}</td>
                            </tr>
                            <tr>
                                <td>授权类型</td>
                                <td>{$data.auth_type}</td>
                            </tr>
                            <tr>
                                <td>授权域名</td>
                                <td>{$data.domain}</td>
                            </tr>
                        {else}
                            <tr>
                                <td colspan="2" style="text-align: center;"><a href="http://www.a3-mall.com/authorized/price.html" target="_blank">系统还未授权，去授权</a></td>
                            </tr>
                        {/if}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>





