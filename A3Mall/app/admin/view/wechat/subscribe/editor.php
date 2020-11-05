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

<section class="content clearfix">
    <div class="layui-editor-box">
        <div class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
            <ul class="layui-tab-title">
                <li class="layui-this">基本信息</li>
            </ul>
            <form action="" class="layui-form layui-form-pane">
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <div class="layui-form-item">
                            <label class="layui-form-label">名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" value="{$data.name|default=''}" lay-reqtext="请填写名称" lay-verify="required" placeholder="请输入名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">标识</label>
                            <div class="layui-input-block">
                                <input type="text" name="sign" value="{$data.sign|default=''}" lay-reqtext="请填写标识" lay-verify="required" placeholder="请输入标识" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">模板ID</label>
                            <div class="layui-input-block">
                                <input type="text" name="temp_id" value="{$data.temp_id|default=''}" lay-reqtext="请填写模板ID" lay-verify="required" placeholder="请输入模板ID" autocomplete="off" class="layui-input">
                            </div>
                        </div>


                        <div class="layui-form-item">
                            <label class="layui-form-label">状态</label>
                            <div class="layui-input-block">
                                <input type="radio" name="status" title="开启" value="0" {if empty($data.status) || $data.status==0}checked="checked"{/if}>
                                <input type="radio" name="status" title="关闭" value="1" {if isset($data.status) && $data.status==1}checked="checked"{/if}>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <button id="add-table-btn" type="button" class="layui-btn layui-bg-light-blue">添加</button>
                        </div>
                        <blockquote class="layui-elem-quote" style="border-left: 5px solid #3c8dbc; font-size: 14px; background-color: #eee;">
                            提示：当前模板属性内容请和微信小程序后台订阅消息模板内容一一对应，【表字段】是当前发送内容的数据表字段名称。如果您不清楚，请勿修改/删除/移动.
                        </blockquote>
                        <div class="layui-form-item">
                            <table class="layui-table">
                                <colgroup>
                                    <col width="250">
                                    <col width="150">
                                    <col>
                                    <col width="170">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th style="text-align:center;">属性名称</th>
                                    <th style="text-align:center;">表字段</th>
                                    <th style="text-align:center;">属性值</th>
                                    <th style="text-align:center;">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                {if !empty($data.attr)}
                                {volist name="data['attr']['name']" id="item"}
                                <tr>
                                    <td>
                                        <input type="text" name="attr[name][]" value="{$item}" required  lay-verify="required" autocomplete="off" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text" name="attr[field][]" value="{$data['attr']['field'][$key]}" required  lay-verify="required" autocomplete="off" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text" name="attr[value][]" value="{$data['attr']['value'][$key]}" autocomplete="off" class="layui-input">
                                    </td>
                                    <td>
                                        <div class="layui-btn-group">
                                            <button type="button" class="layui-btn layui-btn-sm attr-up-btn layui-bg-light-blue">上移</button>
                                            <button type="button" class="layui-btn layui-btn-sm attr-up-dowm-btn layui-bg-light-blue">下移</button>
                                            <button type="button" class="layui-btn layui-btn-sm attr-delete-btn layui-bg-light-blue">删除</button>
                                        </div>
                                    </td>
                                </tr>
                                {/volist}
                                {/if}
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input name="id" type="hidden" value="{$data.id|default='0'}">
                        <button class="layui-btn layui-bg-light-blue" lay-filter="layui-submit-filter" lay-submit="">立即提交</button>
                        <button class="layui-btn layui-btn-primary" type="reset">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<div id='attr-template'>
    <table>
        <tr>
            <td>
                <input type="text" name="attr[name][]" value="" required  lay-verify="required" autocomplete="off" class="layui-input">
            </td>
            <td>
                <input type="text" name="attr[field][]" value="" required  lay-verify="required" autocomplete="off" class="layui-input">
            </td>
            <td>
                <input type="text" name="attr[value][]" value="" autocomplete="off" class="layui-input">
            </td>
            <td>
                <div class="layui-btn-group">
                    <button type="button" class="layui-btn layui-btn-sm attr-up-btn layui-bg-light-blue">上移</button>
                    <button type="button" class="layui-btn layui-btn-sm attr-up-dowm-btn layui-bg-light-blue">下移</button>
                    <button type="button" class="layui-btn layui-btn-sm attr-delete-btn layui-bg-light-blue">删除</button>
                </div>
            </td>
        </tr>
    </table>
</div>

<script type="text/javascript">
    $(function () {
        layui.use(["form", "element",'layer'], function () {
            var form = layui.form;
            var layer = layui.layer;

            if ($.trim($(".layui-table tbody").html()) == '') {
                $(".layui-table tbody").append($($("#attr-template table tbody").html()));
                layui.form.render();
            }

            $('#add-table-btn').on("click", function () {
                $(".layui-table tbody").append($("#attr-template table tbody").html());
                layui.form.render();
                return false;
            });

            $(document).on("click", ".attr-up-btn", function () {
                var current_tr = $(this).parent().parent().parent();
                if (current_tr.prev().html() != null) {
                    current_tr.insertBefore(current_tr.prev());
                }
                return false;
            });

            $(document).on("click", ".attr-up-dowm-btn", function () {
                var current_tr = $(this).parent().parent().parent();
                current_tr.insertAfter(current_tr.next());
                return false;
            });

            $(document).on("click", ".attr-delete-btn", function () {
                if ($(".layui-table tbody tr").length > 1) {
                    $(this).parent().parent().parent().remove();
                }
                return false;
            });

            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:url("wechat.subscribe/editor")}', data.field, function (result) {
                    layer.close(index);
                    if(result.code){
                        layer.msg(result.msg,{ icon : 1 });
                        setTimeout(function () {
                            // parent.window.layer.closeAll();
                            parent.window.location.reload();
                        },2000);
                    }else{
                        layer.msg(result.msg,{ icon :2 });
                    }
                }, "json");
                return false;
            });

        });
    });
</script>

</body>
</html>