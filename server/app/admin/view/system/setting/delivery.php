<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;站点管理</a></li>
            <li><a href="javascript:;">物流设置</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <blockquote class="layui-elem-quote" style="background: #fff;font-size: 14px;">
        <p>物流查询使用的是阿里云API服务，可以到官网去注册开通。</p>
        <p>地址：<a href="https://market.aliyun.com/products/56928004/cmapi021863.html" target="_blank">去开通</a></p>
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
                            <label class="layui-form-label">AppKey</label>
                            <div class="layui-input-block">
                                <input type="text" name="AppKey" value="{$data.AppKey|default=''}" lay-reqtext="请填写AppKey" lay-verify="required" placeholder="请输入AppKey" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">AppSecret</label>
                            <div class="layui-input-block">
                                <input type="text" name="AppSecret" value="{$data.AppSecret|default=''}" lay-reqtext="请填写AppSecret" lay-verify="required" placeholder="请输入AppSecret" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">AppCode</label>
                            <div class="layui-input-block">
                                <input type="text" name="AppCode" value="{$data.AppCode|default=''}" lay-reqtext="请填写AppCode" lay-verify="required" placeholder="请输入AppCode" autocomplete="off" class="layui-input">
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
                $.post('{:createUrl("delivery")}', data.field, function (result) {
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