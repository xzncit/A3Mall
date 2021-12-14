<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;站点管理</a></li>
            <li><a href="javascript:;">备案信息</a></li>
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
                            <label class="layui-form-label">ICP备案号</label>
                            <div class="layui-input-block">
                                <input type="text" name="copyright[icp]" value="{$data.icp|default=''}" lay-reqtext="请填写ICP备案号" placeholder="请输入ICP备案号" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">ICP备案号，如：粤ICP备2021111111号 文本，不允许包含HTML</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">公安备案</label>
                            <div class="layui-input-block">
                                <input type="text" name="copyright[gov_record]" value="{$data.gov_record|default=''}" lay-reqtext="请填写公安备案" placeholder="请输入公安备案" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">直接填写公安备案号，如：33522102000110号</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">公安链接</label>
                            <div class="layui-input-block">
                                <input type="text" name="copyright[gov_link]" value="{$data.gov_link|default=''}" placeholder="请输入公安链接" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">直接填写备案链接地址</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">监督管理局</label>
                            <div class="layui-input-block">
                                <input type="text" name="copyright[supervision_url]" value="{$data.supervision_url|default=''}" placeholder="请输入监督管理局" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">直接将文字内容复制进去，可以包含a链接</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">PC商城</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="copyright[is_pc]" {if isset($data.is_pc) && $data.is_pc==1}checked{/if} value="1" lay-skin="switch">
                            </div>
                            <div class="layui-form-mid layui-word-aux">是否在PC商城显示备案信息</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">H5商城</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="copyright[is_h5]" {if isset($data.is_h5) && $data.is_h5==1}checked{/if} value="1" lay-skin="switch">
                            </div>
                            <div class="layui-form-mid layui-word-aux">是否在H5/公众号商城显示备案信息</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">后台登录页</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="copyright[is_admin]" {if isset($data.is_admin) && $data.is_admin==1}checked{/if} value="1" lay-skin="switch">
                            </div>
                            <div class="layui-form-mid layui-word-aux">是否在后台登录页显示备案信息</div>
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
            var upload = layui.upload;

            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("copyright")}', data.field, function (result) {
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