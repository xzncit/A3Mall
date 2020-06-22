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
                            <label class="layui-form-label">帐户信息：</label>
                            <div class="layui-input-block">
                                <div class="layui-form-mid layui-word-aux">
                                    &nbsp;&nbsp;
                                    会员名：<?php echo $user["username"]; ?>&nbsp;&nbsp;
                                    拥有金额：￥<?php echo $user["amount"]; ?>&nbsp;&nbsp;
                                    拥有积分：<?php echo $user["point"]; ?>&nbsp;&nbsp;
                                    拥有经验：<?php echo $user["exp"]; ?>&nbsp;&nbsp;
                                </div>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">类型：</label>
                            <div class="layui-input-block">
                                <select name="action" lay-verify="required">
                                    <option value="">请选择</option>
                                    <option value="0">金额</option>
                                    <option value="1">积分</option>
                                    <option value="2">经验</option>
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">操作：</label>
                            <div class="layui-input-block">
                                <select name="operation" lay-verify="required">
                                    <option value="">请选择</option>
                                    <option value="0">充值</option>
                                    <option value="1">提现</option>
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
                        <input name="id" type="hidden" value="{$user.id|default='0'}">
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

            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("finance")}', data.field, function (result) {
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
        });
    });
</script>
</body>
</html>
