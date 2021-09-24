<script src="{__SYSTEM_PATH__}/js/echarts/echarts.min.js"></script>
<script src="{__SYSTEM_PATH__}/js/echarts/theme/walden.js"></script>
<script src="{__SYSTEM_PATH__}/js/echarts/map/js/china.js"></script>
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
                <form method="post" class="layui-form layui-form-pane" action="{:url('order')}">

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
        <div id="main" style="width: 100%;height:700px; padding: 25px; padding-top: 50px;"></div>
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
    var option = {
        title: {
            text: '订单地区分布图',
            subtext: '<?php echo $str_time; ?>',
            left: 'center'
        },
        tooltip: {
            trigger: 'item'
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data:['订单统计']
        },
        visualMap: {
            min: 0,
            max: <?php echo $total; ?>,
            left: 'left',
            top: 'bottom',
            text: ['高','低'],
            calculable: true
        },
        toolbox: {
            show: true,
            orient: 'vertical',
            left: 'right',
            top: 'center',
            feature: {
                dataView: {readOnly: false},
                restore: {},
                saveAsImage: {}
            }
        },
        series: [
            {
                name: '订单统计',
                type: 'map',
                mapType: 'china',
                roam: false,
                label: {
                    normal: {
                        show: true
                    },
                    emphasis: {
                        show: true
                    }
                },
                data:[
                    <?php if(!empty($data)) : $i=0; $count=count($data); foreach($data as $item) : ?>
                    {name: '<?php echo $item["name"]; ?>',value: <?php echo $item["num"]; ?> }<?php echo $i+1!=$count ? ',' : ''; ?>
                    <?php $i++; endforeach; endif; ?>
                ]
            }
        ]
    };
    myChart.setOption(option);
</script>
