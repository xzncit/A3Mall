{__NOLAYOUT__}

<table id="tagGroupTable" lay-filter="tagGroupTable"></table>

<script>
    var ids = {};
    var table_data=new Array();
    layui.use(['form','table'], function(){

        layui.table.render({
            elem: '#tagGroupTable',
            height: '420',
            page: 'true',
            limit:'10',
            url: '{:createUrl("promotion.second/index")}',
            id:'tagGroupTable',
            cols: [[
                {type: 'checkbox'}
                , {field: 'id', title: 'ID', width: 80, unresize: true, sort: true,align:"center"}
                , {field:'photo', title:'封面', width:60,templet: function(res){
                        return '<img src="'+ res.photo +'" style="max-width:30px; max-height:30px;">';
                    }}
                , {field: 'title', title: '商品名称',align: "center"}
                , {field: 'regiment_price', title: '销售价格', width: 160,align: "center"}
                , {field: 'create_time', title: '创建时间',width: 160,align: "center"}
            ]],
            , text: {
                none: '<div><i class="layui-icon">&#xe702;</i>暂无相关数据</div>'
            }
            done: function(res, curr, count){
                table_data=res.data;

                for(var i=0;i< res.data.length;i++){
                    if(ids[res.data[i].id]){
                        res.data[i]["LAY_CHECKED"]='true';
                        var index= res.data[i]['LAY_TABLE_INDEX'];
                        $('#tagGroupTable + div .layui-table-body tr[data-index=' + index + '] input[type="checkbox"]').prop('checked', true);
                        $('#tagGroupTable + div .layui-table-body tr[data-index=' + index + '] input[type="checkbox"]').next().addClass('layui-form-checked');
                    }
                }

                var checkStatus = layui.table.checkStatus('tagGroupTable');
                if(checkStatus.isAll){
                    $('#tagGroupTable + div .layui-table-header th[data-field="0"] input[type="checkbox"]').prop('checked', true);
                    $('#tagGroupTable + div .layui-table-header th[data-field="0"] input[type="checkbox"]').next().addClass('layui-form-checked');
                }
            }
        });

        layui.table.on('checkbox(tagGroupTable)', function(obj){
            if(obj.checked){
                if(obj.type=='one'){
                    ids[obj.data.id] = obj.data;
                }else{
                    for(var i=0;i<table_data.length;i++){
                        ids[table_data[i].id] = table_data[i];
                    }
                }
            }else{
                if(obj.type=='one'){
                    delete ids[obj.data.id];
                }else{
                    for(var i=0;i<table_data.length;i++){
                        delete ids[table_data[i].id];
                    }
                }
            }

        });

    });
</script>
