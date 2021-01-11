<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;数据管理</a></li>
            <li><a href="javascript:;">编辑数据</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
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
                            <label class="layui-form-label">标识</label>
                            <div class="layui-input-block">
                                <input type="text" name="sign" value="{$data.sign|default=''}" lay-reqtext="请填写标识" lay-verify="required" placeholder="请输入标识" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <button id="add-table-btn" type="button" class="layui-btn layui-bg-light-blue">添加</button>
                        </div>

                        <div>
                            <table class="layui-table">
                                <colgroup>
                                    <col width="150">
                                    <col>
                                    <col>
                                    <col width="150">
                                    <col width="160">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th style="text-align:center">图片</th>
                                    <th style="text-align:center">名称</th>
                                    <th style="text-align:center">地址</th>
                                    <th style="text-align:center">类型</th>
                                    <th style="text-align:center">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                {if !empty($marketing)}
                                {volist name="marketing" id="item"}
                                <tr>
                                    <td>
                                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm link-upload-btn">
                                            <i class="layui-icon layui-icon-upload"></i>
                                        </button>
                                        <div class="link-upload-image-box">
                                            <input type="hidden" name="marketing[id][]" value="{$item.id}">
                                            <input type="hidden" name="marketing[attachment_id][]" value="{$item.attachment_id}">
                                            <input type="hidden" name="marketing[photo][]" value="{$item.photo}">
                                            <img class="layui-upload-img" src="{$item.photo|default='/static/images/default.jpg'}" style="max-width: 60px; max-height: 60px;" alt="">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="marketing[name][]" value="{$item.name}" placeholder="请输入名称" autocomplete="off" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text" name="marketing[url][]" value="{$item.url}" placeholder="请输入地址" autocomplete="off" class="layui-input">
                                    </td>
                                    <td>
                                        <select name="marketing[target][]">
                                            <option value="0" {if $item.target == 0}selected{/if}>新窗口</option>
                                            <option value="1" {if $item.target == 1}selected{/if}>本窗口</option>
                                        </select>
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

<div id="template-box" style="display: none;">
    <table>
        <tr>
            <td>
                <button type="button" class="layui-btn layui-btn-primary layui-btn-sm link-upload-btn">
                    <i class="layui-icon layui-icon-upload"></i>
                </button>
                <div class="link-upload-image-box">
                    <input type="hidden" name="marketing[id][]">
                    <input type="hidden" name="marketing[attachment_id][]">
                    <input type="hidden" name="marketing[photo][]">
                    <img class="layui-upload-img" src="/static/images/default.jpg" style="max-width: 60px; max-height: 60px;" alt="">
                </div>
            </td>
            <td>
                <input type="text" name="marketing[name][]" placeholder="请输入名称" autocomplete="off" class="layui-input">
            </td>
            <td>
                <input type="text" name="marketing[url][]" placeholder="请输入地址" autocomplete="off" class="layui-input">
            </td>
            <td>
                <select name="marketing[target][]">
                    <option value="0">新窗口</option>
                    <option value="1">本窗口</option>
                </select>
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

    layui.use(["form", "element",'layer','upload'], function () {
        var form = layui.form;
        var layer = layui.layer;
        var upload = layui.upload;

        var uploadfiy = function (btn){
            upload.render({
                elem: btn
                ,url: '{:createUrl("common.uploadfiy/image")}'
                ,exts: 'jpg|png|gif|bmp|jpeg'
                ,data: {
                    module: function (){
                        return "marketing";
                    }
                }
                ,done: function(res, index, upload){
                    if(!res.code){
                        var item = this.item;
                        var pt = $(item).parent();
                        pt.find(".layui-upload-img").attr("src",res.data.src);
                        $('[name="marketing[attachment_id][]"]',pt).val(res.data.id);
                        $('[name="marketing[photo][]"]',pt).val(res.data.src);
                    }else{
                        layer.msg(res.msg,{ icon : 2 });
                    }
                }
            });
        };

        $('.layui-table .link-upload-btn').each(function (){
            uploadfiy($(this));
        });

        $("#add-table-btn").on("click",function (){
            var tr = $("#template-box tr:first").clone();
            $(".layui-table tbody").append(tr);
            form.render('select');
            uploadfiy($('.layui-table .link-upload-btn:last'));
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
            $.post('{:createUrl("editor")}', data.field, function (result) {
                layer.close(index);
                if(result.code){
                    layer.msg(result.msg, {
                        time: 0
                        ,btn: ['继续发布', '返回列表']
                        ,yes: function(index){
                            window.location.href = '{:createUrl("editor")}';
                        }
                        ,btn2: function (index, layero){
                            window.location.href = '{:createUrl("index")}';
                        }
                    });
                }else{
                    layer.msg(result.msg,{ icon :2 });
                }
            }, "json");
            return false;
        });
    });
</script>