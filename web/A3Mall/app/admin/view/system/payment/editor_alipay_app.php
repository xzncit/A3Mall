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
                            <label class="layui-form-label">加签模式</label>
                            <div class="layui-input-block">
                                <input type="radio" name="config[type]" value="1" lay-filter="type" title="公钥证书" {if isset($data.config.type) && $data.config.type == 1}checked{/if}>
                                <input type="radio" name="config[type]" value="2" lay-filter="type" title="公钥" {if isset($data.config.type) && $data.config.type == 2}checked{/if}>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">APPID</label>
                            <div class="layui-input-block">
                                <input type="text" name="config[app_id]" value="{$data.config.app_id|default=''}" lay-reqtext="请填写应用APPID" lay-verify="required" placeholder="请填写应用APPID" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">私钥字符</label>
                            <div class="layui-input-block">
                                <textarea name="config[merchantPrivateKey]" placeholder="请输入私钥字符" class="layui-textarea">{$data.config.merchantPrivateKey|default=''}</textarea>
                            </div>
                        </div>

                        <div class="layui-form-item layui-form-text layui-form-alipay-public-box">
                            <label class="layui-form-label">公钥字符</label>
                            <div class="layui-input-block">
                                <textarea name="config[alipayPublicKey]" placeholder="请输入公钥字符" class="layui-textarea">{$data.config.alipayPublicKey|default=''}</textarea>
                            </div>
                        </div>

                        <div class="layui-form-item layui-form-alipay-crt-box">
                            <label class="layui-form-label">应用证书</label>
                            <div class="layui-input-block">
                                <input type="text" id="merchantCertPath" name="config[merchantCertPath]" value="{$data.config.merchantCertPath|default=''}" placeholder="请上传应用公钥证书" readonly="readonly" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">请上传应用公钥证书</div>
                        </div>

                        <div class="layui-form-item layui-form-alipay-crt-box">
                            <label class="layui-form-label">支付宝证书</label>
                            <div class="layui-input-block">
                                <input type="text" id="alipayCertPath" name="config[alipayCertPath]" value="{$data.config.alipayCertPath|default=''}" placeholder="请上传支付宝公钥证书" readonly="readonly" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">请上传支付宝公钥证书</div>
                        </div>

                        <div class="layui-form-item layui-form-alipay-crt-box">
                            <label class="layui-form-label">根证书</label>
                            <div class="layui-input-block">
                                <input type="text" id="alipayRootCertPath" name="config[alipayRootCertPath]" value="{$data.config.alipayRootCertPath|default=''}" placeholder="请上传支付宝根证书" readonly="readonly" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">请上传支付宝根证书</div>
                        </div>

                        <div class="layui-form-item layui-form-alipay-crt-box">
                            <label class="layui-form-label">上传证书</label>
                            <div class="layui-input-block">
                                <button type="button" class="layui-btn layui-bg-primary" id="crt-merchant-cert-path"><i class="layui-icon"></i>上传应用公钥证书</button>
                                <button type="button" class="layui-btn layui-bg-primary" id="crt-alipay-cert-path"><i class="layui-icon"></i>上传支付宝公钥证书</button>
                                <button type="button" class="layui-btn layui-bg-primary" id="root-cert-path"><i class="layui-icon"></i>上传支付宝根证书</button>
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

<style>
    .layui-form-alipay-public-box,.layui-form-alipay-crt-box{ display: none; }
</style>
<script type="text/javascript">
    $(function () {
        layui.use(["form", "element",'layer','upload'], function () {
            var form = layui.form;
            var layer = layui.layer;
            var upload = layui.upload;

            {if isset($data.config.type) && $data.config.type == 1}
            $(".layui-form-alipay-crt-box").show();
            $(".layui-form-alipay-public-box").hide();
            {else}
            $(".layui-form-alipay-crt-box").hide();
            $(".layui-form-alipay-public-box").show();
            {/if}

                form.on('radio(type)', function(data){
                    switch (data.value) {
                        case "1":
                            $(".layui-form-alipay-crt-box").show();
                            $(".layui-form-alipay-public-box").hide();
                            break;
                        case "2":
                            $(".layui-form-alipay-crt-box").hide();
                            $(".layui-form-alipay-public-box").show();
                            break;
                    }
                });

                upload.render({
                    elem: '#crt-merchant-cert-path'
                    ,url: '{:url("common.uploadfiy/file")}'
                    ,multiple: false
                    ,exts: 'crt'
                    ,data: {
                        module: function (){
                            return "alipay";
                        },
                        method: function (){
                            return "web";
                        }
                    }
                    ,done: function(res){
                        if(!res.code){
                            $("#merchantCertPath").val(res.data.src);
                        }else{
                            layer.msg(res.msg,{ icon : 2 });
                        }
                    }
                });

                upload.render({
                    elem: '#crt-alipay-cert-path'
                    ,url: '{:url("common.uploadfiy/file")}'
                    ,multiple: false
                    ,exts: 'crt'
                    ,data: {
                        module: function (){
                            return "alipay";
                        },
                        method: function (){
                            return "web";
                        }
                    }
                    ,done: function(res){
                        if(!res.code){
                            $("#alipayCertPath").val(res.data.src);
                        }else{
                            layer.msg(res.msg,{ icon : 2 });
                        }
                    }
                });

                upload.render({
                    elem: '#root-cert-path'
                    ,url: '{:url("common.uploadfiy/file")}'
                    ,multiple: false
                    ,exts: 'crt'
                    ,data: {
                        module: function (){
                            return "alipay";
                        },
                        method: function (){
                            return "web";
                        }
                    }
                    ,done: function(res){
                        if(!res.code){
                            $("#alipayRootCertPath").val(res.data.src);
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