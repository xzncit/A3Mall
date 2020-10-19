<div class="row content-nav inline-page-box">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;微信管理</a></li>
            <li><a href="javascript:;">公众号</a></li>
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
                        {include file="wechat/common/mini_menu"}
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
                                        <label class="layui-form-label">Appid</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="appid" value="{$data.appid|default=''}" lay-reqtext="请填写小程序Appid" lay-verify="required" placeholder="请输入小程序Appid" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label">Appsecret</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="appsecret" value="{$data.appsecret|default=''}" lay-reqtext="请填写小程序Appsecret" lay-verify="required" placeholder="请输入小程序Appsecret" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label">MCH_ID</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="mch_id" value="{$data.mch_id|default=''}" placeholder="请输入商户号" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label">MCH_KEY</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="mch_key" value="{$data.mch_key|default=''}" placeholder="请输入商户KEY" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label">证书密钥</label>
                                        <div class="layui-input-block">
                                            <input type="text" id="key-input-text" name="key_url" value="{$data.key_url|default=''}" placeholder="请上传证书" readonly="readonly" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label">支付证书</label>
                                        <div class="layui-input-block">
                                            <input type="text" id="cert-input-text" name="cert_url" value="{$data.cert_url|default=''}" placeholder="请上传证书" readonly="readonly" autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label">上传证书</label>
                                        <div class="layui-input-block">
                                            <button type="button" class="layui-btn layui-bg-primary" id="key-upload-btn"><i class="layui-icon"></i>上传证书密钥</button>
                                            <button type="button" class="layui-btn layui-bg-primary" id="cert-upload-btn"><i class="layui-icon"></i>上传支付证书</button>
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


        </div>

    </div>

</section>

<script type="text/javascript">
    $(function () {
        layui.use(["form", "element",'layer','upload'], function () {
            var form = layui.form;
            var layer = layui.layer;
            var upload = layui.upload;

            upload.render({
                elem: '#key-upload-btn'
                ,url: '{:url("common.uploadfiy/file")}'
                ,multiple: false
                ,exts: 'pem'
                ,data: {
                    module: function (){
                        return "mini";
                    },
                    method: function (){
                        return "";
                    }
                }
                ,done: function(res){
                    if(!res.code){
                        $("#key-input-text").val(res.data.src);
                    }else{
                        layer.msg(res.msg,{ icon : 2 });
                    }
                }
            });

            upload.render({
                elem: '#cert-upload-btn'
                ,url: '{:url("common.uploadfiy/file")}'
                ,multiple: false
                ,exts: 'pem'
                ,data: {
                    module: function (){
                        return "mini";
                    },
                    method: function (){
                        return "";
                    }
                }
                ,done: function(res){
                    if(!res.code){
                        $("#cert-input-text").val(res.data.src);
                    }else{
                        layer.msg(res.msg,{ icon : 2 });
                    }
                }
            });

            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("pay")}', data.field, function (result) {
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



