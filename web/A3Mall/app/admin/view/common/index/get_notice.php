{__NOLAYOUT__}
<table id="tagNoticeTable" lay-filter="tagNoticeTable"></table>
<script>
    var ids = {};
    var table_data=new Array();
    layui.use(['form', 'laydate','table'], function(){
        layui.table.render({
            elem: '#tagNoticeTable',
            height: '420',
            page: 'true',
            limit:'10',
            url: "{:createUrl('platform.archives/index')}",
            id:'tagNoticeTable',
            cols: [[
                {type:'checkbox'}
                , {field: 'id', title: 'ID', width: 80, unresize: true, sort: true,align:"center"}
                , {field:'photo', title:'封面', width:60,templet: function(res){
                        return '<img src="'+ res.photo +'" style="max-width:30px; max-height:30px;">';
                    }}
                , {field: 'cat_name', title: '所属分类',width:130}
                , {field: 'title', title: '名称'}
                , {field: 'create_time', title: '时间', width: 180, align: "center"}
            ]],
            done: function(res, curr, count){
                table_data=res.data;

                for(var i=0;i< res.data.length;i++){
                    if(ids[res.data[i].id]){
                        res.data[i]["LAY_CHECKED"]='true';
                        var index= res.data[i]['LAY_TABLE_INDEX'];
                        $('#tagNoticeTable + div .layui-table-body tr[data-index=' + index + '] input[type="checkbox"]').prop('checked', true);
                        $('#tagNoticeTable + div .layui-table-body tr[data-index=' + index + '] input[type="checkbox"]').next().addClass('layui-form-checked');
                    }
                }
                var checkStatus = layui.table.checkStatus('tagNoticeTable');
                if(checkStatus.isAll){
                    $('#tagNoticeTable + div .layui-table-header th[data-field="0"] input[type="checkbox"]').prop('checked', true);
                    $('#tagNoticeTable + div .layui-table-header th[data-field="0"] input[type="checkbox"]').next().addClass('layui-form-checked');
                }
            }
        });
        layui.table.on('checkbox(tagNoticeTable)', function(obj){
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
