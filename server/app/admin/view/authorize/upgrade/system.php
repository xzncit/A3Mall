<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;系统升级</a></li>
            <li><a href="javascript:;">版本列表</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-list-box">
        <table class="layui-table">
            <colgroup>
                <col>
                <col width="200">
                <col width="200">
                <col width="200">
            </colgroup>
            <thead>
                <tr>
                    <th style="text-align: center">产品名称</th>
                    <th style="text-align: center">程序版本</th>
                    <th style="text-align: center">发布时间</th>
                    <th style="text-align: center">下载</th>
                </tr>
            </thead>
            <tbody>
                {if !empty($data)}{volist name="data" id="vo"}
                <tr>
                    <td style="text-align: center">{$vo.title}</td>
                    <td style="text-align: center">v{$vo.version}</td>
                    <td style="text-align: center">{$vo.time}</td>
                    <td style="text-align: center">
                        <a href="{$vo.download|raw}" class="layui-btn layui-btn-xs layui-btn-danger">下载</a>
                    </td>
                </tr>
                {/volist}
                {else}
                <tr><td colspan="4" style="text-align: center;">当前程序为最新版本</td></tr>
                {/if}
            </tbody>
        </table>
    </div>
</section>







