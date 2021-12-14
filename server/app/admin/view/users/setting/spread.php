<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;分销管理</a></li>
            <li><a href="javascript:;">推广设置</a></li>
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
                            <label class="layui-form-label">分享标题</label>
                            <div class="layui-input-block">
                                <input type="text" name="share_title" value="{$data.share_title|default=''}" lay-reqtext="请填写分享标题" lay-verify="required" placeholder="请输入分享标题" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">分享标题，只支持APP</div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">分享链接</label>
                            <div class="layui-input-block">
                                <input type="text" name="share_url" value="{$data.share_url|default=''}" lay-reqtext="请填写分享链接" lay-verify="required" placeholder="请输入分享链接" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">可填写APP下载链接或者跳转至指定页面，只支持APP</div>
                        </div>

                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">分享内容</label>
                            <div class="layui-input-block">
                                <textarea name="share_content" placeholder="请输入分享内容" class="layui-textarea">{$data.share_content|raw|default=""}</textarea>
                            </div>
                            <div class="layui-form-mid layui-word-aux">分享内容的摘要，请自行控制好长度，只支持APP。</div>
                        </div>

                        <div class="layui-form-item">
                            <button id="add-table-btn" type="button" class="layui-btn layui-bg-light-blue">添加</button>
                        </div>

                        <div>
                            <table class="layui-table">
                                <colgroup>
                                    <col width="150">
                                    <col>
                                    <col width="160">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th style="text-align:center">图片</th>
                                    <th style="text-align:center">名称</th>
                                    <th style="text-align:center">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                {if !empty($data.share)}
                                {volist name="data['share']" id="item"}
                                <tr>
                                    <td>
                                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm link-upload-btn">
                                            <i class="layui-icon layui-icon-upload"></i>
                                        </button>
                                        <div class="link-upload-image-box">
                                            <input type="hidden" name="share[photo][]" value="{$item.photo|default=''}">
                                            <img class="layui-upload-img" src="{$item.photo|default='/static/images/default.jpg'}" style="max-width: 60px; max-height: 60px;" alt="">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="share[name][]" value="{$item.name|default=''}" placeholder="请输入名称" autocomplete="off" class="layui-input">
                                    </td>
                                    <td>
                                        <div class="layui-btn-group">
                                            <button type="button" class="layui-btn layui-btn-primary layui-btn-sm layui-button-up"><i class="layui-icon layui-icon-up"></i></button>
                                            <button type="button" class="layui-btn layui-btn-primary layui-btn-sm layui-button-down"><i class="layui-icon layui-icon-down"></i></button>
                                            <button type="button" class="layui-btn layui-btn-primary layui-btn-sm layui-button-remove"><i class="layui-icon layui-icon-delete"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                {/volist}
                                {/if}
                                </tbody>
                            </table>
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

<div id="template-box" style="display: none;">
    <table>
        <tr>
            <td>
                <button type="button" class="layui-btn layui-btn-primary layui-btn-sm link-upload-btn">
                    <i class="layui-icon layui-icon-upload"></i>
                </button>
                <div class="link-upload-image-box">
                    <input type="hidden" name="share[photo][]">
                    <img class="layui-upload-img" src="/static/images/default.jpg" style="max-width: 60px; max-height: 60px;" alt="">
                </div>
            </td>
            <td>
                <input type="text" name="share[name][]" placeholder="请输入名称" autocomplete="off" class="layui-input">
            </td>
            <td>
                <div class="layui-btn-group">
                    <button type="button" class="layui-btn layui-btn-primary layui-btn-sm layui-button-up"><i class="layui-icon layui-icon-up"></i></button>
                    <button type="button" class="layui-btn layui-btn-primary layui-btn-sm layui-button-down"><i class="layui-icon layui-icon-down"></i></button>
                    <button type="button" class="layui-btn layui-btn-primary layui-btn-sm layui-button-remove"><i class="layui-icon layui-icon-delete"></i></button>
                </div>
            </td>
        </tr>
    </table>
</div>

<script type="text/javascript">
    function photo(array){
        var pt = $(".link-upload-btn.active").parent();
        $(".link-upload-image-box input",pt).val(array[0]);
        $(".link-upload-image-box img",pt).attr("src",array[0]);
        layui.layer.closeAll();
    }
    $(function () {
        layui.use(["form", "element",'layer','upload'], function () {
            var form = layui.form;
            var layer = layui.layer;
            var upload = layui.upload;

            $(document).on("click",".link-upload-btn",function (){
                $(".link-upload-btn").removeClass("active");
                $(this).addClass("active");
                layer.open({
                    type: 2,
                    title: '图库列表',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1100px', '600px'],
                    content: '{:createUrl("common.material/index",["type"=>"image","callback"=>"photo","module"=>"users","method"=>"spread"])}'
                });
            });

            $("#add-table-btn").on("click",function (){
                var tr = $("#template-box tr:first").clone();
                $(".layui-table tbody").append(tr);
                form.render('select');
            });

            if($(".layui-table tbody tr").length == 0){
                $("#add-table-btn").trigger("click");
            }

            $(document).on("click",".layui-button-remove",function (){
                if($(".layui-table tbody tr").length > 1){
                    $(this).parent().parent().parent().remove();
                }
                return false;
            });

            $(document).on("click", ".layui-button-down", function() {
                var current_tr = $(this).parent().parent().parent();
                current_tr.insertAfter(current_tr.next());
            });

            $(document).on("click", ".layui-button-up", function() {
                var current_tr = $(this).parent().parent().parent();
                if (current_tr.prev().html() != null) {
                    current_tr.insertBefore(current_tr.prev());
                }
            });

            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("spread")}', data.field, function (result) {
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