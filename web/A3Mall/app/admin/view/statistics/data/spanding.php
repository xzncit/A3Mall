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
    <div class="layui-fluid" id="search-box">
        <div class="layui-card">
            <div class="layui-card-body">
                <form method="post" class="layui-form layui-form-pane" action="{:url('spanding')}">

                    <div class="layui-form-item">

                        <div class="layui-inline">
                            <label class="layui-form-label seller-inline-2">开始时间：</label>
                            <div class="layui-input-inline seller-inline-4">
                                <input id="start-time-box" type="text" name="start_time" value="{$start_time|default=''}" placeholder="请输入开始时间" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label seller-inline-2">结束时间：</label>
                            <div class="layui-input-inline seller-inline-4">
                                <input id="end-time-box" type="text" name="end_time" value="{$end_time|default=''}" placeholder="请输入结束时间" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <button lay-filter="layui-submit-filter" lay-submit="" type="button" id="search-btn" class="layui-btn layui-btn-sm layui-bg-light-blue"><i class="layui-icon layui-icon-search"></i> 搜索</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="echarts-box">
        <div id="main" style="width: 100%;height:500px; padding: 25px; padding-top: 50px;"></div>
    </div>
</section>
<script>
    layui.use(['form','laydate'], function () {
        var form = layui.form;

        var laydate = layui.laydate;
        laydate.render({ elem: '#start-time-box',format: 'yyyy-MM-dd HH:mm:ss' });
        laydate.render({ elem: '#end-time-box',format: 'yyyy-MM-dd HH:mm:ss' });

        $("#search-btn").on("click",function (){
            $(".layui-form").submit();
        });

    });
</script>

<script type="text/javascript">
    var myChart = echarts.init(document.getElementById('main'),'walden');
    var option = option = {
        backgroundColor:"#fff",
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            show: false,
            data:['销售统计']
        },
        toolbox: {
            show : false,
            feature : {
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                data : [<?php echo $keys; ?>],
                axisLabel: {
                    show: true,
                    textStyle: {
                        color: '#333'
                    }
                }
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel: {
                    show: true,
                    textStyle: {
                        color: '#ffffff'
                    }
                }
            }
        ],
        series : [
            {
                name:'销售额',
                type:'bar',
                data:[<?php echo $values; ?>]
            }
        ]
    };
    myChart.setOption(option);
</script>
