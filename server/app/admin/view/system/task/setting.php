<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;系统任务</a></li>
            <li><a href="javascript:;">基本设置</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <blockquote class="layui-elem-quote" style="font-size: 13px;background: #fff;border-left-color: #3c8dbc;">
        <p>定时器: 单位秒，一般为60秒就可以了，更改后需要重新启动 Workerman 才能生效。</p>
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
                            <label class="layui-form-label">定时器</label>
                            <div class="layui-input-inline">
                                <input type="number" name="unit" value="{$data.unit|default='1'}" required lay-verify="required" placeholder="请输入值" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">单位：秒</div>
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
                $.post('{:createUrl("setting")}', data.field, function (result) {
                    layer.close(index);
                    if(result.code){
                        layer.msg(result.msg, {
                            time: 0
                            ,btn: ['继续编辑']
                            ,yes: function(index){
                                window.location.reload();
                            }
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