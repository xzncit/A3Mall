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
                        <form class="layui-form layui-form-pane" action="">

                            <div class="layui-form-item">

                                <div class="layui-inline">
                                    <label class="layui-form-label seller-inline-2">用户昵称：</label>
                                    <div class="layui-input-inline seller-inline-4">
                                        <input type="text" name="title" placeholder="请输入用户昵称" autocomplete="off" class="layui-input">
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
                    <button lay-event="refresh" type="button" class="layui-btn layui-btn-sm layui-bg-red"><i class="layui-icon">&#xe9aa;</i> 刷新</button>
                    <button lay-event="sync-fans" type="button" class="layui-btn layui-btn-sm layui-bg-light-blue"><i class="layui-icon">&#xe61f;</i> 同步粉丝</button>
                    <button lay-event="sync-tags" type="button" class="layui-btn layui-btn-sm layui-bg-light-blue"><i class="layui-icon">&#xe61f;</i> 同步标签</button>
                    <button lay-event="sync-black" type="button" class="layui-btn layui-btn-sm layui-bg-light-blue"><i class="layui-icon">&#xe61f;</i> 同步黑名单</button>
                    <button lay-event="add-black" type="button" class="layui-btn layui-btn-sm layui-bg-orange"><i class="layui-icon">&#xe624;</i> 加入黑名单</button>
                    <button lay-event="remove-black" type="button" class="layui-btn layui-btn-sm layui-bg-green"><i class="layui-icon">&#xe67e;</i> 移出黑名单</button>
                </div>
            </script>

            <script type="text/html" id="list-bar">
                <a class="layui-btn layui-btn-xs" lay-event="edit">{{ d.is_black ? '己拉黑' : '拉黑' }}</a>
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
            , url: '{:createUrl("wechat.fans/index")}'
            , toolbar: '#list-toolbar'
            , defaultToolbar: []
            , title: '数据表'
            , cols: [[
                {type: 'checkbox'}
                , {field: 'id', title: 'ID', width: 80, unresize: true, sort: true,align:"center"}
                , {field:'photo', title:'头像', width:60,templet: function(res){
                        return '<img src="'+ res.photo +'" style="max-width:30px; max-height:30px;">';
                  }}
                , {field: 'nickname', title: '微信昵称',align:"center"}
                , {field: 'tags', title: 'tag',width:130,align:"center"}
                , {field: 'area', title: '区域',width:130,align:"center"}
                , {field: 'sex', title: '性别', width: 100, align:"center",templet(res){
                    var str = '';
                    if(res.sex == 1) {
                        str = '男';
                    }else if(res.sex == 2){
                        str = '女';
                    }else{
                        str = '未知';
                    }
                    return str;
                  }}
                , {field: 'status', title: '状态', width: 80,align:"center", templet(res){
                    var str = '';
                    str += '<span class="layui-badge">'+(res.subscribe ? "己关注" : "未关注")+'</span>';
                    str += '<br>';
                    str += '<span class="layui-badge layui-bg-green layui-is-black">'+(res.is_black ? "己拉黑" : "未拉黑")+'</span>';
                    return str;
                  }}
                , {field: 'create_time', title: '关注时间', width: 180, align: "center"}
                , {align: 'center', title: '操作', toolbar: '#list-bar', width: 130}
            ]]
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
            var checkStatus = table.checkStatus(obj.config.id);
            switch (obj.event) {
                case 'sync-fans':
                    layer.confirm('您确定要同步粉丝数据？', {
                        btn: ['确定','取消']
                    }, function(i){
                        layer.close(i);
                        var index = layer.load(1, {
                            shade: [0.2,'#000']
                        });
                        $.get("{:createUrl('wechat.fans/sync_fans')}",function (result){
                            if(result.code){
                                window.location.reload();
                            }else{
                                layer.close(index);
                                layer.msg(result.msg,{ icon : 2 });
                            }
                        },"json");
                    }, function(){});
                    break;
                case 'sync-black':
                    layer.confirm('您确定要同步黑名单数据？', {
                        btn: ['确定','取消']
                    }, function(i){
                        layer.close(i);
                        var index = layer.load(1, {
                            shade: [0.2,'#000']
                        });
                        $.get("{:createUrl('wechat.fans/sync_black')}",function (result){
                            if(result.code){
                                window.location.reload();
                            }else{
                                layer.close(index);
                                layer.msg(result.msg,{ icon : 2 });
                            }
                        },"json");
                    }, function(){});
                    break;
                case 'sync-tags':
                    layer.confirm('您确定要同步粉丝标签数据？', {
                        btn: ['确定','取消']
                    }, function(i){
                        layer.close(i);
                        var index = layer.load(1, {
                            shade: [0.2,'#000']
                        });
                        $.get("{:createUrl('wechat.fans/sync_tags')}",function (result){
                            if(result.code){
                                window.location.reload();
                            }else{
                                layer.close(index);
                                layer.msg(result.msg,{ icon : 2 });
                            }
                        },"json");
                    }, function(){});
                    break;
                case 'refresh':
                    window.location.reload();
                    break;
                case 'add-black':
                    var data = checkStatus.data;
                    var arr = [];
                    for(var i in data){
                        arr.push(data[i].openid);
                    }
                    if(arr.length == 0){
                        layer.msg("您还没有选择用户",{ icon : 2 });
                        return false;
                    }
                    $.post("{:createUrl('wechat.fans/add_black')}",{ openid : arr.join(",") },function (result){
                        if(result.code){
                            window.location.reload();
                        }else{
                            layer.msg(result.msg,{ icon : 2 });
                        }
                    },"json");
                    break;
                case 'remove-black':
                    var data = checkStatus.data;
                    var arr = [];
                    for(var i in data){
                        arr.push(data[i].openid);
                    }
                    if(arr.length == 0){
                        layer.msg("您还没有选择用户",{ icon : 2 });
                        return false;
                    }
                    $.post("{:createUrl('wechat.fans/remove_black')}",{ openid : arr.join(",") },function (result){
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
                    $.get('{:createUrl("wechat.fans/delete")}',{
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
                if(data.is_black){
                    $.post("{:createUrl('wechat.fans/remove_black')}",{ openid : data.openid },function (result){
                        if(result.code){
                            $('[lay-event="edit"]',$(obj.tr)).html("拉黑");
                            $(".layui-is-black",$(obj.tr)).html("未拉黑");
                            obj.update({
                                is_black : 0
                            });
                        }else{
                            layer.msg(result.msg,{ icon : 2 });
                        }
                    },"json");
                }else{
                    $.post("{:createUrl('wechat.fans/add_black')}",{ openid : data.openid },function (result){
                        if(result.code){
                            $('[lay-event="edit"]',$(obj.tr)).html("移出黑名单");
                            $(".layui-is-black",$(obj.tr)).html("己拉黑");
                            obj.update({
                                is_black : 1
                            });
                        }else{
                            layer.msg(result.msg,{ icon : 2 });
                        }
                    },"json");

                }
            }
        });

        // 监听锁定操作
        form.on('checkbox(status-filter)', function(obj){
            var that = this;
            $.get('{:createUrl("wechat.fans/field")}',{
                name : that.name,
                value : (obj.elem.checked ? 0 : 1),
                id : that.value
            },function (result){
                if(!result.code){
                    layer.msg(result.msg,{ icon : 2 });
                }
            },"json");
        });

        // 监听单元格编辑
        table.on('edit(list-box)', function(obj){
            $.get('{:createUrl("wechat.fans/field")}',{
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



