<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;订单管理</a></li>
            <li><a href="javascript:;">订单发货</a></li>
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
                                            <th style="text-align:right;">配送方式：</th>
                                            <td>{$data.distribution_name}</td>
                                            <th style="text-align:right;">保价费用：</th>
                                            <td>{$data.insured}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">应付运费：</th>
                                            <td>{$data.payable_freight}</td>
                                            <th style="text-align:right;">实付运费：</th>
                                            <td>{$data.real_freight}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">收货人姓名：</th>
                                            <td><input type="text" name="accept_name" value="{$data.accept_name}" required  lay-verify="required" placeholder="请输入收货人姓名" autocomplete="off" class="layui-input"></td>
                                            <th style="text-align:right;">电话：</th>
                                            <td><input type="text" name="phone" value="{$data.phone}" required  lay-verify="required" placeholder="请输入电话" autocomplete="off" class="layui-input"></td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">手机：</th>
                                            <td><input type="text" name="mobile" value="{$data.mobile}" required  lay-verify="required" placeholder="请输入手机" autocomplete="off" class="layui-input"></td>
                                            <th style="text-align:right;">邮政编码：</th>
                                            <td><input type="text" name="zip" value="{$data.zip}" required  lay-verify="required" placeholder="请输入邮政编码" autocomplete="off" class="layui-input"></td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">地区：</th>
                                            <td colspan="3">
                                                <div class="layui-inline">
                                                    <select name="province" lay-filter="province-filter">
                                                        <option value="">请选择省</option>
                                                        {if !empty($province)}
                                                        {volist name="province" id="value"}
                                                        <option value="{$value.id}"{if !empty($data.province) && $value.id==$data.province} selected{/if}>{$value.name}</option>
                                                        {/volist}
                                                        {/if}
                                                    </select>
                                                </div>
                                                <div class="layui-inline">
                                                    <select name="city" lay-filter="city-filter">
                                                        <option value="">请选择市</option>
                                                        {if !empty($city)}
                                                        {volist name="city" id="value"}
                                                        <option value="{$value.id}"{if !empty($data.city) && $value.id==$data.city} selected{/if}>{$value.name}</option>
                                                        {/volist}
                                                        {/if}
                                                    </select>
                                                </div>
                                                <div class="layui-inline">
                                                    <select name="area" lay-filter="area-filter">
                                                        <option value="">请选择县/区</option>
                                                        {if !empty($area)}
                                                        {volist name="area" id="value"}
                                                        <option value="{$value.id}"{if !empty($data.area) && $value.id==$data.area} selected{/if}>{$value.name}</option>
                                                        {/volist}
                                                        {/if}
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">地址：</th>
                                            <td><input type="text" name="address" value="{$data.address}" required  lay-verify="required" placeholder="请输入地址" autocomplete="off" class="layui-input"></td>
                                            <th style="text-align:right;">配送单号：</th>
                                            <td><input type="text" name="distribution_code" value="" required  lay-verify="required" placeholder="请输入配送单号" autocomplete="off" class="layui-input"></td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">物流公司：</th>
                                            <td colspan="3">
                                                <select name="freight_id" lay-verify="required">
                                                    <option value="">--请选择--</option>
                                                    {if !empty($freight)}
                                                    {volist name="freight" id="value"}
                                                    <option value="{$value.id}">{$value.title}</option>
                                                    {/volist}
                                                    {/if}
                                                </select>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="layui-form-item">
                                <textarea name="remarks" placeholder="请输入备注" class="layui-textarea"></textarea>
                                <div class="layui-form-mid layui-word-aux">发货单备注</div>
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
            
            form.on('select(province-filter)', function(data){
                if(data.value == ""){
                    return true;
                }

                $.get("{:createUrl('common.ajax/get_area')}",{
                    "id" : data.value
                },function (result){
                    if(result.code){
                        $('[name="city"]').html(result.data);
                        $('[name="area"]').html('<option value="">请选择</option>');
                        layui.form.render();
                    }else{
                        layer.msg(result.msg,{ icon : 2 });
                    }
                },"json");
            });
            
            form.on('select(city-filter)', function(data){
                if(data.value == ""){
                    return true;
                }

                $.get("{:createUrl('common.ajax/get_area')}",{
                    "id" : data.value
                },function (result){
                    if(result.code){
                        $('[name="area"]').html(result.data);
                        layui.form.render();
                    }else{
                        layer.msg(result.msg,{ icon : 2 });
                    }
                },"json");
            });
            
            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("distribution")}', data.field, function (result) {
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