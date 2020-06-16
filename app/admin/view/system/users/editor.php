<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp; 用户管理</a></li>
            <li><a href="javascript:;">编辑用户</a></li>
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
                                <label class="layui-form-label">选择权限</label>
                                <div class="layui-input-block">
                                    <select name="role_id" lay-verify="required" lay-reqtext="请选择分组">
                                        <option value="">请选择分组</option>
                                        {if !empty($cat)}
                                        {volist name="cat" id="value"}
                                        <option value="{$value.id}"{if !empty($data.role_id) && $value.id==$data.role_id} selected{/if}>{$value.title}</option>
                                        {/volist}
                                        {/if}
                                    </select>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">用户名</label>
                                <div class="layui-input-block">
                                    <input type="text" name="username" value="{$data.username|default=''}" lay-reqtext="请填写用户名" lay-verify="required" placeholder="请输入用户名" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">密码</label>
                                <div class="layui-input-block">
                                    <input type="password" name="password" value="" {if empty($data.id)}lay-reqtext="请填写密码" lay-verify="required"{/if} placeholder="请输入密码" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-form-mid layui-word-aux">修改会员时，可以不用填写</div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">确认密码</label>
                                <div class="layui-input-block">
                                    <input type="password" name="confirm_password" value="" {if empty($data.id)}lay-reqtext="请填写确认密码" lay-verify="required"{/if} placeholder="请输入确认密码" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-form-mid layui-word-aux">修改会员时，可以不用填写</div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">邮箱</label>
                                <div class="layui-input-block">
                                    <input type="text" name="email" value="{$data.email|default=''}" lay-reqtext="请填写邮箱" lay-verify="required" placeholder="请输入邮箱" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">状态</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="status" title="开启" value="0" {if empty($data.status) || $data.status==0}checked="checked"{/if}>
                                    <input type="radio" name="status" title="关闭" value="1" {if isset($data.status) && $data.status==1}checked="checked"{/if}>
                                </div>
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
            </form>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function () {
        layui.use(["form", "element",'layer','laydate'], function () {
            var form = layui.form;
            var layer = layui.layer;
           
            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:url("editor")}', data.field, function (result) {
                    layer.close(index);
                    if(result.code){
                        layer.msg(result.msg, {
                            time: 0
                            ,btn: ['继续发布', '返回列表']
                            ,yes: function(index){
                                window.location.href = '{:url("editor")}';
                            }
                            ,btn2: function (index, layero){
                                window.location.href = '{:url("index")}';
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