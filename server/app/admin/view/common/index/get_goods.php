{__NOLAYOUT__}
<div class="layui-fluid" id="search-box">
    <div class="layui-card">
        <div class="layui-card-body">
            <form class="layui-form layui-form-pane" action="">

                <div class="layui-form-item">

                    <div class="layui-inline">
                        <label class="layui-form-label seller-inline-2">商品名称：</label>
                        <div class="layui-input-inline seller-inline-4">
                            <input type="text" name="title" placeholder="请输入商品名称" autocomplete="off" class="layui-input">
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
        <table class="layui-hide" id="goods-list-box" lay-filter="goods-list-box"></table>
    </div>
</section>

<script type="text/html" id="list-toolbar">
    <div class="layui-btn-container">
        <button lay-event="refresh" type="button" class="layui-btn layui-btn-sm layui-bg-red"><i class="layui-icon">&#xe9aa;</i> 刷新</button>
    </div>
</script>

<script type="text/html" id="list-bar">
    <a class="layui-btn layui-btn-xs" lay-event="selectGoods">选择</a>
</script>

<script>
    layui.use(['table','form'], function () {
        var table = layui.table;
        var form = layui.form;

        table.render({
            elem: '#goods-list-box'
            , url: '{:createUrl("products.index/index")}'
            , toolbar: '#list-toolbar'
            , defaultToolbar: []
            , title: '数据表'
            , cols: [[
                {type: 'checkbox'}
                , {field: 'id', title: 'ID', width: 80, unresize: true, sort: true,align:"center"}
                , {field:"cat_name",title:"分类名称",width:120,align:"center"}
                , {field:'photo', title:'封面', width:60,templet: function(res){
                        return '<img src="'+ res.photo +'" style="max-width:30px; max-height:30px;">';
                    }}
                , {field: 'title', title: '名称'}
                , {field: 'sell_price', title: '商品价格',width:150,align:'center'}
                , {field: 'create_time', title: '创建时间',width:180,align:'center'}
                , {fixed: 'right', align: 'center', title: '操作', toolbar: '#list-bar', width: 100}
            ]]
            , text: {
                none: '<div><i class="layui-icon">&#xe702;</i>暂无相关数据</div>'
            }
            , page: true
            , id: 'list-table'
            , height: 'full-310'
            // ,limit:30
        });

        $("#search-btn").on("click",function (){
            //执行重载
            table.reload('list-table', {
                page: {
                    curr: 1
                }
                ,where: {
                    key: {
                        cat_id : $('[name="cat_id"]').val(),
                        title : $('[name="title"]').val(),
                        status : $('[name="status"]').val(),
                        brand_id : $('[name="brand_id"]').val()
                    }
                }
            }, 'data');
        });

        //头工具栏事件
        table.on('toolbar(goods-list-box)', function (obj) {
            switch (obj.event) {
                case 'refresh':
                    window.location.reload();
                    break;
            }
        });

    });
</script>





