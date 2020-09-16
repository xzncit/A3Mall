<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;优惠券管理</a></li>
            <li><a href="javascript:;">编辑优惠券</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <blockquote class="layui-elem-quote" style="font-size: 13px;background: #fff;border-left-color: #3c8dbc;">
        <p>1.订单金额如果设置为0,则表示不限制优惠券的使用场景</p>
        <p>2.优惠券数量设置为0,则表示不限制张数</p>
    </blockquote>

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
                                <label class="layui-form-label">选择类型</label>
                                <div class="layui-input-block">
                                    <select lay-verify="required" name="type" lay-filter="attr-select">
                                        <option value="0" {if isset($data.type) && $data.type == 0}selected{/if}>用户触发</option>
                                        <option value="1" {if isset($data.type) && $data.type == 1}selected{/if}>积分兑换</option>
                                        <option value="2" {if isset($data.type) && $data.type == 2}selected{/if}>订单赠送</option>
                                        <option value="3" {if isset($data.type) && $data.type == 3}selected{/if}>系统触发</option>
                                    </select>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">金额</label>
                                <div class="layui-input-block">
                                    <input type="text" name="amount" value="{$data.amount|default=''}" lay-reqtext="请填写金额" lay-verify="required" placeholder="请输入红包金额" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">订单金额</label>
                                <div class="layui-input-block">
                                    <input type="text" name="order_amount" value="{$data.order_amount|default=''}" lay-reqtext="请填写订单金额" lay-verify="required" placeholder="请输入订单金额" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item point-box">
                                <label class="layui-form-label">积分数量</label>
                                <div class="layui-input-block">
                                    <input type="text" name="point" value="{$data.point|default=''}" placeholder="请输入积分数量" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">优惠券数量</label>
                                <div class="layui-input-block">
                                    <input type="text" name="giveout" value="{$data.giveout|default=''}" lay-reqtext="请填写优惠券数量" lay-verify="required" placeholder="请输入优惠券数量" autocomplete="off" class="layui-input">
                                </div>
                            </div>

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

<script type="text/javascript">
    $(function () {
        layui.use(["form", "element",'layer','laydate'], function () {
            var form = layui.form;
            var layer = layui.layer;
            var laydate = layui.laydate;

            laydate.render({ elem: '#start-time-box',format: 'yyyy-MM-dd HH:mm:ss' });
            laydate.render({ elem: '#end-time-box',format: 'yyyy-MM-dd HH:mm:ss' });

            $(".point-box").hide();
            {if isset($data.type) && $data.type == 1}
            $(".point-box").show();
            {/if}

            layui.form.on('select(attr-select)',function (data){
                switch(data.value.toString()){
                    case '0':
                        $(".point-box").hide();
                        break;
                    case '1':
                        $(".point-box").show();
                        break;
                    case '2':
                        $(".point-box").hide();
                        break;
                    default:
                }

                //layui.form.render();
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