<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;订单设置</a></li>
            <li><a href="javascript:;">基本设置</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <blockquote class="layui-elem-quote" style="font-size: 13px;background: #fff;border-left-color: #3c8dbc;">
        <p>订单取消时间：未付款订单取消的时间间隔，单位为天</p>
        <p>订单完成时间：已付款的订单完成的时间间隔，单位为天</p>
        <p>订单确认收货时间：发货后的订单自动确认收货时间,单位为天</p>
    </blockquote>
    <div class="layui-editor-box">
        <div class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
            <ul class="layui-tab-title">
                <li class="layui-this">基本信息</li>
            </ul>
            <form action="" class="layui-form layui-form-pane">
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                            <div class="layui-form-item">
                                <label class="layui-form-label">取消时间</label>
                                <div class="layui-input-block">
                                    <input type="text" name="cancel_time" value="{$data.cancel_time|default=''}" lay-reqtext="请填写订单取消时间" lay-verify="required" placeholder="请输入订单取消时间" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">完成时间</label>
                                <div class="layui-input-block">
                                    <input type="text" name="complete_time" value="{$data.complete_time|default=''}" lay-reqtext="请填写订单完成时间" lay-verify="required" placeholder="请输入订单完成时间" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">收货时间</label>
                                <div class="layui-input-block">
                                    <input type="text" name="confirm_time" value="{$data.confirm_time|default=''}" placeholder="请输入订单确认收货时间" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                    </div>

                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn layui-bg-light-blue" lay-filter="layui-submit-filter" lay-submit="">立即提交</button>
                        <button class="layui-btn layui-btn-primary" type="reset">重置</button>
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
            var layer = layui.layer;
            
            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("index")}', data.field, function (result) {
                    layer.close(index);
                    if(result.code){
                        layer.msg(result.msg, {
                            time: 3000
                        },function (){
                            window.location.reload();
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