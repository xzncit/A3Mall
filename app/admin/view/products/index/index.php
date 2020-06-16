<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;商品管理</a></li>
            <li><a href="javascript:;">商品列表</a></li>
        </ul>
    </div>
</div>

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
        <button lay-event="url" type="button" class="layui-btn layui-btn-sm layui-bg-light-blue"><i class="layui-icon">&#xe61f;</i> 添加</button>
        <button lay-event="refresh" type="button" class="layui-btn layui-btn-sm layui-bg-red"><i class="layui-icon">&#xe9aa;</i> 刷新</button>
        <button lay-event="go" type="button" class="layui-btn layui-btn-sm layui-bg-red"><i class="layui-icon">&#xe9aa;</i> 采集</button>
    </div>
</script>

<script type="text/html" id="list-bar">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
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
                , {field: 'id', title: 'ID', width: 80, unresize: true, sort: true,align:"center"}
                , {field:"cat_name",title:"分类名称",width:120,align:"center"}
                , {field:'photo', title:'封面', width:60,templet: function(res){
                    return '<img src="'+ res.photo +'" style="max-width:30px; max-height:30px;">';
                  }}
                , {field: 'title', title: '名称'}
                , {field: 'sell_price', title: '商品价格',width:150,align:'center'}
                , {field: 'sort', title: '排序', edit:true, width: 100, align:"center"}
                , {field: 'create_time', title: '创建时间',width:180,align:'center'}
                , {fixed: 'right', align: 'center', title: '操作', toolbar: '#list-bar', width: 130}
            ]]
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
    table.on('toolbar(list-box)', function (obj) {
        switch (obj.event) {
            case 'url':
                window.location.href = "{:createUrl('editor')}";
                break;
            case 'refresh':
                window.location.reload();
                break;
            case 'go':
                layer.open({
                    type: 2,
                    title: '采集页',
                    shadeClose: true,
                    shade: 0.4,
                    area: ['90%', '90%'],
                    content: "{:createUrl('products.gather/index')}"
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
            window.location.href = data.url;
        }
    });
    
    //监听单元格编辑
    table.on('edit(list-box)', function(obj){
        $.get('{:createUrl("field")}',{
            name : obj.field,
            value : obj.value,
            id : obj.data.id
        },function (result){
            if(!result.code){
                layer.msg(result.msg,{ icon : 2 });
            }
        },"json");
    });
    
});
</script>





