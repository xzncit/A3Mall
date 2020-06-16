{__NOLAYOUT__}
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>A3Mall | Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/font/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/layui/css/layui.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/css/base.css">
    <script src="{__SYSTEM_PATH__}/js/jquery/jquery.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/bootstrap/dist/js/bootstrap.min.js"></script>
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
                            <label class="layui-form-label">订单信息：</label>
                            <div class="layui-input-block">
                                <div class="layui-form-mid layui-word-aux">
                                    &nbsp;订单金额：￥<?php echo $order["order_amount"]; ?>
                                    &nbsp;订单号：￥<?php echo $order["order_no"]; ?>
                                </div>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">类型：</label>
                            <div class="layui-input-block">
                                <select name="action" lay-verify="required">
                                    <option value="">请选择</option>
                                    <option value="0">增加金额</option>
                                    <option value="1">减少金额</option>
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">数值：</label>
                            <div class="layui-input-block">
                                <input type="text" name="num" value="" required  lay-verify="required" placeholder="请输入数值" autocomplete="off" class="layui-input">
                            </div>
                        </div>


                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input name="id" type="hidden" value="{$order.id|default='0'}">
                        <button class="layui-btn layui-bg-light-blue" lay-filter="layui-submit-filter" lay-submit="">确定</button>
                        <button class="layui-btn layui-btn-primary" type="reset">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function () {
        layui.use(["form", "element",'layer'], function () {
            var form = layui.form;
            var layer = layui.layer;

            {if empty($order)}
                layer.msg("您要操作的订单不存在",{ time: 3000 },function (){
                    parent.layer.closeAll();
                });
            {/if}

            {if !empty($order) && $order.pay_status == 1}
                layer.msg("您要操作的订单己支付",{ time: 3000 },function (){
                    parent.layer.closeAll();
                });
            {/if}

            {if !empty($order) && $order.pay_status == 0}
            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("update_amount")}', data.field, function (result) {
                    layer.close(index);
                    if (result.code) {
                        layer.msg(result.msg, {icon: 1},function (){
                            parent.window.location.reload();
                        });
                    } else {
                        layer.msg(result.msg, {icon: 2});
                    }
                }, "json");
                return false;
            });
            {/if}
        });
    });
</script>
</body>
</html>
