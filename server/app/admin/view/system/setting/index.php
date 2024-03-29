<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;站点管理</a></li>
            <li><a href="javascript:;">站点设置</a></li>
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
                            <label class="layui-form-label">网站名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="web_name" value="{$data.web_name|default=''}" lay-reqtext="请填写网站名称" lay-verify="required" placeholder="请输入网站名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">网站副标题</label>
                            <div class="layui-input-block">
                                <input type="text" name="web_title" value="{$data.web_title|default=''}" lay-reqtext="请填写网站副标题" lay-verify="required" placeholder="请输入网站副标题" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">网站关键字</label>
                            <div class="layui-input-block">
                                <input type="text" name="web_keywords" value="{$data.web_keywords|default=''}" placeholder="请输入网站关键字" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">网站描述</label>
                            <div class="layui-input-block">
                                <textarea name="web_description" placeholder="请输入网站描述" class="layui-textarea">{$data.web_description|default=""}</textarea>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">LOGO</label>
                            <div class="layui-input-block">
                                <input type="hidden" name="attachment_id" value="0">
                                <input type="text" id="input-image-text" name="web_logo" value="{$data.web_logo|default=''}" placeholder="站点LOGO" readonly="readonly" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">上传</label>
                            <div class="layui-input-block">
                                <button type="button" class="layui-btn layui-bg-primary" id="upload-btn"><i class="layui-icon"></i>上传图片</button>
                            </div>
                        </div>

                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">尾部版权</label>
                            <div class="layui-input-block">
                                <textarea name="web_copyright" placeholder="请输入尾部版权" class="layui-textarea">{$data.web_copyright|default=""}</textarea>
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
    function photo(array){
        $("#input-image-text").val(array[0]);
        layui.layer.closeAll();
    }
    $(function () {
        layui.use(["form", "element",'layer','upload'], function () {
            var form = layui.form;
            var layer = layui.layer;
            var upload = layui.upload;

            $("#upload-btn").on("click",function (){
                layer.open({
                    type: 2,
                    title: '图库列表',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1100px', '600px'],
                    content: '{:createUrl("common.material/index",["type"=>"image","callback"=>"photo","module"=>"system","method"=>"base"])}'
                });
            });

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