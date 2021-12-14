<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;营销管理</a></li>
            <li><a href="javascript:;">订单活动</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-editor-box">
        <form action="" class="layui-form layui-form-pane">
            <div class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
                <ul class="layui-tab-title">
                    <li class="layui-this">基本信息</li>
                    <li>活动说明</li>
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
                                <label class="layui-form-label">选择类型</label>
                                <div class="layui-input-block">
                                    <select lay-verify="required" name="type" lay-filter="attr-select">
                                        {volist name="type" id="vo"}
                                        <option value="{$key}" {if isset($data.type) && $data.type == $key}selected{/if}>{$vo|default=""}</option>
                                        {/volist}
                                    </select>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">订单金额</label>
                                <div class="layui-input-block">
                                    <input type="text" name="amount" value="{$data.amount|default=''}" lay-reqtext="请填写订单金额" lay-verify="required" placeholder="请输入订单金额" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item expression-box"></div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">开始时间</label>
                                <div class="layui-input-block">
                                    <input type="text" id="start-time-box" name="start_time" value="{$data.start_time|default=''}" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">结束时间</label>
                                <div class="layui-input-block">
                                    <input type="text" id="end-time-box" name="end_time" value="{$data.end_time|default=''}" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
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

                    <div class="layui-tab-item">

                        <div class="layui-form-item">
                            <script id="container" style="width:100%;height: 500px;" name="content" type="text/plain">{$data.content|default=""}</script>
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
    $(function () {
        var ue = UE.getEditor('container');

        layui.use(["form", "element",'layer','laydate'], function () {
            var form = layui.form;
            var layer = layui.layer;
            var laydate = layui.laydate;

            laydate.render({ elem: '#start-time-box',format: 'yyyy-MM-dd HH:mm:ss' });
            laydate.render({ elem: '#end-time-box',format: 'yyyy-MM-dd HH:mm:ss' });

            function getExpression(type,flag){
                switch(type.toString()){
                    case '0':
                        var str = '<label class="layui-form-label">折扣</label>\n' +
                            '<div class="layui-input-block">\n' +
                            '<input type="text" name="expression" value="{$data.expression|default=\'\'}" placeholder="折扣值(1-100 如果打9折，请输入90)" autocomplete="off" class="layui-input">\n' +
                            '</div>';
                        $(".expression-box").html(str);
                        $(".expression-box").show();
                        break;
                    case '1':
                        var str = '<label class="layui-form-label">优惠金额</label>\n' +
                            '<div class="layui-input-block">\n' +
                            '<input type="text" name="expression" value="{$data.expression|default=\'\'}" placeholder="立减金额（元）" autocomplete="off" class="layui-input">\n' +
                            '</div>';
                        $(".expression-box").html(str);
                        $(".expression-box").show();
                        break;
                    case '2':
                        var str = '<label class="layui-form-label">积分数量</label>\n' +
                            '<div class="layui-input-block">\n' +
                            '<input type="text" name="expression" value="{$data.expression|default=\'\'}" placeholder="商品送积分" autocomplete="off" class="layui-input">\n' +
                            '</div>';
                        $(".expression-box").html(str);
                        $(".expression-box").show();
                        break;
                    case '3':
                        $(".expression-box").hide();
                        break;
                }
                if(flag){
                    $('[name="expression"]').val('');
                }
                layui.form.render();
            }

            $(".expression-box").hide();
            {if isset($data.type) && in_array($data.type,[0,1,2,3])}
            getExpression({$data.type},false);
            {else}
            getExpression('0',false);
            {/if}

            layui.form.on('select(attr-select)',function (data){
                getExpression(data.value.toString(),true);
                return false;
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