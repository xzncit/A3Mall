<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;支付管理</a></li>
            <li><a href="javascript:;">支付列表</a></li>
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
    {{#  if(d.id != 1){ }}
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    {{#  } }}
</script>

<script>
    layui.use(['table','form'], function () {
        var table = layui.table;
        var form = layui.form;

        table.render({
            elem: '#list-box'
            , url: '{:createUrl("index")}'
            , toolbar: '#list-toolbar'
            , defaultToolbar: []
            , title: '数据表'
            , cols: [[
                {type: 'checkbox'}
                , {field: 'id', title: 'ID', width: 80, unresize: true, sort: true,align:"center"}
                , {field: 'name', title: '用户名'}
                , {field: 'sort', title: '排序', edit:true, width: 100, align:"center"}
                , {field:'status', title:'状态', width:120,align:"center",templet: '#checkboxTpl'}
                , {fixed: 'right', align: 'center', title: '操作', toolbar: '#list-bar', width: 130}
            ]]
            , text: {
                none: '<div><i class="layui-icon">&#xe702;</i>暂无相关数据</div>'
            }
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
            if (obj.event != 'edit') {
                return ;
                //window.location.href = '{:createUrl("editor")}?id='+data.id;
            }
            switch(data.code) {
                case "wechat-mp":
                case "wechat-h5":
                case "wechat-qrcode":
                    window.location.href = '{:createUrl("wechat.index/pay")}';
                    break;
                case "wechat-app":
                    window.location.href = '{:createUrl("editor")}?id='+data.id;
                    break;
                case "wechat-mini":
                    window.location.href = '{:createUrl("wechat.mini/pay")}';
                    break;
                case "alipay-app":
                case "alipay-web":
                case "alipay-wap":
                    window.location.href = '{:createUrl("editor")}?id='+data.id;
                    break;
            }
        });

        //监听单元格编辑
        table.on('edit(list-box)', function(obj){
            $.get('{:createUrl("field")}',{
                name : obj.field,
                value : obj.value,
                id : obj.data.id
            },function (result){
                if(!result.code){
                    layer.msg(result.msg,{ icon : 2 });
                }
            },"json");
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
