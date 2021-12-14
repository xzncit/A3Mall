<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;支付管理</a></li>
            <li><a href="javascript:;">编辑支付</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-editor-box">
        <div style="margin-top: 0;" class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
            <ul class="layui-tab-title">
                <li class="layui-this">基本信息</li>
            </ul>
            <form action="" class="layui-form layui-form-pane">
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form-item">
                            <label class="layui-form-label">APPID</label>
                            <div class="layui-input-block">
                                <input type="text" name="config[app_id]" value="{$data.config.app_id|default=''}" lay-reqtext="请填写微信开放平台审核通过的应用APPID" lay-verify="required" placeholder="请输入微信商户编号" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">微信开放平台审核通过的应用APPID（请登录open.weixin.qq.com查看，注意与公众号的APPID不同）</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">MCH_ID</label>
                            <div class="layui-input-block">
                                <input type="text" name="config[mch_id]" value="{$data.config.mch_id|default=''}" lay-reqtext="请填写微信商户编号" lay-verify="required" placeholder="请输入微信商户编号" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">微信商户编号</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">MCH_KEY</label>
                            <div class="layui-input-block">
                                <input type="text" name="config[mch_key]" value="{$data.config.mch_key|default=''}" lay-reqtext="请填写微信商户密钥" lay-verify="required" placeholder="请输入微信商户密钥" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">微信商户密钥</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">KEY证书</label>
                            <div class="layui-input-block">
                                <input type="text" id="key-input-text" name="config[key_url]" value="{$data.config.key_url|default=''}" placeholder="请上传证书" readonly="readonly" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">CERT证书</label>
                            <div class="layui-input-block">
                                <input type="text" id="cert-input-text" name="config[cert_url]" value="{$data.config.cert_url|default=''}" placeholder="请上传证书" readonly="readonly" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">上传证书</label>
                            <div class="layui-input-block">
                                <button type="button" class="layui-btn layui-bg-primary" id="key-upload-btn"><i class="layui-icon"></i>上传KEY证书</button>
                                <button type="button" class="layui-btn layui-bg-primary" id="cert-upload-btn"><i class="layui-icon"></i>上传CERT证书</button>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input type="hidden" name="id" value="{$data.id}">
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
                        return "wechat";
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
                        return "wechat";
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
                $.post('{:createUrl("editor")}', data.field, function (result) {
                    layer.close(index);
                    if(result.code){
                        layer.msg(result.msg, {
                            time: 3000
                        },function (){
                            window.location.href = "{:createUrl('index')}";
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