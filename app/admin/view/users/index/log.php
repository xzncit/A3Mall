<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;会员管理</a></li>
            <li><a href="javascript:;">会员日志</a></li>
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
        <button lay-event="return" type="button" class="layui-btn layui-btn-sm layui-bg-orange"><i class="layui-icon">&#xe603;</i> 返回</button>
    </div>
</script>

<script>
layui.use(['table','form'], function () {
    var table = layui.table;
    var form = layui.form;

    table.render({
        elem: '#list-box'
        , url: '{:createUrl("log",["id"=>$id])}'
        , toolbar: '#list-toolbar'
        , defaultToolbar: []
        , title: '数据表'
        , cols: [[
                  {type: 'checkbox'}
                , {field: 'username', title: '会员名称',align:'center',width:150}
                , {field: 'description', title: '信息'}
                , {field: 'action', title: '动作',width:150,align:'center',templet:function(res){
                    switch(res.action){
                        case 0:
                            return "<span style='color:#333;'>金额</span>";
                        case 1:
                            return "<span style='color:#333;'>积分</span>";
                        case 2:
                            return "<span style='color:#333;'>经验</span>";
                        case 3:
                            return "<span style='color:#333;'>退款</span>";
                    }
                }}
                , {field: 'operation', title: '操作',width:150,align:'center',templet:function(res){
                    switch(res.action){
                        case 0:
                            return "<span style='color:#333;'>充值</span>";
                        case 1:
                            return "<span style='color:#333;'>提现</span>";
                    }
                }}
                , {field: 'create_time', title: '创建时间',width:180,align:'center'}
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
            case 'return':
                window.location.href = '{:createUrl("index")}';
                break;
        }
    });
    
});
</script>

