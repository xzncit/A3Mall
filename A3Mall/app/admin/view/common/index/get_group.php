{__NOLAYOUT__}

<table id="tagGoodsTable" lay-filter="tagGoodsTable"></table>

<script>
    var ids = {};
    var table_data=new Array();
    layui.use(['form','table'], function(){
        layui.table.render({
            elem: '#tagGoodsTable',
            height: '420',
            page: 'true',
            limit:'10',
            url: '{:createUrl("products.goods/index")}',
            id:'tagGoodsTable',
            cols: [[
                {type: 'checkbox'}
                , {field: 'id', title: 'ID', width: 80, unresize: true, sort: true,align:"center"}
                , {field:"cat_name",title:"分类名称",width:120,align:"center"}
                , {field:'photo', title:'封面', width:60,templet: function(res){
                        return '<img src="'+ res.photo +'" style="max-width:30px; max-height:30px;">';
                    }}
                , {field: 'title', title: '名称'}
                , {field: 'sell_price', title: '商品价格',width:150,align:'center'}
                , {field: 'group_price', title: '拼团价格', edit:true, width: 150, align:"center"}
                , {field: 'people', title: '参团人数', width: 100, align:"center"}
                , {field: 'end_time', title: '结束时间',width:180,align:'center'}
            ]],
            done: function(res, curr, count){

                table_data=res.data;

                for(var i=0;i< res.data.length;i++){
                    if(ids[res.data[i].id]){
                        res.data[i]["LAY_CHECKED"]='true';
                        var index= res.data[i]['LAY_TABLE_INDEX'];
                        $('#tagGoodsTable + div .layui-table-body tr[data-index=' + index + '] input[type="checkbox"]').prop('checked', true);
                        $('#tagGoodsTable + div .layui-table-body tr[data-index=' + index + '] input[type="checkbox"]').next().addClass('layui-form-checked');
                    }
                }
                var checkStatus = layui.table.checkStatus('tagGoodsTable');
                if(checkStatus.isAll){
                    $('#tagGoodsTable + div .layui-table-header th[data-field="0"] input[type="checkbox"]').prop('checked', true);
                    $('#tagGoodsTable + div .layui-table-header th[data-field="0"] input[type="checkbox"]').next().addClass('layui-form-checked');
                }
            }
        });
        //监听表格复选框选择
        layui.table.on('checkbox(tagGoodsTable)', function(obj){
            console.log(obj);
            if(obj.checked){
                if(obj.type=='one'){
                    ids[obj.data.goods_id] = obj.data;
                }else{
                    for(var i=0;i<table_data.length;i++){
                        ids[table_data[i].goods_id] = table_data[i];
                    }
                }
            }else{
                if(obj.type=='one'){
                    delete ids[obj.data.goods_id];
                }else{
                    for(var i=0;i<table_data.length;i++){
                        delete ids[table_data[i].goods_id];
                    }
                }
            }
        });

    });
</script>
