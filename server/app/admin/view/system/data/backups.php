<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;数据管理</a></li>
            <li><a href="javascript:;">数据备份</a></li>
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
        <button lay-event="backup" type="button" class="layui-btn layui-btn-sm layui-bg-red"><i class="layui-icon">&#xe631;</i> 备份数据</button>
    </div>
</script>

<script type="text/html" id="list-bar">
    <a class="layui-btn layui-btn-xs" lay-event="import">导入</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>

<script>
layui.use(['table','form'], function () {
    var table = layui.table;
    var form = layui.form;

    table.render({
        elem: '#list-box'
        , url: '{:createUrl("backups")}'
        , toolbar: '#list-toolbar'
        , defaultToolbar: []
        , title: '数据表'
        , cols: [[
                  {type: 'checkbox'}
                , {field: 'name', title: '名称'}
                , {field: 'time', title: '创建时间',width:180,align:'center'}
                , {fixed: 'right', align: 'center', title: '操作', toolbar: '#list-bar', width: 130}
            ]]
        , text: {
            none: '<div><i class="layui-icon">&#xe702;</i>暂无相关数据</div>'
        }
        , page: false
        , height: 'full-200'
        // ,limit:30
    });

    //头工具栏事件
    table.on('toolbar(list-box)', function (obj) {
        switch (obj.event) {
            case 'backup':
                layer.confirm('你确定要备份数据库吗？', function (index) {
                    layer.close(index);
                    var llll = layer.load(1, {
                        shade: [0.3,'#000']
                    });
                    $.get('{:createUrl("backups")}',{
                        type: "backup"
                    },function(result){
                        layer.close(llll);
                        if(result.code){
                            window.location.reload();
                        }else{
                            layer.msg(result.msg,{ icon : 2 });
                        }
                    },"json");
                });
                break;
        }
    });

    //监听行工具事件
    table.on('tool(list-box)', function (obj) {
        var data = obj.data;
        if (obj.event === 'del') {
            layer.confirm('你确定要删除吗？', function (index) {
                $.get('{:createUrl("delete")}',{
                    file : data.name
                },function(result){
                    layer.close(index);
                    if(result.code){
                        obj.del();
                    }else{
                        layer.msg(result.msg,{ icon : 2 });
                    }
                },"json");
            });
        } else if (obj.event === 'import') {
            layer.confirm('你确定要还原数据吗，此操作将会删除现有数据？', function (index) {
                layer.close(index);
                var llll = layer.load(1, {
                    shade: [0.3,'#000']
                });
                $.get('{:createUrl("import")}',{
                    file : data.name
                },function(result){
                    layer.close(llll);
                    if(result.code){
                        layer.msg(result.msg,{ icon : 1 });
                    }else{
                        layer.msg(result.msg,{ icon : 2 });
                    }
                },"json");
            });
        }
    });
    
});
</script>





