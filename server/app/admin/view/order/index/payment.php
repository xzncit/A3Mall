<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;订单管理</a></li>
            <li><a href="javascript:;">支付订单</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-editor-box">
        <form action="" class="layui-form layui-form-pane">
            <div class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
                <ul class="layui-tab-title">
                    <li class="layui-this">基本信息</li>
                </ul>

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
                                            <th colspan="4">详细信息：</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th style="text-align:right;">订单号：</th>
                                            <td>{$data.order_no}</td>
                                            <th style="text-align:right;">下单时间：</th>
                                            <td>{$data.create_time}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">收款方式：</th>
                                            <td>{$data.payment_name}</td>
                                            <th style="text-align:right;">付款人：</th>
                                            <td>{$data.username}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">实付运费：</th>
                                            <td>{$data.real_freight}</td>
                                            <th style="text-align:right;">应付运费：</th>
                                            <td>{$data.payable_freight}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">收款金额：</th>
                                            <td>{$data.real_amount}</td>
                                            <th style="text-align:right;">优惠金额：</th>
                                            <td>{$data.promotions}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">订单总金额：</th>
                                            <td colspan="3">{$data.order_amount}</td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="layui-form-item">
                                <textarea name="note" placeholder="请输入备注" class="layui-textarea"></textarea>
                                <div class="layui-form-mid layui-word-aux">请输入订单备注</div>
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
                $.post('{:createUrl("payment")}', data.field, function (result) {
                    layer.close(index);
                    if(result.code){
                        layer.msg(result.msg, {
                            time: 3000,
                            icon : 1
                        },function (){
                            window.location.href = result.data;
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