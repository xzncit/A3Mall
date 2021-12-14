<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;数据管理</a></li>
            <li><a href="javascript:;">数据优化</a></li>
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

<script>
layui.use(['table','form'], function () {
    var table = layui.table;
    var form = layui.form;

    table.render({
        elem: '#list-box'
        , url: '{:createUrl("optimize")}'
        , toolbar: '#list-toolbar'
        , defaultToolbar: []
        , title: '数据表'
        , cols: [[
              {type: 'checkbox'}
            , {field: 'Name', title: '表名称'}
            , {field: 'Rows', title: '行数',width:110,align:'center'}
            , {field: 'Data_length', title: '数据量（字节）',width:130,align:'center'}
            , {field: 'Index_length', title: '索引占用磁盘',width:130,align:'center'}
            , {field: 'Version', title: '版本',width:100,align:'center'}
            , {field: 'Engine', title: '存储引擎',width:120,align:'center'}
            , {field: 'Create_time', title: '创建时间',width:180,align:'center'}
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
            case 'refresh':
                window.location.reload();
                break;
        }
    });
    
});
</script>





