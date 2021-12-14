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
                        <label class="layui-form-label seller-inline-2">商品分类：</label>
                        <div class="layui-input-inline seller-inline-4">
                            <select name="cat_id">
                                <option value="-1">全部</option>
                                {if !empty($cat)}
                                {volist name="cat" id="value"}
                                <option value="{$value.id}">{$value.level}{$value.title}</option>
                                {/volist}
                                {/if}
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <label class="layui-form-label seller-inline-2">商品状态：</label>
                        <div class="layui-input-inline seller-inline-4">
                            <select name="status">
                                <option value="-1">全部</option>
                                <option value="0">上架</option>
                                <option value="1">下架</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item">

                    <div class="layui-inline">
                        <label class="layui-form-label seller-inline-2">商品品牌：</label>
                        <div class="layui-input-inline seller-inline-4">
                            <select name="brand_id">
                                <option value="-1">全部</option>
                                {if !empty($brand)}
                                {volist name="brand" id="value"}
                                <option value="{$value.id}">{$value.name}</option>
                                {/volist}
                                {/if}
                            </select>
                        </div>
                    </div>

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
        <table class="layui-hide" id="list-box" lay-filter="list-box"></table>
    </div>
</section>

<script type="text/html" id="list-toolbar">
    <div class="layui-btn-container">
        <button lay-event="add" type="button" class="layui-btn layui-btn-sm layui-bg-light-blue"><i class="layui-icon">&#xe61f;</i> 选中</button>
    </div>
</script>

<script>
    layui.use(['table','form'], function () {
        var table = layui.table;
        var form = layui.form;

        table.render({
            elem: '#list-box'
            , url: '{:url("common.ajax/get_goods")}'
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
                , {field: 'sort', title: '排序', edit:false, width: 100, align:"center"}
                , {field: 'create_time', title: '创建时间',width:160,align:'center'}
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

        table.on('toolbar(list-box)', function (obj) {
            var checkStatus = table.checkStatus(obj.config.id);
            switch (obj.event) {
                case 'add':
                    var data = checkStatus.data;
                    var arr = [];
                    for(var i in data){
                        arr.push(data[i].id);
                    }

                    if(arr.length <= 0){
                        layer.msg("请选择商品");
                    }else{
                        parent.handleGoods(arr.join(","));
                        parent.layer.closeAll();
                    }
                    break;
            }
        });

        //监听行工具事件
        table.on('tool(list-box)', function (obj) {
            var data = obj.data;
            if (obj.event === 'selected') {
                parent.handleGoods(data.id);
                parent.layer.closeAll();
            }
        });

    });
</script>
</body>
</html>
