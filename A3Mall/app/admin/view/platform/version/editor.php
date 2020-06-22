<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;版本管理</a></li>
            <li><a href="javascript:;">编辑版本</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-editor-box">
        <form action="" class="layui-form layui-form-pane">
            <div class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
                <ul class="layui-tab-title">
                    <li class="layui-this">基本信息</li>
                </ul>

                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                            <div class="layui-form-item">
                                <label class="layui-form-label">名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" value="{$data.name|default=''}" lay-reqtext="请填写名称" lay-verify="required" placeholder="请输入名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">版本号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="api" value="{$data.api|default=''}" lay-reqtext="请填写版本号" lay-verify="required" placeholder="请填写版本号" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">接口版本</label>
                                <div class="layui-input-block">
                                    <input type="text" name="api_url" value="{$data.api_url|default=''}" lay-reqtext="请填写接口版本" lay-verify="required" placeholder="请填写接口版本" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">安卓地址</label>
                                <div class="layui-input-block">
                                    <input type="text" name="android_url" value="{$data.android_url|default=''}" lay-reqtext="请填写安卓地址" lay-verify="required" placeholder="请填写安卓地址" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">苹果地址</label>
                                <div class="layui-input-block">
                                    <input type="text" name="ios_url" value="{$data.ios_url|default=''}" lay-reqtext="请填写苹果地址" lay-verify="required" placeholder="请填写苹果地址" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <script id="container" style="width:100%;height: 500px;" name="content" type="text/plain">{$data.content|raw|default=""}</script>
                            </div>

                    </div>

                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input name="id" type="hidden" value="{$data.id|default='0'}">
                        <button class="layui-btn layui-bg-light-blue" lay-filter="layui-submit-filter" lay-submit="">立即提交</button>
                        <button class="layui-btn layui-btn-primary" type="reset">重置</button>
                    </div>
                </div>
                
            </div>
        </form>
    </div>
</section>

<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
var ue = UE.getEditor('container');
layui.use(["form", "element",'layer','upload'], function () {
    var form = layui.form;
    var layer = layui.layer;

    form.on('submit(layui-submit-filter)', function (data) {
        var index = layer.load(1, { shade: [0.2,'#fff'] });
        $.post('{:createUrl("editor")}', data.field, function (result) {
            layer.close(index);
            if(result.code){
                layer.msg(result.msg, {
                    time: 0
                    ,btn: ['继续发布', '返回列表']
                    ,yes: function(index){
                        window.location.href = '{:createUrl("editor")}';
                    }
                    ,btn2: function (index, layero){
                        window.location.href = '{:createUrl("index")}';
                    }
                });
            }else{
                layer.msg(result.msg,{ icon :2 });
            }
        }, "json");
        return false;
    });
});
</script>