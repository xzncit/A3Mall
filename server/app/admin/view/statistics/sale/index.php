<script src="{__SYSTEM_PATH__}/js/echarts/echarts.min.js"></script>
<script src="{__SYSTEM_PATH__}/js/echarts/theme/walden.js"></script>
<style type="text/css">
    .echarts-box {
        width: 97.5%;
        height: 100%;
        background: #fff;
        margin: 0 auto;
    }
</style>
<section class="content clearfix">

    <div class="echarts-box">
        <div class="layui-container">
            <div class="layui-row">
                <div class="layui-col-md6">
                    <div id="echarts-1" style="width: 100%;height:500px; padding: 25px; padding-top: 50px;"></div>
                </div>
                <script type="text/javascript">
                    var myChart = echarts.init(document.getElementById('echarts-1'),"walden");
                    var option = {
                        title: {
                            text: '购买率 {$a}%',
                            subtext: '会员未购买率',
                            left: 'center'
                        },
                        tooltip: {
                            trigger: 'item',
                            formatter: '{a} <br/>{b} : {c} ({d}%)'
                        },
                        legend: {
                            orient: 'vertical',
                            left: 'left',
                            data: ['会员总数', '购买过商品会员数', '未购买过商品会员数']
                        },
                        series: [
                            {
                                name: '会员统计',
                                type: 'pie',
                                radius: '55%',
                                center: ['50%', '60%'],
                                data: [
                                    {value: {$b}, name: '会员总数'},
                                    {value: {$d.count}, name: '购买过商品会员数'},
                                    {value: {$c}, name: '未购买过商品会员数'}
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
                    };
                    myChart.setOption(option);
                </script>

                <div class="layui-col-md6">
                    <div id="echarts-2" style="width: 100%;height:500px; padding: 25px; padding-top: 50px;"></div>
                </div>

                <script type="text/javascript">
                    var myChart = echarts.init(document.getElementById('echarts-2'),'walden');
                    var option = {
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
                    };
                    myChart.setOption(option);
                </script>
            </div>

        </div>


        <div class="layui-row">
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
</section>


