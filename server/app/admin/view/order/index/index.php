<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;订单管理</a></li>
            <li><a href="javascript:;">订单列表</a></li>
        </ul>
    </div>
</div>

<div class="layui-fluid" id="search-box">
    <div class="layui-card">
        <div class="layui-card-body">
            <form class="layui-form layui-form-pane" action="">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label seller-inline-2">支付状态：</label>
                        <div class="layui-input-inline seller-inline-4">
                        <select name="pay_type">
                            <option value="-1">全部</option>
                            <option value="0">未支付</option>
                            <option value="1">已支付</option>
                        </select>   
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <label class="layui-form-label seller-inline-2">发货状态：</label>
                        <div class="layui-input-inline seller-inline-4">
                        <select name="distribution_status">
                            <option value="-1">全部</option>
                            <option value="0">未发货</option>
                            <option value="1">已发货</option>
                        </select>   
                        </div>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    
                    <div class="layui-inline">
                        <label class="layui-form-label seller-inline-2">订单状态：</label>
                        <div class="layui-input-inline seller-inline-4">
                        <select name="status">
                            <option value="-1">全部</option>
                            <option value="1">新订单</option>
                            <option value="2">确认订单</option>
                            <option value="3">取消订单</option>
                            <option value="4">作废订单</option>
                            <option value="5">完成订单</option>
                            <option value="6">退款</option>
                            <option value="7">部分退款</option>
                        </select>   
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <label class="layui-form-label seller-inline-2">订单名称：</label>
                        <div class="layui-input-inline seller-inline-4">
                            <input type="text" name="title" placeholder="请输入订单号" autocomplete="off" class="layui-input">
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
    <div style="float: left;">
        <div class="layui-form-pos-btn">
            <div class="tab-list-btn active">全部订单</div>
            <div class="tab-list-btn">待支付</div>
            <div class="tab-list-btn">待发货</div>
            <div class="tab-list-btn">待收货</div>
            <div class="tab-list-btn">待评价</div>
            <div class="tab-list-btn">已完成</div>
        </div>
    </div>
    <div style="float: right;">
        <div class="layui-btn-container">
            <button lay-event="refresh" type="button" class="layui-btn layui-btn-sm layui-bg-red"><i class="layui-icon">&#xe9aa;</i> 刷新</button>
            <button lay-event="delete" type="button" class="layui-btn layui-btn-sm layui-bg-orange"><i class="layui-icon">&#xe640;</i> 删除</button>
        </div>
    </div>
</script>

<script type="text/html" id="order-type">
    <p>订单类型：{{ d.order_type_name }}</p>
    <p>支付方式：{{ d.payment_name }}</p>
</script>

<script type="text/html" id="order-info">
    <p>订单号：{{ d.order_no }}</p>
    <p>会员名称：{{ d.username }}</p>
</script>

<script type="text/html" id="order-status">
    <p>支付状态：{{ d.pay_status_name }}</p>
    <p>发货状态：{{ d.distribution_status_name }}</p>
</script>

<script type="text/html" id="order-amount">
    <p>商品金额：￥{{ d.real_amount }}元</p>
    <p>订单金额：￥{{ d.order_amount }}元</p>
</script>

<style type="text/css">
    .layui-table-tool-temp { padding-right: 0; }
    .layui-table-cell{ height:auto !important; }
    .fillet-btn { border-radius: 10px; }
</style>
<script>
window.active = {:input("param.type",0)};
layui.use(['table','form'], function () {
    var table = layui.table;
    var form = layui.form;

    table.render({
        elem: '#list-box'
        , url: '{:createUrl("index",["key[order_status]"=>input("param.type",0)])}'
        , toolbar: '#list-toolbar'
        , defaultToolbar: []
        , title: '数据表'
        , cols: [[
                  {type: 'checkbox'}
                , {field: 'order_type_name', title: '订单类型',templet: "#order-type",width:210,align:'left'}
                , {field: 'order_no', title: '订单信息',templet: "#order-info",align:'left'}
                , {field: 'pay_status_name', title: '订单状态',templet: "#order-status", width:150,align:'center'}
                , {field: 'order_amount', title: '订单金额',templet: "#order-amount",width:210,align:'center'}
                , {field: 'create_time', title: '下单时间',width:180,align:'center'}
                , {fixed: 'right', align: 'center', title: '操作', width: 110,templet: function(res){

                    var str = '<p>';
                    if((res.distribution_status == 1 || res.distribution_status == 2) && res.shipping_type == 1){
                        str += '<a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="logistics">物流</a>';
                    }else{
                        str += '<a class="layui-btn layui-btn-xs layui-bg-gray">物流</a>';
                    }

                    if(res.pay_status == 0){
                        str += '<a class="layui-btn layui-bg-blue layui-btn-xs" lay-event="update">修改</a>';
                    }else{
                        str += '<a class="layui-btn layui-bg-gray layui-btn-xs">修改</a>';
                    }

                    str += '</p>';

                    str += '<p><a class="layui-btn layui-btn-xs" lay-event="edit">详情</a>' +
                           '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a></p>';

                    return str;
                }}
            ]]
        ,done: function (){
            $(".tab-list-btn").removeClass("active").eq(window.active).addClass("active");
        }
        , text: {
            none: '<div><i class="layui-icon">&#xe702;</i>暂无相关数据</div>'
        }
        , page: true
        , id: 'list-table'
        , height: 'full-310'
        // ,limit:30
    });

    $(document).on("click",".tab-list-btn",function (){
        window.active = $(this).index();
        table.reload('list-table', {
            page: {
                curr: 1
            }
            , where: {
                key: {
                    order_status: window.active
                }
            }
        }, 'data');
    });
    
    $("#search-btn").on("click",function (){
        table.reload('list-table', {
          page: {
            curr: 1
          }
          ,where: {
            key: {
              pay_type : $('[name="pay_type"]').val(),
              distribution_status : $('[name="distribution_status"]').val(),
              status : $('[name="status"]').val(),
              title : $('[name="title"]').val()
            }
          }
        }, 'data');
    });
    
    //头工具栏事件
    table.on('toolbar(list-box)', function (obj) {
        switch (obj.event) {
            case 'export':
                window.location.href = '{:createUrl("export")}';
                break;
            case 'url':
                layer.prompt({ title: "请输入核销码" },function(val, index){
                    var jindex = layer.load();
                    $.get('{:createUrl("store_order")}',{ code: val },function (res){
                        layer.close(jindex);
                        if(res.code){
                            layer.close(index);
                            layer.open({
                                type: 2,
                                title: '核销订单',
                                shadeClose: true,
                                shade: 0.3,
                                area: ['90%', '90%'],
                                content: '{:createUrl("store_order")}?id='+res.data
                            });
                        }else{
                            layer.msg(res.msg);
                        }
                    },"json");
                });
                break;
            case 'refresh':
                window.location.reload();
                break;
            case 'delete':
                var checkStatus = table.checkStatus(obj.config.id);
                var data = checkStatus.data;

                var arr = [];
                for(var i in data){
                    arr.push(data[i].id);
                }

                if(arr.length <= 0){
                    layer.msg("请选需要删除的数据!",{ icon : 2 });
                    return ;
                }

                layer.confirm('你确定要删除吗？', function (index) {
                    $.get('{:createUrl("delete")}',{
                        id : arr.join(",")
                    },function(result){
                        layer.close(index);
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
            window.location.href = '{:createUrl("detail")}?id='+data.id;
        }else if(obj.event == 'update'){
            layer.open({
                type: 2,
                title: '金额管理',
                shadeClose: true,
                shade: 0.3,
                area: ['60%', '58%'],
                content: '{:createUrl("update_amount")}?id='+data.id
            });
        }else if(obj.event == 'logistics'){
            layer.open({
                type: 2,
                title: '物流查询',
                shadeClose: true,
                shade: 0.3,
                area: ['60%', '58%'],
                content: '{:createUrl("express")}?id='+data.id
            });
        }
    });
    
});
</script>





