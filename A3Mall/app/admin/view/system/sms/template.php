<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;短信管理</a></li>
            <li><a href="javascript:;">短信模板</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-list-box">
        <table class="layui-hide" id="list-box" lay-filter="list-box"></table>
    </div>
</section>

<script type="text/html" id="list-toolbar">
    <div class="layui-btn-container">
        <button lay-event="refresh" type="button" class="layui-btn layui-btn-sm layui-bg-red"><i class="layui-icon">&#xe9aa;</i> 刷新</button>
    </div>
</script>

<script type="text/html" id="checkboxTpl">
    <input type="checkbox" name="status" value="{{d.id}}" title="开启" lay-filter="status-filter" {{ d.status == 0 ? 'checked' : '' }}>
</script>

<script type="text/html" id="list-bar">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
</script>

<script>
    layui.use(['table','form'], function () {
        var table = layui.table;
        var form = layui.form;

        table.render({
            elem: '#list-box'
            , url: '{:createUrl("template")}'
            , toolbar: '#list-toolbar'
            , defaultToolbar: []
            , title: '数据表'
            , cols: [[
                  {type: 'checkbox'}
                , {field: 'sign', title: '标识',width:160,align: "center"}
                , {field: 'sign_name', title: '签名名称',width:160,align: "center"}
                , {field: 'template_code', title: '模版CODE',width:160,align: "center"}
                , {field: 'template_name', title: '模板名称',align: "center"}
                , {field: 'template_param', title: '模板变量',width:250}
                , {fixed: 'right', align: 'center', title: '操作', toolbar: '#list-bar', width: 80}
            ]]
            , page: true
            , height: 'full-200'
            // ,limit:30
        });

        //头工具栏事件
        table.on('toolbar(list-box)', function (obj) {
            switch (obj.event) {
                case 'refresh':
                    window.location.reload();
                    break;
            }
        });

        //监听行工具事件
        table.on('tool(list-box)', function (obj) {
            var data = obj.data;
            if (obj.event === 'edit') {
                window.location.href = data.url;
            }
        });

        //监听锁定操作
        form.on('checkbox(status-filter)', function(obj){
            var that = this;
            $.get('{:createUrl("field")}',{
                name : that.name,
                value : (obj.elem.checked ? 0 : 1),
                id : that.value
            },function (result){
                if(!result.code){
                    layer.msg(result.msg,{ icon : 2 });
                }
            },"json");
        });
    });
</script>