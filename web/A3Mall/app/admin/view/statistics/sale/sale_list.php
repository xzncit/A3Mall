<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;销售排行</a></li>
            <li><a href="javascript:;">销售明细</a></li>
        </ul>
    </div>
</div>

<div class="layui-fluid" id="search-box">
    <div class="layui-card">
        <div class="layui-card-body">
            <form class="layui-form layui-form-pane" action="">

                <div class="layui-form-item">

                    <div class="layui-inline">
                        <label class="layui-form-label seller-inline-2">开始时间：</label>
                        <div class="layui-input-inline seller-inline-4">
                            <input id="start-time-box" type="text" name="start_time" value="{$start_time|default=''}" placeholder="请输入开始时间" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-label seller-inline-2">结束时间：</label>
                        <div class="layui-input-inline seller-inline-4">
                            <input id="end-time-box" type="text" name="end_time" value="{$end_time|default=''}" placeholder="请输入结束时间" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <button lay-filter="layui-submit-filter" lay-submit="" type="button" id="search-btn" class="layui-btn layui-btn-sm layui-bg-light-blue"><i class="layui-icon layui-icon-search"></i> 搜索</button>
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
    layui.use(['table','form','laydate'], function () {
        var table = layui.table;
        var form = layui.form;

        var laydate = layui.laydate;
        laydate.render({ elem: '#start-time-box',format: 'yyyy-MM-dd HH:mm:ss' });
        laydate.render({ elem: '#end-time-box',format: 'yyyy-MM-dd HH:mm:ss' });

        table.render({
            elem: '#list-box'
            , url: '{:url("sale_list")}'
            , toolbar: '#list-toolbar'
            , defaultToolbar: []
            , title: '数据表'
            , cols: [[
                {type: 'checkbox'}
                , {field: 'goods_img', title: '封面',width:60,templet: function(res){
                        return '<img src="'+ res.goods_img +'" style="max-width:30px; max-height:30px;">';
                    }}
                , {field: 'goods_name', title: '商品名称'}
                , {field: 'order_no', width: 200, title: '订单号',align: "center"}
                , {field: 'num', width: 100, title: '数量',sort: true,align: "center"}
                , {field: 'price', width: 130, title: '售价',sort: true,align: "center"}
                , {field: 'time', width: 180, title: '售出日期',align: "center"}
            ]]
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
                        start_time : $('[name="start_time"]').val(),
                        end_time : $('[name="end_time"]').val()
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
    });
</script>