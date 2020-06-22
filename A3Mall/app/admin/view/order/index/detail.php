<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;订单管理</a></li>
            <li><a href="javascript:;">订单信息</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-editor-box">
        <form action="" class="layui-form layui-form-pane">
            <div class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
                <ul class="layui-tab-title">
                    <li class="layui-this">基本信息</li>
                    <li>订单日志</li>
                </ul>

                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            
                            <div class="layui-form-item">
                                <div>
                                    <a href="javascript:;" data-href="{:createUrl('payment',['id'=>$data.id])}" class="layui-btn order-event-btn">支付</a>
                                    <a href="javascript:;" data-href="{:createUrl('distribution',['id'=>$data.id])}" class="layui-btn layui-btn-disabled order-event-btn layui-btn-normal">发货</a>
                                    <a href="javascript:;" data-href="{:createUrl('refundment',['id'=>$data.id])}" class="layui-btn layui-btn-disabled order-event-btn layui-btn-warm">退款</a>
                                    <a href="javascript:;" data-href="{:createUrl('complete',['id'=>$data.id,'status'=>4])}" class="layui-btn layui-btn-disabled order-event-btn layui-btn-danger">作废</a>
                                    <a href="javascript:;" data-href="{:createUrl('complete',['id'=>$data.id,'status'=>5])}" class="layui-btn layui-btn-disabled order-event-btn layui-btn-greens">完成</a>
                                </div>
                                <table id="goods-table-box" class="layui-table">
                                    <colgroup>
                                        <col>
                                        <col>
                                        <col width="90">
                                        <col width="90">
                                        <col width="90">
                                        <col width="90">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;">商品名称</th>
                                            <th style="text-align:center;">规格</th>
                                            <th style="text-align:center;">销售价格</th>
                                            <th style="text-align:center;">购买数量</th>
                                            <th style="text-align:center;">库存数量</th>
                                            <th style="text-align:center;">金额</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        {if !empty($data.goods)}
                                        {volist name="data['goods']" id="item"}
                                        <tr>
                                            <td>
                                                <input type="hidden" name="order_goods_id[]" value="{$item.id}">
                                                <input type="hidden" data-field="{$item.goods_id}" name="goods_id[]" value="{$item.goods_id}">
                                                <input type="hidden" name="product_id[]" value="{$item.product_id}">
                                                {$item.goods_array.title}
                                            </td>
                                            <td>{if !empty($item.goods_array.spec)}{$item.goods_array.spec}{/if}</td>
                                            <td>{$item.sell_price}</td>
                                            <td>X {$item.goods_nums}</td>
                                            <td>{$item.store_nums}</td>
                                            <td>{$item.order_price}</td>
                                        </tr>
                                        {/volist}
                                        {/if}
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="layui-form-item">
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="8%">
                                        <col width="27%">
                                        <col width="8%">
                                        <col width="27%">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th colspan="4">购买人信息：</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th style="text-align:right;">会员：</th>
                                            <td>{$data.users.username}</td>
                                            <th style="text-align:right;">姓名：</th>
                                            <td>{$data.accept_name}</td>
                                        </tr>
                                        <tr>
                                            <th style="text-align:right;">级别：</th>
                                            <td>{$data.users.level}</td>
                                            <th style="text-align:right;">性别：</th>
                                            <td>{$data.users.sex}</td>
                                        </tr>
                                        <tr>
                                            <th style="text-align:right;">手机：</th>
                                            <td>{$data.mobile}</td>
                                            <th style="text-align:right;">电话：</th>
                                            <td>{$data.phone}</td>
                                        </tr>
                                        <tr>
                                            <th style="text-align:right;">地址：</th>
                                            <td>{$data.area_name} {$data.address}</td>
                                            <th style="text-align:right;">邮编：</th>
                                            <td>{$data.zip}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
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
                                            <th style="text-align:right;">商品重量：</th>
                                            <td>{$data.goods_weight}</td>
                                            <th style="text-align:right;">支付方式：</th>
                                            <td>{$data.payment_name}</td>
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
                                            <th colspan="4">操作信息：</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th style="text-align:right;">下单时间：</th>
                                            <td>{$data.create_time}</td>
                                            <th style="text-align:right;">付款时间：</th>
                                            <td>{$data.pay_time}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">发货时间：</th>
                                            <td>{$data.send_time}</td>
                                            <th style="text-align:right;">收货时间：</th>
                                            <td>{$data.accept_time}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">完成时间：</th>
                                            <td colspan="3">{$data.completion_time}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
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
                                            <th colspan="4">订单留言：</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th style="text-align:right;">会员备注：</th>
                                            <td colspan="3">{$data.message}</td>
                                        </tr>
                                        <tr>
                                            <th style="text-align:right;">管理备注：</th>
                                            <td colspan="3">{$data.note}</td>
                                        </tr>
                                        <tr>
                                            <th style="text-align:right;">发货备注：</th>
                                            <td colspan="3">{$data.remarks}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        
                        <div class="layui-tab-item">
                            <table class="layui-table">
                                <colgroup>
                                    <col width="100">
                                    <col width="150">
                                    <col width="80">
                                    <col>
                                    <col width="200">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>操作者</th>
                                        <th>动作</th>
                                        <th>结果</th>
                                        <th>信息</th>
                                        <th>时间</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    {if !empty($data.order_log)}
                                    {volist name="data['order_log']" id="log"}
                                    <tr>
                                        <td>{$log.username}</td>
                                        <td>{$log.action}</td>
                                        <td>{$log.result}</td>
                                        <td>{$log.note}</td>
                                        <td>{$log.create_time}</td>
                                    </tr>
                                    {/volist}
                                    {/if}
                                </tbody>
                            </table>
                        </div>
                        
                    </div>


            </div>
        </form>
    </div>
</section>

<script type="text/javascript">
    $(function () {
        $(".order-event-btn").addClass("layui-btn-disabled");
        
        // 支付
        {if in_array($data.order_status,[1])}
        $(".order-event-btn").eq(0).removeClass("layui-btn-disabled");
        {/if}
            
        // 发货
        {if in_array($data.order_status,[2])}
        $(".order-event-btn").eq(1).removeClass("layui-btn-disabled");
        {/if}
        
        // 退款 
        {if in_array($data.order_status,[2,3,4])}
        $(".order-event-btn").eq(2).removeClass("layui-btn-disabled");
        {/if}
     
        // 完成
        {if in_array($data.order_status,[2,3,4])}
        $(".order-event-btn").eq(4).removeClass("layui-btn-disabled");
        {/if}
        
        {if $data.status != 4}
        $(".order-event-btn").eq(3).removeClass("layui-btn-disabled");
        {/if}
        
        $(".order-event-btn").on("click",function (){
            var url = $(this).attr("data-href");
            if($(this).is(".layui-btn-disabled")){
                return false;
            }

            window.location.href = url;
            return false;
        });
        
        layui.use(["form", "element",'layer'], function () {
            var form = layui.form;
            var layer = layui.layer;
            
            
            form.on('submit(layui-submit-filter)', function (data) {
                
                return false;
            });
        });
    });
</script>