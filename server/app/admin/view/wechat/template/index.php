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
                            <p>设置微信小程序模板消息，请<a href="https://mp.weixin.qq.com/" style="color: red" target="_blank">登录</a>微信公众号平台->广告与服务->模板消息</p>
                            <p>将模板消息所在行业选择为：主营行业：IT科技 | 互联网|电子商务，IT科技 | IT软件与服务，所选行业不一致将会导致模板消息不可用；</p>
                            <p>如何使用：</p>
                            <p>1. 根据模板名称 在微信公众平台模版库中搜索模版，添加到我的模版中，要求模板字段必须一样。</p>
                            <p>2. 添加模板后，复制模版ID到商城系统后台编辑对应模版，然后修改模版ID保存即可使用</p>
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
                    <button lay-event="editor" type="button" class="layui-btn layui-btn-sm layui-btn-normal"><i class="layui-icon">&#xe61f;</i> 添加</button>
                    <button lay-event="refresh" type="button" class="layui-btn layui-btn-sm layui-bg-red"><i class="layui-icon">&#xe9aa;</i> 刷新</button>
                </div>
            </script>

            <script type="text/html" id="list-bar">
                <a class="layui-btn layui-btn-xs" lay-event="editor">编辑</a>
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
            , url: '{:createUrl("wechat.template/index")}'
            , toolbar: '#list-toolbar'
            , defaultToolbar: []
            , title: '数据表'
            , cols: [[
                {type: 'checkbox'}
                , {field: 'id', title: 'ID', width: 80, unresize: true, sort: true,align:"center"}
                , {field: 'name', title: '模板名称',align:"center"}
                , {field:'sign', title:'模板标识', align: "center", width:220}
                , {field: 'temp_id', title: '模板ID',align:"center"}
                , {field: 'create_time', title: '创建时间', width: 180, align: "center"}
                , {align: 'center', title: '操作', toolbar: '#list-bar', width: 150}
            ]]
            , text: {
                none: '<div><i class="layui-icon">&#xe702;</i>暂无相关数据</div>'
            }
            , page: true
            , id: 'list-table'
            , height: 'full-255'
            // ,limit:30
        });

        // 头工具栏事件
        table.on('toolbar(list-box)', function (obj) {
            switch (obj.event) {
                case 'refresh':
                    window.location.reload();
                    break;
                case 'editor':
                    layer.open({
                        type: 2,
                        title: '编辑模板',
                        shadeClose: true,
                        shade: 0.8,
                        area: ['80%', '90%'],
                        content: '{:url("wechat.template/editor")}'
                    });
                    break;
            }
        });

        // 监听行工具事件
        table.on('tool(list-box)', function (obj) {
            var data = obj.data;
            if (obj.event === 'del') {
                layer.confirm('你确定要删除吗？', function (index) {
                    $.get('{:createUrl("wechat.template/delete")}',{
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
            }else if (obj.event === 'editor') {
                layer.open({
                    type: 2,
                    title: '编辑模板',
                    shadeClose: true,
                    shade: 0.8,
                    area: ['80%', '90%'],
                    content: '{:url("wechat.template/editor")}?id=' + data.id
                });
            }
        });

    });
</script>
