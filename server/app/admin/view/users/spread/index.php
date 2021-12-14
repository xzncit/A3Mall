<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;会员管理</a></li>
            <li><a href="javascript:;">分销会员</a></li>
        </ul>
    </div>
</div>

<div class="layui-fluid" id="search-box">
    <div class="layui-card">
        <div class="layui-card-body">
            <form class="layui-form layui-form-pane" action="">

                <div class="layui-form-item">

                    <div class="layui-inline">
                        <label class="layui-form-label seller-inline-2">用户名：</label>
                        <div class="layui-input-inline seller-inline-4">
                            <input type="text" name="title" placeholder="请输入名称" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <button type="button" id="search-btn" class="layui-btn layui-btn-sm layui-bg-light-blue"><i class="layui-icon layui-icon-search"></i> 搜索</button>
                        <!--                        <button type="button" id="query-btn" class="layui-btn layui-btn-sm layui-btn-danger"><i class="layui-icon layui-icon-search"></i> 查询</button>-->
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
    <div class="layui-btn-container">
        <button lay-event="editor" type="button" class="layui-btn layui-btn-sm layui-bg-blue"><i class="layui-icon">&#xe61f;</i> 添加</button>
        <button lay-event="refresh" type="button" class="layui-btn layui-btn-sm layui-bg-red"><i class="layui-icon">&#xe9aa;</i> 刷新</button>
        {if $pid > 0}
        <button lay-event="return" type="button" class="layui-btn layui-btn-sm layui-bg-orange"><i class="layui-icon">&#xe603;</i> 返回</button>
        {/if}
    </div>
</script>

<script type="text/html" id="list-bar">
    {{#  if(d.level_id == 3){ }}
    {{#  } else { }}
    <a class="layui-btn layui-btn-xs layui-bg-blue" lay-event="distributor">下级人员</a>
    {{#  } }}
    <a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="log">返现明细</a>
    {{#  if(d.is_spread == 1){ }}
    <a class="layui-btn layui-btn-xs layui-bg-red" lay-event="close">冻结分销</a>
    {{#  } else { }}
    <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="add">开启分销</a>
    {{#  } }}
</script>

<script>
    layui.use(['table','form'], function () {
        var table = layui.table;
        var form = layui.form;

        table.render({
            elem: '#list-box'
            , url: '{:createUrl("index",["pid"=>$pid])}'
            , toolbar: '#list-toolbar'
            , defaultToolbar: []
            , title: '数据表'
            , cols: [[
                {type: 'checkbox'}
                , {field: 'id', title: 'ID', width: 80, unresize: true, sort: true,align:"center"}
                , {field: 'spread_name', title: '推荐人',align: "center"}
                , {field: 'username', title: '会员名称',align: "center"}
                , {field: 'pay_count', title: '订单数量',width:100,align:"center"}
                , {field: 'order_amount', title: '订单金额',width:100,align:"center"}
                , {field: 'brokerage_amount', title: '佣金金额',width:100,align:"center"}
                , {field: 'withdraw_amount', title: '己提现金额',width:100,align:"center"}
                , {field: 'spread_amount', title: '待提现金额',width:100,align:"center"}
                , {field: 'is_spread', title: '状态',width:80,align:"center",templet:function (res){
                    return res.is_spread ==1 ? "<span style='color: green'>开启</span>" : "<span style='color:red'>冻结</span>"
                }}
                , {field: 'spread_time', title: '开通时间', width: 170, align: "center"}
                , {fixed: 'right', align: 'center', title: '操作', toolbar: '#list-bar', width: 240}
            ]]
            , text: {
                none: '<div><i class="layui-icon">&#xe702;</i>暂无相关数据</div>'
            }
            , page: true
            , id: 'list-table'
            , height: 'full-255'
            // ,limit:30
        });

        $("#search-btn").on("click",function (){
            table.reload('list-table', {
                page: {
                    curr: 1
                }
                ,where: {
                    key: {
                        title : $('[name="title"]').val()
                    }
                }
            }, 'data');
        });

        $("#query-btn").on("click",function (){
            layer.open({
                type: 2,
                title: "查询",
                area: ['700px', '450px'],
                fixed: true,
                content: '{:createUrl("common.ajax/query")}'
            });
        });

        //头工具栏事件
        table.on('toolbar(list-box)', function (obj) {
            switch (obj.event) {
                case 'refresh':
                    window.location.reload();
                    break;
                case 'return':
                    window.history.go(-1);
                    break;
                case 'editor':
                    layer.open({
                        type: 2,
                        title: '查询会员',
                        shadeClose: true,
                        shade: 0.8,
                        area: ['80%', '90%'],
                        content: '{:createUrl("common.ajax/get_users")}'
                    });
                    break;
            }
        });

        //监听行工具事件
        table.on('tool(list-box)', function (obj) {
            var data = obj.data;
            if (obj.event === 'log') {
                window.location.href = '{:createUrl("log")}?id='+data.id;
            }else if(obj.event == 'distributor'){
                window.location.href = '{:createUrl("index")}?pid='+data.id;
            }else if(obj.event == 'close'){
                $.get('{:createUrl("close")}',{id:data.id},function (res){
                    window.location.reload();
                },"json");
            }else if(obj.event == 'add'){
                $.get('{:createUrl("add")}',{id:data.id},function (res){
                    window.location.reload();
                },"json");
            }
        });

    });
</script>





