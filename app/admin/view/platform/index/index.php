<!-- Content Header (Page header) -->
<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;控制面板</a></li>
            <li><a href="javascript:;">站点信息</a></li>
        </ul>
    </div>
</div>

<!-- Main content -->
<section class="content clearfix">
    <div class="row">
        <div class="col-lg-3 col-xs-6">

            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>6</h3>
                    <p>订单总数量</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cart-plus" aria-hidden="true"></i>
                </div>
                <a href="{:createUrl('order.index/index')}" class="small-box-footer">查看 <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">

            <div class="small-box bg-green">
                <div class="inner">
                    <h3>60</h3>
                    <p>商品总数量</p>
                </div>
                <div class="icon">
                    <i class="fa fa-area-chart" aria-hidden="true"></i>
                </div>
                <a href="{:createUrl('products.index/index')}" class="small-box-footer">查看 <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">

            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>4</h3>
                    <p>会员总数量</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-o" aria-hidden="true"></i>
                </div>
                <a href="{:createUrl('users.index/index')}" class="small-box-footer">查看 <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">

            <div class="small-box bg-red">
                <div class="inner">
                    <h3>5</h3>
                    <p>评价总数量</p>
                </div>
                <div class="icon">
                    <i class="fa fa-commenting-o" aria-hidden="true"></i>
                </div>
                <a href="{:createUrl('users.comment/index')}" class="small-box-footer">查看 <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-8">

            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">最新订单</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <th style="text-align: center">订单号</th>
                                    <th width="150" style="text-align: center">订单类型</th>
                                    <th width="120" style="text-align: center">支付状态</th>
                                    <th width="150" style="text-align: center">订单金额</th>
                                    <th width="150" style="text-align: center">下单时间</th>
                                    <th width="100" style="text-align: center">查看</th>
                                </tr>
                            </thead>
                            <tbody>
                                {if !empty($order)}
                                {volist name="order" id="vo"}
                                <tr>
                                    <td style="text-align: center"><a href="{$vo.url}">{$vo.order_no}</a></td>
                                    <td style="text-align: center">{$vo.order_type_name}</td>
                                    <td style="text-align: center">{$vo.pay_status_name}</td>
                                    <td style="text-align: center">{$vo.order_amount}</td>
                                    <td style="text-align: center">{$vo.create_time}</td>
                                    <td style="text-align: center"><a href="{$vo.url}">查看</a></td>
                                </tr>
                                {/volist}
                                {/if}
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">销量最高商品</h3>
                </div>

                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        {if !empty($goods)}
                        {volist name="goods" id="vo"}
                        <li class="item">
                            <div class="product-img">
                                <img src="{$vo.photo}" alt="">
                            </div>
                            <div class="product-info">
                                <a href="{$vo.url}" class="product-title">{$vo.title}</a>
                                <span class="product-description">销售价格：{$vo.sell_price}</span>
                            </div>
                        </li>
                        {/volist}
                        {/if}
                    </ul>
                </div>
                <div class="box-footer text-center">
                    <a href="{:createUrl('products.index/index')}" class="uppercase">查看全部商品</a>
                </div>
            </div>
        </div>
    </div>

</section>
