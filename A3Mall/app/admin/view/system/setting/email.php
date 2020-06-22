<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;站点管理</a></li>
            <li><a href="javascript:;">邮箱设置</a></li>
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
                                <label class="layui-form-label">SMTP地址</label>
                                <div class="layui-input-block">
                                    <input type="text" name="address" value="{$data.address|default=''}" lay-reqtext="请填写SMTP地址" lay-verify="required" placeholder="请输入SMTP地址" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label">SMTP端口</label>
                                <div class="layui-input-block">
                                    <input type="text" name="port" value="{$data.port|default=''}" lay-reqtext="请填写SMTP端口" lay-verify="required" placeholder="请输入SMTP端口" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">邮箱用户名</label>
                                <div class="layui-input-block">
                                    <input type="text" name="username" value="{$data.username|default=''}" lay-reqtext="请填写邮箱用户名" lay-verify="required" placeholder="请输入邮箱用户名" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">SMTP密码</label>
                                <div class="layui-input-block">
                                    <input type="text" name="password" value="{$data.password|default=''}" lay-reqtext="请填写SMTP密码" lay-verify="required" placeholder="请输入SMTP密码" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                        
                            <div class="layui-form-item">
                                <label class="layui-form-label">发送者名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="smtp_name" value="{$data.smtp_name|default=''}" lay-reqtext="请填写收发送者名称" lay-verify="required" placeholder="请输入收发送者名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            
                            <div class="layui-form-item">
                                <label class="layui-form-label">发件人邮箱</label>
                                <div class="layui-input-block">
                                    <input type="text" name="smtp_send" value="{$data.smtp_send|default=''}" lay-reqtext="请填写发件人邮箱" lay-verify="required" placeholder="请输入发件人邮箱" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                        
                            <div class="layui-form-item">
                                <label class="layui-form-label">SSL</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="is_ssl" title="启用" value="0" {if empty($data.is_ssl) || $data.is_ssl==0}checked="checked"{/if}>
                                    <input type="radio" name="is_ssl" title="关闭" value="1" {if isset($data.is_ssl) && $data.is_ssl==1}checked="checked"{/if}>
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
                $.post('{:createUrl("email")}', data.field, function (result) {
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