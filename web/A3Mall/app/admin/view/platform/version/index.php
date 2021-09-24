<div class="row content-nav inline-page-box">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;版本管理</a></li>
            <li><a href="javascript:;">版本列表</a></li>
        </ul>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-md-3 l-col-md-3">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">菜单</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    {include file="platform/version/menu"}
                </div>

            </div>

        </div>

        <div class="col-md-9 r-col-md-9">

            <section class="content clearfix" style="padding-top: 0">
                <div class="layui-list-box">
                    <table class="layui-hide" id="list-box" lay-filter="list-box"></table>
                </div>
            </section>

            <script type="text/html" id="list-toolbar">
                <div class="layui-btn-container">
                    <button lay-event="url" type="button" class="layui-btn layui-btn-sm layui-bg-light-blue"><i class="layui-icon">&#xe61f;</i> 添加</button>
                    <button lay-event="refresh" type="button" class="layui-btn layui-btn-sm layui-bg-red"><i class="layui-icon">&#xe9aa;</i> 刷新</button>
                </div>
            </script>

            <script type="text/html" id="list-bar">
                <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
            </script>

        </div>

    </div>

</section>

<style type="text/css">.layui-table, .layui-table-view{ margin-top: 0; }</style>
<script>
    layui.use(['table','form'], function () {
        var table = layui.table;
        var form = layui.form;

        table.render({
            elem: '#list-box'
            , url: '{:createUrl("platform.version/index")}?type={$type}'
            , toolbar: '#list-toolbar'
            , defaultToolbar: []
            , title: '数据表'
            , cols: [[
                {type: 'checkbox'}
                , {field: 'title', title: '标题',align:"center"}
                , {field: 'sign', title: '标识', width: 150,align:"center"}
                , {field: 'version', title: '版本号', width: 120,align:"center"}
                , {field: 'status', title: '状态', width: 120, align:"center", templet(res){
                    return res.status == 1 ? "禁用" : "正常";
                }}
                , {field: 'create_time', title: '创建时间', width: 180, align: "center"}
                , {align: 'center', title: '操作', width: 120, toolbar: '#list-bar'}
            ]]
            , page: true
            , id: 'list-table'
            , height: 'full-255'
            // ,limit:30
        });


        // 头工具栏事件
        table.on('toolbar(list-box)', function (obj) {
            var checkStatus = table.checkStatus(obj.config.id);
            switch (obj.event) {
                case 'refresh':
                    window.location.reload();
                    break;
                case 'url':
                    window.location.href = '{:url("platform.version/editor")}?type={$type}';
                    break;
            }
        });

        // 监听行工具事件
        table.on('tool(list-box)', function (obj) {
            var data = obj.data;
            if (obj.event === 'del') {
                layer.confirm('你确定要删除吗？', function (index) {
                    $.get('{:createUrl("platform.version/delete")}',{
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
                window.location.href = '{:url("platform.version/editor")}?type='+data.type+'&id=' + data.id;
            }
        });

        // 监听锁定操作
        form.on('checkbox(status-filter)', function(obj){
            var that = this;
            $.get('{:createUrl("field")}',{
                name : that.name,
                value : (obj.elem.checked ? 0 : 1),
                id : that.value
            },function (result){
                if(!result.code){
                    layer.msg(result.msg,{ icon : 2 });
                }
            },"json");
        });

    });
</script>



