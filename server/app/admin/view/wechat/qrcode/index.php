<div class="row content-nav inline-page-box">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;微信管理</a></li>
            <li><a href="javascript:;">公众号</a></li>
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
                    {include file="wechat/common/wechat_menu"}
                </div>

            </div>

        </div>

        <div class="col-md-9 r-col-md-9">
            <div class="layui-fluid">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <blockquote class="layui-elem-quote" style="border-left: 5px solid #3c8dbc; font-size: 14px; background-color: #eee;">
                            如果小程序更换Appid，请清空小程序码，前台会员访问时会自动生成相关小程序码。
                        </blockquote>
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
                    <button lay-event="remove" type="button" class="layui-btn layui-btn-sm layui-btn-normal"><i class="layui-icon">&#xe640;</i> 清空</button>
                </div>
            </script>

            <script type="text/html" id="list-bar">
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
            </script>

        </div>

    </div>

</section>

<style type="text/css">.layui-table-cell{ height:auto !important; }</style>
<script>
    layui.use(['table','form'], function () {
        var table = layui.table;
        var form = layui.form;

        table.render({
            elem: '#list-box'
            , url: '{:createUrl("wechat.qrcode/index")}'
            , toolbar: '#list-toolbar'
            , defaultToolbar: []
            , title: '数据表'
            , cols: [[
                {type: 'checkbox'}
                , {field: 'id', title: 'ID', width: 80, unresize: true, sort: true,align:"center"}
                , {field:'photo', title:'小程序码', align: "center", width:120,templet: function(res){
                        return '<img src="'+ res.image +'" style="max-width:60px; max-height:60px;">';
                    }}
                , {field: 'username', title: '用户名',align:"center"}
                , {field: 'nickname', title: '昵称',align:"center"}
                , {field: 'create_time', title: '生成时间', width: 180, align: "center"}
                , {align: 'center', title: '操作', toolbar: '#list-bar', width: 100}
            ]]
            , text: {
                none: '<div><i class="layui-icon">&#xe702;</i>暂无相关数据</div>'
            }
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
                        title : $('[name="title"]').val()
                    }
                }
            }, 'data');
        });

        // 头工具栏事件
        table.on('toolbar(list-box)', function (obj) {
            switch (obj.event) {
                case 'refresh':
                    window.location.reload();
                    break;
                case 'remove':
                    $.post("{:createUrl('wechat.qrcode/remove')}",function (result){
                        if(result.code){
                            window.location.reload();
                        }else{
                            layer.msg(result.msg,{ icon : 2 });
                        }
                    },"json");
                    break;
            }
        });

        // 监听行工具事件
        table.on('tool(list-box)', function (obj) {
            var data = obj.data;
            if (obj.event === 'del') {
                layer.confirm('你确定要删除吗？', function (index) {
                    $.get('{:createUrl("wechat.qrcode/delete")}',{
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
            }
        });

    });
</script>



