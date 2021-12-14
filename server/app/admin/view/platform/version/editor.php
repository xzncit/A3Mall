<div class="row content-nav inline-page-box">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;版本管理</a></li>
            <li><a href="javascript:;">编辑版本</a></li>
        </ul>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-md-3 l-col-md-3">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">菜单</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        {include file="platform/version/menu"}
                    </ul>
                </div>

            </div>

        </div>

        <div class="col-md-9 r-col-md-9">
            <section class="content clearfix" style="padding-top: 0;">
                <div class="layui-editor-box">
                    <div style="margin-top: 0;" class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
                        <ul class="layui-tab-title">
                            <li class="layui-this">基本信息</li>
                        </ul>
                        <form action="" class="layui-form layui-form-pane">
                            <div class="layui-tab-content">
                                <div class="layui-tab-item layui-show">

                                    <div class="layui-form-item">
                                        <label class="layui-form-label">标题</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="title" value="{$data.title|default=''}" lay-reqtext="请填写标题" lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label">标识</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="sign" value="{$data.sign|default=''}" placeholder="请输入标识" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label">版本号</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="version" value="{$data.version|default=''}" lay-reqtext="请填写版本号" lay-verify="required" placeholder="请输入版本号" autocomplete="off" class="layui-input">
                                        </div>
                                        <div class="layui-form-mid layui-word-aux">版本号不允许带字母和其他字符,只允许数字和"." 例：1.0，1.1，2.0</div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label">地址/资源</label>
                                        <div class="layui-input-block">
                                            <input type="hidden" name="attachment_id" value="0">
                                            <input type="text" id="input-url-text" name="url" value="{$data.url|default=''}" placeholder="地址/资源" autocomplete="off" class="layui-input">
                                        </div>
                                        <div class="layui-form-mid layui-word-aux">可以填写地址或者点击下方上传资源，资源类型：apk,wgt,png,jpg,gif,jpeg</div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label">上传</label>
                                        <div class="layui-input-block">
                                            <button type="button" class="layui-btn layui-bg-primary" id="upload-btn"><i class="layui-icon"></i>上传</button>
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <script id="container" style="width:100%;height: 500px;" name="content" type="text/plain">{$data.content|raw|default=""}</script>
                                    </div>

                                </div>

                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <input type="hidden" name="type" value="{$data.type|default=$type}">
                                    <input type="hidden" name="id" value="{$data.id|default=''}">
                                    <button class="layui-btn layui-bg-light-blue" lay-filter="layui-submit-filter" lay-submit="">立即提交</button>
                                    <button class="layui-btn layui-btn-primary" type="reset">重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>


        </div>

    </div>

</section>

<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
    $(function () {
        var ue = UE.getEditor('container');
        layui.use(["form", "element",'layer','upload'], function () {
            var form = layui.form;
            var layer = layui.layer;
            var upload = layui.upload;

            upload.render({
                elem: '#upload-btn'
                ,url: '{:url("file")}'
                ,multiple: false
                ,exts: 'apk|wgt|jpg|png|gif|jpeg'
                ,data: {
                    module: function (){
                        return "version";
                    },
                    method: function (){
                        return "";
                    }
                }
                ,done: function(res){
                    if(!res.code){
                        $('[name="attachment_id"]').val(res.data.id);
                        $("#input-url-text").val(res.data.src);
                    }else{
                        layer.msg(res.msg,{ icon : 2 });
                    }
                }
            });

            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("editor")}', data.field, function (result) {
                    layer.close(index);
                    if(result.code){
                        layer.msg(result.msg, {
                            time: 0
                            ,btn: ['继续发布', '返回列表']
                            ,yes: function(index){
                                window.location.href = '{:createUrl("editor")}?type={$data.type|default=$type}';
                            }
                            ,btn2: function (index, layero){
                                window.location.href = '{:createUrl("index")}?type={$data.type|default=$type}';
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



