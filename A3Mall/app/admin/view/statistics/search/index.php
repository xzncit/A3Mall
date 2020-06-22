<script src="{__SYSTEM_PATH__}/js/echarts/echarts.min.js"></script>

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
                <div class="layui-col-md4">
                    <div id="echarts-1" style="width: 100%;height:500px; padding: 25px; padding-top: 50px;"></div>
                </div>
                <script type="text/javascript">
                    var myChart = echarts.init(document.getElementById('echarts-1'));
                    var option = {
                        title: {
                            text: 'APP',
                            subtext: '搜索时使用的设备',
                            left: 'center'
                        },
                        tooltip: {
                            trigger: 'item',
                            formatter: '{a} <br/>{b} : {c} ({d}%)'
                        },
                        legend: {
                            orient: 'vertical',
                            left: 'left',
                            data: ['Android', 'IOS']
                        },
                        series: [
                            {
                                name: '搜索统计',
                                type: 'pie',
                                radius: '55%',
                                center: ['50%', '60%'],
                                data: [
                                    {value: {$app.android}, name: 'Android'},
                                    {value: {$app.ios}, name: 'IOS'}
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

                <div class="layui-col-md4">
                    <div id="echarts-2" style="width: 100%;height:500px; padding: 25px; padding-top: 50px;"></div>
                </div>
                <script type="text/javascript">
                    var myChart = echarts.init(document.getElementById('echarts-2'));
                    var option = {
                        title: {
                            text: '网页搜索',
                            subtext: '搜索时使用的设备',
                            left: 'center'
                        },
                        tooltip: {
                            trigger: 'item',
                            formatter: '{a} <br/>{b} : {c} ({d}%)'
                        },
                        legend: {
                            orient: 'vertical',
                            left: 'left',
                            data: ['PC', 'WAP','公众号']
                        },
                        series: [
                            {
                                name: '统计',
                                type: 'pie',
                                radius: '55%',
                                center: ['50%', '60%'],
                                data: [
                                    {value: {$web.pc}, name: 'PC'},
                                    {value: {$web.wap}, name: 'WAP'},
                                    {value: {$web.wechat}, name: '公众号'}
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

                <div class="layui-col-md4">
                    <div id="echarts-3" style="width: 100%;height:500px; padding: 25px; padding-top: 50px;"></div>
                </div>
                <script type="text/javascript">
                    var myChart = echarts.init(document.getElementById('echarts-3'));
                    var option = {
                        title: {
                            text: '小程序',
                            subtext: '搜索时使用的设备',
                            left: 'center'
                        },
                        tooltip: {
                            trigger: 'item',
                            formatter: '{a} <br/>{b} : {c} ({d}%)'
                        },
                        legend: {
                            orient: 'vertical',
                            left: 'left',
                            data: ['微信', '支付宝','百度','今日头条',"QQ"]
                        },
                        series: [
                            {
                                name: '统计',
                                type: 'pie',
                                radius: '55%',
                                center: ['50%', '60%'],
                                data: [
                                    {value: {$mp.wechat}, name: '微信'},
                                    {value: {$mp.alipay}, name: '支付宝'},
                                    {value: {$mp.baidu}, name: '百度'},
                                    {value: {$mp.zjtd}, name: '今日头条'},
                                    {value: {$mp.qq}, name: 'QQ'}
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

            <div class="layui-row">
                <div class="layui-col-md12">
                    <div id="echarts-4" style="width: 100%;height:500px; padding: 25px; padding-top: 50px;"></div>
                </div>
                <script type="text/javascript">
                    var myChart = echarts.init(document.getElementById('echarts-4'));
                    var option = {
                        title: {
                            text: '搜索访问统计',
                            subtext: '过去六个月搜索数据'
                        },
                        tooltip: {
                            trigger: 'axis'
                        },
                        legend: {
                            data: ['PC', '手机网页', 'APP', '公众号', '小程序']
                        },
                        toolbox: {
                            show: true,
                            feature: {
                                dataView: {show: true, readOnly: false},
                                magicType: {show: true, type: ['line', 'bar']},
                                restore: {show: true},
                                saveAsImage: {show: true}
                            }
                        },
                        calculable: true,
                        xAxis: [
                            {
                                type: 'category',
                                data: [<?php echo("'".implode("月','",$data['cat'])."月'") ?>]
                            }
                        ],
                        yAxis: [
                            {
                                type: 'value'
                            }
                        ],
                        series: [
                            {
                                name: 'PC',
                                type: 'bar',
                                data: [<?php echo $data['list']['pc']; ?>],

                            },
                            {
                                name: '手机网页',
                                type: 'bar',
                                data: [<?php echo $data['list']['wap']; ?>],

                            },
                            {
                                name: 'APP',
                                type: 'bar',
                                data: [<?php echo $data['list']['app']; ?>],

                            },
                            {
                                name: '公众号',
                                type: 'bar',
                                data: [<?php echo $data['list']['wechat']; ?>],

                            },
                            {
                                name: '小程序',
                                type: 'bar',
                                data: [<?php echo $data['list']['mp']; ?>],

                            }
                        ]
                    };
                    myChart.setOption(option);
                </script>
            </div>

        </div>


    </div>
</section>


