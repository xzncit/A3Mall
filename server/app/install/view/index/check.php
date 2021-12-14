<section class="section">
    <div class="step">
        <ul>
            <li class="current"><em>1</em>检测环境</li>
            <li><em>2</em>设置数据</li>
            <li><em>3</em>完成安装</li>
        </ul>
    </div>

    <div class="server">
        <table width="100%">
            <tr>
                <td class="td1">运行环境</td>
                <td class="td1" width="30%">所需配置</td>
                <td class="td1" width="40%">当前配置</td>
            </tr>

            {volist name="env" id="item"}
            <tr>
                <td>{$item[0]}</td>
                <td>{$item[1]}</td>
                <td><i class="icon_{$item[4]}"></i>{$item[3]}</td>
            </tr>
            {/volist}
        </table>

        <table width="100%">
            <tr>
                <td class="td1">目录/文件</td>
                <td class="td1" width="30%">所需状态</td>
                <td class="td1" width="40%">当前状态</td>
            </tr>

            {volist name="dirFile" id="item"}
            <tr>
                <td>{$item[3]}</td>
                <td>可写</td>
                <td><i class="icon_{$item[2]}"></i>{$item[1]}</td>
            </tr>
            {/volist}
        </table>

        <table width="100%">
            <tr>
                <td class="td1">扩展依赖</td>
                <td class="td1" width="30%">类型</td>
                <td class="td1" width="40%">检查结果</td>
            </tr>

            {volist name="func" id="item"}
            <tr>
                <td>{$item[0]}</td>
                <td>{$item[3]}</td>
                <td><i class="icon_{$item[2]}"></i>{$item[1]}</td>
            </tr>
            {/volist}
        </table>
    </div>

    <div class="bottom tac">
        <a href="javascript:;" onclick="location.reload()" class="btn">重新检测</a>
        {eq name="isNext" value="true"}
        <a href="{:str_replace('/install/','/',createUrl('index/config'))}" class="btn">下一步</a>
        {/eq}
    </div>
</section>