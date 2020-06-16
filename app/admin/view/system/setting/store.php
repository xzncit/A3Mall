<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;站点管理</a></li>
            <li><a href="javascript:;">门店设置</a></li>
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
                                <label class="layui-form-label">门店名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" value="{$data.name|default=''}" lay-reqtext="请填写门店名称" lay-verify="required" placeholder="请输入门店名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label">门店简介</label>
                                <div class="layui-input-block">
                                    <input type="text" name="intro" value="{$data.intro|default=''}" lay-reqtext="请填写门店简介" lay-verify="required" placeholder="请输入门店简介" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">联系电话</label>
                                <div class="layui-input-block">
                                    <input type="text" name="phone" value="{$data.phone|default=''}" lay-reqtext="请填写门店手机号" lay-verify="required" placeholder="请输入门店手机号" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">门店地址</label>
                                <div class="layui-input-block">
                                    <input type="text" name="address" value="{$data.address|default=''}" lay-reqtext="请填写门店地址" lay-verify="required" placeholder="请输入门店地址" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                        
                            <div class="layui-form-item">
                                <label class="layui-form-label">营业时间</label>
                                <div class="layui-input-block">
                                    <input type="text" name="date_time" value="{$data.date_time|default=''}" lay-reqtext="请填写营业时间" lay-verify="required" placeholder="请输入营业时间" autocomplete="off" class="layui-input">
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
                $.post('{:createUrl("store")}', data.field, function (result) {
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