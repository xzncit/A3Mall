<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;分销管理</a></li>
            <li><a href="javascript:;">分销设置</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <blockquote class="layui-elem-quote" style="font-size: 13px;background: #fff;border-left-color: #3c8dbc;">
        <p>人人分销：人人分销默认每个人都可以分销商</p>
        <p>指定分销：需要在后台指定分销推广员</p>
        <p>分销内购：分销商自己购买商品，享受一级佣金，上级享受二级佣金，上上级享受三级佣金</p>
        <p>返现说明：订单交易成功后给上级返佣的比例0 - 100，不允许小数点,例:订单金额为100元，如果填写10，则返现10元（10%）</p>
        <p>提现金额：用户提现时金额最低要求在会员设置->基础设置里更改</p>
    </blockquote>
    <div class="layui-editor-box">
        <div class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
            <ul class="layui-tab-title">
                <li class="layui-this">基本信息</li>
            </ul>
            <form action="" class="layui-form layui-form-pane">
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <div class="layui-form-item">
                            <label class="layui-form-label">分销类型</label>
                            <div class="layui-input-block">
                                <select name="type">
                                    <option value="0" {if isset($data['type']) && $data['type'] == 0}selected{/if}>关闭分销</option>
                                    <option value="1" {if isset($data['type']) && $data['type'] == 1}selected{/if}>人人分销</option>
                                    <option value="2" {if isset($data['type']) && $data['type'] == 2}selected{/if}>指定分销</option>
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">分销内购</label>
                            <div class="layui-input-block">
                                <input type="radio" name="is_goods_rebate" value="1" title="开启" {if isset($data['is_goods_rebate']) && $data['is_goods_rebate'] == 1}checked{/if}>
                                <input type="radio" name="is_goods_rebate" value="0" title="关闭" {if empty($data['is_goods_rebate']) || $data['is_goods_rebate'] == 0}checked{/if}>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">一级返佣</label>
                            <div class="layui-input-block">
                                <input type="text" name="one" value="{$data.one|default='0'}" lay-reqtext="请填写一级返佣比例" lay-verify="required" placeholder="请输入一级返佣比例" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">二级返佣</label>
                            <div class="layui-input-block">
                                <input type="text" name="two" value="{$data.two|default='0'}" lay-reqtext="请填写二级返佣比例" lay-verify="required" placeholder="请输入二级返佣比例" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">三级返佣</label>
                            <div class="layui-input-block">
                                <input type="text" name="three" value="{$data.three|default='0'}" lay-reqtext="请填写三级返佣比例" lay-verify="required" placeholder="请输入三级返佣比例" autocomplete="off" class="layui-input">
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
                $.post('{:createUrl("index")}', data.field, function (result) {
                    layer.close(index);
                    if(result.code){
                        layer.msg(result.msg, {
                            time: 0
                            ,btn: ['继续编辑']
                            ,yes: function(index){
                                window.location.reload();
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