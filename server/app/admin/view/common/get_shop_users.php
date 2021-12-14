{__NOLAYOUT__}
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>A3Mall | Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/font/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/layui/css/layui.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/css/base.css">
    <script src="{__SYSTEM_PATH__}/js/jquery/jquery.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/bootstrap/js/bootstrap.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/layui/layui.js"></script>
    <script src="{__SYSTEM_PATH__}/js/common/common.js"></script>
</head>
<body>

<div class="layui-fluid" id="search-box">
    <div class="layui-card">
        <div class="layui-card-body">
            <form class="layui-form layui-form-pane" action="">

                <div class="layui-form-item">

                    <div class="layui-inline">
                        <label class="layui-form-label seller-inline-2">选择分组：</label>
                        <div class="layui-input-inline seller-inline-4">
                            <select name="cat_id">
                                <option value="-1">全部</option>
                                {if !empty($cat)}
                                {volist name="cat" id="value"}
                                <option value="{$value.id}">{$value.name}</option>
                                {/volist}
                                {/if}
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-label seller-inline-2">用户名：</label>
                        <div class="layui-input-inline seller-inline-4">
                            <input type="text" name="title" placeholder="请输入用户名" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <button type="button" id="search-btn" class="layui-btn layui-btn-sm layui-bg-light-blue"><i class="layui-icon layui-icon-search"></i> 搜索</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-list-box">
        <table class="layui-hide" id="list-box" lay-filter="list-box"></table>
    </div>
</section>

<script type="text/html" id="list-toolbar">
    <div class="layui-btn-container"></div>
</script>

<script type="text/html" id="list-bar">
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="selected">选中</a>
</script>

<script>
    layui.use(['table','layer','form'], function () {
        var table = layui.table;
        var form = layui.form;
        var layer = layui.layer;

        table.render({
            elem: '#list-box'
            , url: '{:createUrl("common.ajax/get_shop_users")}'
            , toolbar: '#list-toolbar'
            , defaultToolbar: []
            , title: '数据表'
            , cols: [[
                {type: 'checkbox'}
                , {field: 'group_name', title: '所属分组',width:120}
                , {field: 'username', title: '用户名'}
                , {field: 'tags', title: '标签',width:120}
                , {field: 'point', title: '积分',width:100,align:"center"}
                , {field: 'amount', title: '余额',width:120,align:"center"}
                , {field:'status', title:'状态', width:60,align:"center",templet: function(res){
                        switch(res.status){
                            case 0:
                                return "<span style='color:green;'>正常</span>";
                            case 1:
                                return "<span style='color:#FFB800;'>审核</span>";
                            case 2:
                                return "<span style='color:#01AAED;'>锁定</span>";
                            case 3:
                                return "<span style='color:#FF5722;'>删除</span>";
                        }
                    }}
                , {field: 'create_time', title: '注册时间', width: 180, align: "center"}
                , {fixed: 'right', align: 'center', title: '操作', toolbar: '#list-bar', width: 110}
            ]]
            , text: {
                none: '<div><i class="layui-icon">&#xe702;</i>暂无相关数据</div>'
            }
            , page: true
            , id: 'list-table'
            , height: 'full-255'
            // ,limit:30
        });
        // address,collect
        $("#search-btn").on("click",function (){
            table.reload('list-table', {
                page: {
                    curr: 1
                }
                ,where: {
                    key: {
                        cat_id : $('[name="cat_id"]').val(),
                        title : $('[name="title"]').val()
                    }
                }
            }, 'data');
        });

        //监听行工具事件
        table.on('tool(list-box)', function (obj) {
            var data = obj.data;
            if (obj.event === 'selected') {
                // layer.confirm('您确定要将该会员设置成推广员吗？', {
                //     btn: ['确定','取消'] //按钮
                // }, function(){
                //     $.get("{:createUrl('users.spread/add')}",{ id: data.id },function (res){
                //         parent.window.location.reload();
                //     },"json");
                // }, function(){});
                parent.handleUsers(data);
                parent.layer.closeAll();
            }
        });
    });
</script>

</body>
</html>