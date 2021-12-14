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
        <form action="" class="layui-form layui-form-pane">
            <div class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">

                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <div class="layui-form-item">
                            <table class="layui-table">
                                <colgroup>
                                    <col width="10%">
                                    <col width="25%">
                                    <col width="10%">
                                    <col width="25%">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th colspan="4">订单信息：</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th style="text-align:right;">订单编号：</th>
                                    <td>{$data.order_no}</td>
                                    <th style="text-align:right;">当前状态：</th>
                                    <td>{$data.order_status_text}</td>
                                </tr>
                                <tr>
                                    <th style="text-align:right;">支付状态：</th>
                                    <td>{$data.order_payment_status_text}</td>
                                    <th style="text-align:right;">配送状态：</th>
                                    <td>{$data.distribution_status_name}</td>
                                </tr>
                                <tr>
                                    <th style="text-align:right;">订单类型：</th>
                                    <td>{$data.type_name}</td>
                                    <th style="text-align:right;">配送方式：</th>
                                    <td>{if !empty($data.distribution_name)}{$data.distribution_name}{else}未分配{/if}</td>
                                </tr>
                                <tr>
                                    <th style="text-align:right;">支付方式：</th>
                                    <td colspan="3">{$data.payment_name}</td>
                                </tr>
                                <tr>
                                    <th style="text-align:right;">可得积分：</th>
                                    <td>{$data.point}</td>
                                    <th style="text-align:right;">支付手续费：</th>
                                    <td>{$data.pay_fee}</td>
                                </tr>
                                <tr>
                                    <th style="text-align:right;">配送费用：</th>
                                    <td>{$data.real_freight}</td>
                                    <th style="text-align:right;">保价费用：</th>
                                    <td>{$data.insured}</td>
                                </tr>
                                <tr>
                                    <th style="text-align:right;">税金费用：</th>
                                    <td>{$data.taxes}</td>
                                    <th style="text-align:right;">优惠总额：</th>
                                    <td>{$data.promotions}</td>
                                </tr>
                                <tr>
                                    <th style="text-align:right;">增加或减少金额：</th>
                                    <td>{$data.discount}</td>
                                    <th style="text-align:right;">商品总额：</th>
                                    <td>{$data.real_amount}</td>
                                </tr>
                                <tr>
                                    <th style="text-align:right;">订单总额：</th>
                                    <td colspan="3"><span style="color:#ff6600">{$data.order_amount}</span></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block" style="text-align: center;margin-left: 0">
                        <input name="id" type="hidden" value="{$data.id|default='0'}">
                        <button class="layui-btn layui-bg-light-blue" lay-filter="layui-submit-filter" lay-submit="">确认核销</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script type="text/javascript">
    $(function () {
        layui.use(["form", "element",'layer'], function () {
            var form = layui.form;
            var layer = layui.layer;


            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("store_order")}', data.field, function (result) {
                    layer.close(index);
                    if(result.code){
                        layer.alert(result.msg, function(index){
                            // window.parent.layer.closeAll();
                            window.parent.location.reload();
                        });
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