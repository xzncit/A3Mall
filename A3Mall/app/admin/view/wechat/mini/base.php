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
                                        <label class="layui-form-label">充值功能</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="is_rechange" title="启用" value="0" {if empty($data.is_rechange) || $data.is_rechange==0}checked="checked"{/if}>
                                            <input type="radio" name="is_rechange" title="关闭" value="1" {if isset($data.is_rechange) && $data.is_rechange==1}checked="checked"{/if}>
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <label class="layui-form-label">提现功能</label>
                                        <div class="layui-input-block">
                                            <input type="radio" name="is_withdrawal" title="启用" value="0" {if empty($data.is_withdrawal) || $data.is_withdrawal==0}checked="checked"{/if}>
                                            <input type="radio" name="is_withdrawal" title="关闭" value="1" {if isset($data.is_withdrawal) && $data.is_withdrawal==1}checked="checked"{/if}>
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

            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("base")}', data.field, function (result) {
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



