<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;计划任务</a></li>
            <li><a href="javascript:;">添加任务</a></li>
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
                                <label class="layui-form-label">任务名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="title" value="{$data.title|default=''}" lay-reqtext="请填写名称" lay-verify="required" placeholder="请输入名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">执行指令</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="command" value="{$data.command|default=''}" lay-reqtext="请填写执行指令" lay-verify="required" placeholder="请输入执行指令" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">执行参数</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="exec_data" value="{$data.exec_data|default=''}" placeholder="请输入执行参数" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">执行时段</label>
                                <div class="layui-input-inline">
                                    <input type="number" name="value" value="{$data.value|default='1'}" required lay-verify="required" placeholder="请输入值" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-input-inline">
                                    <select lay-verify="required" name="type" lay-filter="attr-select">
                                        <option value="0" {if isset($data.type) && $data.type == 0}selected{/if}>天</option>
                                        <option value="1" {if isset($data.type) && $data.type == 1}selected{/if}>小时</option>
                                        <option value="2" {if isset($data.type) && $data.type == 2}selected{/if}>分钟</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux">执行一次</div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">执行次数</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="exec_type" title="一次" value="0" {if empty($data.exec_type) || $data.exec_type==0}checked="checked"{/if}>
                                    <input type="radio" name="exec_type" title="多次" value="1" {if isset($data.exec_type) && $data.exec_type==1}checked="checked"{/if}>
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
        layui.use(["form", "element",'layer','upload'], function () {
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