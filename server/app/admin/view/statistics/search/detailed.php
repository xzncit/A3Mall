<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;搜索统计</a></li>
            <li><a href="javascript:;">搜索明细</a></li>
        </ul>
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
        <button lay-event="clear" type="button" class="layui-btn layui-btn-sm layui-bg-light-blue"><i class="layui-icon">&#xe640;</i> 清空</button>
    </div>
</script>

<script>
    layui.use(['table','form',"layer"], function () {
        var table = layui.table;
        var form = layui.form;

        table.render({
            elem: '#list-box'
            , url: '{:createUrl("detailed")}'
            , toolbar: '#list-toolbar'
            , defaultToolbar: []
            , title: '数据表'
            , cols: [[
                {type: 'checkbox'}
                , {field: 'name', title: '关键字'}
                , {field: 'num', title: '搜索次数', sort: true,align: "center"}
            ]]
            , text: {
                none: '<div><i class="layui-icon">&#xe702;</i>暂无相关数据</div>'
            }
            , page: true
            , id: 'list-table'
            , height: 'full-255'
            // ,limit:30
        });

        //头工具栏事件
        table.on('toolbar(list-box)', function (obj) {
            switch (obj.event) {
                case 'clear':
                    layer.msg('你确定要清空所有统计数据吗？', {
                        time: 0 //不自动关闭
                        ,btn: ['确定', '取消']
                        ,yes: function(index){
                            layer.close(index);
                            layer.load();
                            $.get("{:createUrl('detailed_clear')}",function(result){
                                if(result.code){
                                    window.location.reload();
                                }else{
                                    layer.msg(result.msg,{ icon : 2 });
                                }
                            },"json");
                        }
                    });

                    break;
                case 'refresh':
                    window.location.reload();
                    break;
            }
        });
    });
</script>