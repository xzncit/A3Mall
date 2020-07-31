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
                            <option value="1">己支付</option>
                        </select>   
                        </div>
                    </div>
                    
                    <div class="layui-inline">
                        <label class="layui-form-label seller-inline-2">发货状态：</label>
                        <div class="layui-input-inline seller-inline-4">
                        <select name="distribution_status">
                            <option value="-1">全部</option>
                            <option value="0">未发货</option>
                            <option value="1">己发货</option>
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
        , url: '{:createUrl("index")}'
        , toolbar: '#list-toolbar'
        , defaultToolbar: []
        , title: '数据表'
        , cols: [[
                  {type: 'checkbox'}
                , {field: 'order_type_name', title: '订单类型',width:100,align:'center'}
                , {field: 'order_no', title: '订单号',align:'center'}
                , {field: 'username', title: '会员名称',width:100,align:'center'}
                , {field: 'pay_status_name', title: '支付状态',width:100,align:'center'}
                , {field: 'distribution_status_name', title: '发货状态',width:100,align:'center'}
                , {field: 'payment_name', title: '支付方式',width:100,align:'center'}
                , {field: 'order_amount', title: '订单金额',width:120,align:'center'}
                , {field: 'create_time', title: '下单时间',width:180,align:'center'}
                , {fixed: 'right', align: 'center', title: '操作', width: 160,templet: function(res){
                    var str = '';
                    if(res.pay_status == 0){
                        str += '<a class="layui-btn layui-bg-blue layui-btn-xs" lay-event="update">修改</a>';
                    }else{
                        str += '<a class="layui-btn layui-bg-gray layui-btn-xs">修改</a>';
                    }
                    str += '<a class="layui-btn layui-btn-xs" lay-event="edit">详情</a>\n' +
                           '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>'
                    return str;
                }}
            ]]
        , page: true
        , id: 'list-table'
        , height: 'full-310'
        // ,limit:30
    });
    
    $("#search-btn").on("click",function (){
        table.reload('list-table', {
          page: {
            curr: 1
          }
          ,where: {
            key: {
              pay_type : $('[name="pay_type"]').val(),
              distribution_id : $('[name="distribution_id"]').val(),
              status : $('[name="status"]').val(),
              title : $('[name="title"]').val()
            }
          }
        }, 'data');
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
        }
    });
    
});
</script>





