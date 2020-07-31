<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;营销管理</a></li>
            <li><a href="javascript:;">会员特价</a></li>
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
        <button lay-event="url" type="button" class="layui-btn layui-btn-sm layui-bg-light-blue"><i class="layui-icon">&#xe61f;</i> 添加</button>
        <button lay-event="refresh" type="button" class="layui-btn layui-btn-sm layui-bg-red"><i class="layui-icon">&#xe9aa;</i> 刷新</button>
    </div>
</script>

<script type="text/html" id="list-bar">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
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
                , {field:'thumb_image', title:'封面', width:60,templet: function(res){
                        return '<img src="'+ res.thumb_image +'" style="max-width:30px; max-height:30px;">';
                }}
                , {field: 'title', title: '商品名称',align: "center"}
                , {field: 'sell_price', title: '销售价格', width: 160,align: "center"}
                , {field: 'spec', title: '商品规格', width: 130,align: "center"}
                , {field: 'create_time', title: '创建时间',width: 160,align: "center"}
                , {fixed: 'right', align: 'center', title: '操作', toolbar: '#list-bar', width: 130}
            ]]
            , page: true
            , id: 'list-table'
            , height: 'full-255'
            // ,limit:30
        });

        //头工具栏事件
        table.on('toolbar(list-box)', function (obj) {
            switch (obj.event) {
                case 'url':
                    window.location.href = "{:createUrl('editor')}";
                    break;
                case 'refresh':
                    window.location.reload();
                    break;
            }
        });

        //监听行工具事件
        table.on('tool(list-box)', function (obj) {
            var data = obj.data;
            if (obj.event === 'del') {
                layer.confirm('你确定要删除吗？', function (index) {
                    $.get('{:createUrl("delete")}',{
                        id : data.id
                    },function(result){
                        layer.close(index);
                        if(result.code){
                            obj.del();
                        }else{
                            layer.msg(result.msg,{ icon : 2 });
                        }
                    },"json");
                });
            } else if (obj.event === 'edit') {
                window.location.href = '{:createUrl("editor")}?id='+data.id;
            }
        });

    });
</script>