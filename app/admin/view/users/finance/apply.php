<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>财务管理</a></li>
            <li><a href="javascript:;">提现申请</a></li>
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

<script type="text/html" id="list-bar">
    <a class="layui-btn layui-btn-xs" lay-event="handle">处理</a>
</script>

<style type="text/css">.layui-table-cell{ height:auto !important; } </style>
<script>
layui.use(['table','form'], function () {
    var table = layui.table;
    var form = layui.form;

    table.render({
        elem: '#list-box'
        , url: '{:createUrl("apply")}'
        , toolbar: '#list-toolbar'
        , defaultToolbar: []
        , title: '数据表'
        , cols: [[
                  {type: 'checkbox'}
                , {field: 'username', title: '会员名称',width:150}
                , {field: 'type', title: '支付类型',width:150}
                , {field: 'string', title: '提现方式'}
                , {field: 'price', title: '提现金额',width:150,align:'center'}
                , {field: 'status', title: '审核状态',width:150,align:'center'}
                , {field: 'create_time', title: '创建时间',width:180,align:'center'}
                , {align: 'center', title: '操作', toolbar: '#list-bar', width: 80}
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
        if (obj.event === 'handle') {
            layer.open({
                type: 2,
                title: '提现管理',
                shadeClose: true,
                shade: 0.3,
                area: ['60%', '80%'],
                content: data.url
            });
        }
    });
    
});
</script>





