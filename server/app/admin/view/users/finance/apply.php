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
    <div style="float: left">
        <div class="layui-form-pos-btn">
            <div class="tab-list-btn active">全部</div>
            <div class="tab-list-btn">未处理</div>
            <div class="tab-list-btn">已处理</div>
            <div class="tab-list-btn">已拒绝</div>
        </div>
    </div>
</script>

<script type="text/html" id="list-bar">
    {{# if(d.status == 0){ }}
    <a class="layui-btn layui-btn-xs" lay-event="handle">待处理</a>
    {{# } }}

    {{# if(d.status != 0){ }}
    <a class="layui-btn layui-btn-xs layui-btn-danger">已处理</a>
    {{# } }}
</script>

<style type="text/css">.layui-table-cell{ height:auto !important; } </style>
<script>
window.active = {:input("param.type",0)};
layui.use(['table','form'], function () {
    var table = layui.table;
    var form = layui.form;

    table.render({
        elem: '#list-box'
        , url: '{:createUrl("apply",["key[status]"=>input("param.type",0)])}'
        , toolbar: '#list-toolbar'
        , defaultToolbar: []
        , title: '数据表'
        , cols: [[
                  {type: 'checkbox'}
                , {field: 'username', title: '会员名称',width:150}
                , {field: 'type_name', title: '支付类型',width:150}
                , {field: 'string', title: '提现方式'}
                , {field: 'price', title: '提现金额',width:150,align:'center'}
                , {field: 'status_name', title: '审核状态',width:150,align:'center'}
                , {field: 'create_time', title: '创建时间',width:180,align:'center'}
                , {align: 'center', title: '操作', toolbar: '#list-bar', width: 80}
            ]]
        ,done: function (){
            $(".tab-list-btn").removeClass("active").eq(window.active).addClass("active");
        }
        , text: {
            none: '<div><i class="layui-icon">&#xe702;</i>暂无相关数据</div>'
        }
        , page: true
        , height: 'full-200'
        // ,limit:30
    });

    $(document).on("click",".tab-list-btn",function (){
        window.active = $(this).index();
        table.reload('list-box', {
            page: {
                curr: 1
            }
            , where: {
                key: {
                    status : window.active
                }
            }
        }, 'data');
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
                content: '{:createUrl("handle")}?id='+data.id
            });
        }
    });
    
});
</script>





