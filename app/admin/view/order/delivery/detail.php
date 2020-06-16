<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;订单管理</a></li>
            <li><a href="javascript:;">发货详情</a></li>
        </ul>
    </div>
</div>

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
                                <table id="goods-table-box" class="layui-table">
                                    <colgroup>
                                        <col>
                                        <col>
                                        <col width="120">
                                        <col width="90">
                                        <col width="120">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;">商品名称</th>
                                            <th style="text-align:center;">规格</th>
                                            <th style="text-align:center;">销售价格</th>
                                            <th style="text-align:center;">数量</th>
                                            <th style="text-align:center;">金额</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        {if !empty($data["goods"])}
                                        {volist name="data['goods']" id="item"}
                                        <tr>
                                            <td>{$item.goods_array.title}</td>
                                            <td>{if !empty($item.goods_array.spec)}{$item.goods_array.spec}{/if}</td>
                                            <td>{$item.sell_price}</td>
                                            <td style="text-align: center">{$item.goods_nums}</td>
                                            <td style="text-align: center">{$item.order_price}</td>
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
                                            <th colspan="4">操作信息：</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th style="text-align:right;">订单号：</th>
                                            <td>{$data.order_no}</td>
                                            <th style="text-align:right;">订单时间：</th>
                                            <td>{$data.order_create_time}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">配送方式：</th>
                                            <td>{$data.pname}</td>
                                            <th style="text-align:right;">操作员：</th>
                                            <td>{$data.admin_name}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">会员名：</th>
                                            <td>{$data.username}</td>
                                            <th style="text-align:right;">收货人：</th>
                                            <td>{$data.name}</td>
                                        </tr>
                                        
                                        
                                        <tr>
                                            <th style="text-align:right;">收货地区：</th>
                                            <td>{$data.area_name}</td>
                                            <th style="text-align:right;">收货地址：</th>
                                            <td>{$data.address}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">邮编：</th>
                                            <td>{$data.zip}</td>
                                            <th style="text-align:right;">电话：</th>
                                            <td>{$data.phone}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">手机：</th>
                                            <td>{$data.mobile}</td>
                                            <th style="text-align:right;">运费：</th>
                                            <td>{$data.freight}</td>
                                        </tr>
                                        
                                        <tr>
                                            <th style="text-align:right;">物流单号：</th>
                                            <td>{$data.distribution_code}</td>
                                            <th style="text-align:right;">生成时间：</th>
                                            <td>{$data.create_time}</td>
                                        </tr>
                                        
                                        
                                        <tr>
                                            <th style="text-align:right;">备注：</th>
                                            <td colspan="3">{$data.note}</td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>


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
            
            form.on('submit(layui-submit-filter)', function (data) {
                
                return false;
            });
        });
    });
</script>