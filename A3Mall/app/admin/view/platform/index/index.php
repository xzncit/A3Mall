<script src="{__SYSTEM_PATH__}/js/echarts/echarts.min.js"></script>
<script src="{__SYSTEM_PATH__}/js/echarts/theme/walden.js"></script>
<!-- Main content -->
<section class="content clearfix">
    <div class="row">
        <div style="padding: 10px 15px 10px 15px">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-xs6 layui-col-md3">
                    <div class="layui-card">
                        <div class="a3mall-card-body">
                            <div class="img-box">
                                <i class="fa fa-cart-plus"></i>
                            </div>
                            <div class="cart-r">
                                <div class="stat-text incomes-num">{$order_total}</div>
                                <div class="stat-heading">订单总数</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-col-xs6 layui-col-md3">
                    <div class="layui-card ">
                        <div class="a3mall-card-body">
                            <div class="img-box">
                                <i class="fa fa-area-chart" aria-hidden="true"></i>
                            </div>
                            <div class="cart-r">
                                <div class="stat-text goods-num">{$goods_total}</div>
                                <div class="stat-heading">商品总数</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-col-xs6 layui-col-md3">
                    <div class="layui-card">
                        <div class="a3mall-card-body">
                            <div class="img-box">
                                <i class="fa fa-user-o"></i>
                            </div>
                            <div class="cart-r">
                                <div class="stat-text blogs-num">{$users_total}</div>
                                <div class="stat-heading">会员总数</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-col-xs6 layui-col-md3">
                    <div class="layui-card">
                        <div class="a3mall-card-body">
                            <div class="img-box">
                                <i class="fa fa-commenting-o"></i>
                            </div>
                            <div class="cart-r">
                                <div class="stat-text fans-num">{$users_comment_total}</div>
                                <div class="stat-heading">评价总数</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix" style="padding: 10px 0px 10px 0px">
            <div class="clearfix">

                <div class="col-md-8">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <div class="a3mall-card-title">订单统计</div>
                        </div>
                        <div class="a3mall-card-body map-body">
                            <div id="echarts" style="width: 100%;height:300px;"></div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        var myChart = echarts.init(document.getElementById('echarts'),'walden');
                        myChart.setOption({
                            title: {
                                text: ''
                            },
                            tooltip: {
                                trigger: 'axis'
                            },
                            legend: {
                                data: ['金额', '数量']
                            },
                            grid: {
                                left: '3%',
                                right: '4%',
                                bottom: '3%',
                                containLabel: true
                            },
                            toolbox: {
                                feature: {
                                    saveAsImage: {}
                                }
                            },
                            xAxis: {
                                type: 'category',
                                boundaryGap: false,
                                data: [<?php echo $days; ?>]
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [
                                {
                                    name: '金额',
                                    type: 'line',
                                    stack: '总量',
                                    data: [<?php echo $order_amount; ?>]
                                },
                                {
                                    name: '数量',
                                    type: 'line',
                                    stack: '总量',
                                    data: [<?php echo $order_count; ?>]
                                }
                            ]
                        });
                    </script>
                </div>

                <div class="col-md-4">
                    <div class="layui-card">
                        <div class="a3mall-card-body map-body">
                            <div id="echarts-2" style="width: 100%;height:343px;"></div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        var chart = echarts.init(document.getElementById('echarts-2'),'walden');
                        chart.setOption({
                            title: {
                                text: '订单金额分布',
                                subtext: '订单统计',
                                left: 'center'
                            },
                            tooltip: {
                                trigger: 'item',
                                formatter: '{a} <br/>{b} : {c} ({d}%)'
                            },
                            legend: {
                                orient: 'vertical',
                                left: 'left',
                                data: ['购物订单总额', '平均购物总额']
                            },
                            series: [
                                {
                                    name: '统计',
                                    type: 'pie',
                                    radius: '55%',
                                    center: ['50%', '60%'],
                                    data: [
                                        {value: {$e}, name: '购物订单总额'},
                                        {value: {$f}, name: '平均购物总额'}
                                    ],
                                    emphasis: {
                                        itemStyle: {
                                            shadowBlur: 10,
                                            shadowOffsetX: 0,
                                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                                        }
                                    }
                                }
                            ]
                        });
                    </script>
                </div>

            </div>
        </div>

        <div class="clearfix" style="padding: 10px 15px 10px 15px;">
            <div class="layui-row" style="background: #fff;padding: 0 20px;">
                <div class="layui-col-md12">
                    <div style="text-align: center; font-size: 18px;padding: 15px;">
                        会员购买排行
                    </div>
                </div>

                <div class="layui-col-md12">
                    <table class="layui-table">
                        <colgroup>
                            <col width="150">
                            <col width="200">
                            <col width="200">
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th>排行位置</th>
                            <th>会员名称</th>
                            <th>订单总数</th>
                            <th>订单总额</th>
                        </tr>
                        </thead>
                        <tbody>
                        {volist name="g" id="vo"}
                        <tr>
                            <td>{$vo.p}</td>
                            <td>{$vo.username}</td>
                            <td>{$vo.count} 个订单</td>
                            <td>{$vo.total}</td>
                        </tr>
                        {/volist}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
