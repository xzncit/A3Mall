<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;订单管理</a></li>
            <li><a href="javascript:;">订单退款</a></li>
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
                                <table id="goods-table-box" class="layui-table">
                                    <colgroup>
                                        <col>
                                        <col>
                                        <col width="90">
                                        <col width="90">
                                        <col width="80">
                                        <col width="30">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;">商品名称</th>
                                            <th style="text-align:center;">规格</th>
                                            <th style="text-align:center;">销售价格</th>
                                            <th style="text-align:center;">购买数量</th>
                                            <th style="text-align:center;">状态</th>
                                            <th style="text-align:center;">操作</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        {if !empty($data.goods)}
                                        {volist name="data['goods']" id="item"}
                                        <tr>
                                            <td>
                                                <input type="hidden" data-field="{$item.goods_id}" name="goods_id[]" value="{$item.goods_id}">
                                                <input type="hidden" name="product_id[]" value="{$item.product_id}">
                                                {$item.goods_array.title}
                                            </td>
                                            <td>{if !empty($item.goods_array.spec)}{$item.goods_array.spec}{/if}</td>
                                            <td>{$item.sell_price}</td>
                                            <td>X {$item.goods_nums}</td>
                                            <td>{$item.send_status}</td>
                                            <td>
                                                {if $item.is_send != 2}
                                                <input type="checkbox" name="order_goods_id[]" value="{$item.id}" title="选中">
                                                {/if}
                                            </td>
                                        </tr>
                                        {/volist}
                                        {/if}
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
                                            <th style="text-align:right;">商品应付金额：</th>
                                            <td>{$data.payable_amount}</td>
                                            <th style="text-align:right;">商品实付金额：</th>
                                            <td>{$data.real_amount}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">应付运费：</th>
                                            <td>{$data.payable_freight}</td>
                                            <th style="text-align:right;">实付运费：</th>
                                            <td>{$data.real_freight}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">保价金额：</th>
                                            <td>{$data.insured}</td>
                                            <th style="text-align:right;">支付手续费金额：</th>
                                            <td>{$data.pay_fee}</td>
                                        </tr>
                                        
                                        {if $data.promotions > 0}
                                        <tr>
                                            <th style="text-align:right;">活动优惠金额：</th>
                                            <td colspan="3">{$data.promotions}</td>
                                        </tr>
                                        {/if}
                                        
                                        {if $data.discount > 0}
                                        <tr>
                                            <th style="text-align:right;">订单价格修改：</th>
                                            <td colspan="3">{$data.discount}</td>
                                        </tr>
                                        {/if}
                                        
                                        <tr>
                                            <th style="text-align:right;">已退金额：</th>
                                            <td colspan="3">{$data.amount_refund}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">订单总额：</th>
                                            <td colspan="3">{$data.order_amount}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">金额流向：</th>
                                            <td colspan="3">
                                                <select name="type" lay-verify="required">
                                                    <option value="0">平台余额</option>
                                                </select>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">退款金额：</th>
                                            <td colspan="3">
                                                <input type="text" name="amount" value="0" placeholder="请输入退款金额" autocomplete="off" class="layui-input">
                                                <div class="layui-form-mid layui-word-aux">如果填写“0”金额由系统自动计算</div>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="layui-form-item">
                                <div class="layui-form-mid layui-word-aux">点击退款后，<退款商品的金额>将直接转入用户的网站余额中，如果订单中所有商品均在未发货的情况下全部退款，那么系统将返还运费等</div>
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
                $.post('{:createUrl("refundment")}', data.field, function (result) {
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