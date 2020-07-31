<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;规格管理</a></li>
            <li><a href="javascript:;">编辑规格</a></li>
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
                                    <label class="layui-form-label">类型：</label>
                                    <div class="layui-input-block">
                                        <select name="pid" lay-filter="attr-select">
                                            <option value="0">顶级菜单</option>
                                            {if !empty($ch)}
                                            {volist name="ch" id="value"}
                                            <option value="{$value.id}" {if isset($data.id) && $data.pid==$value.id}selected{/if}>{$value.name}</option>
                                            {/volist}
                                            {/if}
                                        </select>
                                    </div>
                                </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" value="{$data.name|default=''}" lay-reqtext="请填写名称" lay-verify="required" placeholder="请输入名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item layui-form-text">
                                <label class="layui-form-label">描述</label>
                                <div class="layui-input-block">
                                    <textarea name="note" placeholder="请输入描述" class="layui-textarea">{$data.note|default=""}</textarea>
                                </div>
                            </div>

                            <div id="layui-form-table-box">
                                <div class="layui-form-item">
                                    <button id="add-table-btn" type="button" class="layui-btn layui-bg-light-blue">添加</button>
                                </div>

                                <div class="layui-form-item">
                                    <table class="layui-table">
                                        <colgroup>
                                            <col>
                                            <col width="168">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th style="text-align:center;">规格值</th>
                                                <th style="text-align:center;">操作</th>
                                            </tr> 
                                        </thead>
                                        <tbody>
                                                {if !empty($data.attr)}
                                                {volist name="data['attr']" id="item"}
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="attr[id][]" value="{$item.id}" />
                                                        <input type="text" name="attr[name][]" value="{$item.value}" required  lay-verify="required" autocomplete="off" class="layui-input">
                                                    </td>
                                                    <td>
                                                        <div class="layui-btn-group">
                                                            <button class="layui-btn layui-btn-sm attr-up-btn layui-bg-light-blue">上移</button>
                                                            <button class="layui-btn layui-btn-sm attr-up-dowm-btn layui-bg-light-blue">下移</button>
                                                            <button type="button" class="layui-btn layui-btn-sm attr-delete-btn layui-bg-light-blue">删除</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                {/volist}
                                                {/if}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">状态：</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="status" value="0" {if empty($data.status) || $data.status==0}checked{/if} title="启用">
                                    <input type="radio" name="status" value="1" {if isset($data.status) && $data.status==1}checked{/if} title="关闭">
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

<div id='attr-template'>
    <table>
        <tr>
            <td>
                <input type="hidden" name="attr[id][]" value="" />
                <input type="text" name="attr[name][]" value="" required  lay-verify="required" autocomplete="off" class="layui-input">
            </td>
            <td>
                <div class="layui-btn-group">
                    <button class="layui-btn layui-btn-sm attr-up-btn layui-bg-light-blue">上移</button>
                    <button class="layui-btn layui-btn-sm attr-up-dowm-btn layui-bg-light-blue">下移</button>
                    <button type="button" class="layui-btn layui-btn-sm attr-delete-btn layui-bg-light-blue">删除</button>
                </div>
            </td>
        </tr>
    </table>
</div>
<script type="text/javascript">
    $(function () {
        layui.use(["form", "element",'layer'], function () {
            var form = layui.form;
            var layer = layui.layer;
            
            {if isset($data.pid) && $data.pid > 0}
            if ($.trim($(".layui-table tbody").html()) == '') {
                $(".layui-table tbody").append($($("#attr-template table tbody").html()));
                layui.form.render();
            }
            $("#layui-form-table-box").show();
            {/if}
            
            layui.form.on('select(attr-select)',function (data){
                var id = $.trim(data.value);
                if(id > 0){
                    if ($.trim($(".layui-table tbody").html()) == '') {
                        $(".layui-table tbody").append($($("#attr-template table tbody").html()));
                    }
                    
                    layui.form.render();
                    $("#layui-form-table-box").show();
                }else{
                    $(".layui-table tbody tr").remove();
                    $("#layui-form-table-box").hide();
                }

                return false;
            });

            $('#add-table-btn').on("click", function () {
                $(".layui-table tbody").append($("#attr-template table tbody").html());
                layui.form.render();
                return false;
            });

            $(document).on("click", ".attr-up-btn", function () {
                var current_tr = $(this).parent().parent().parent();
                if (current_tr.prev().html() != null) {
                    current_tr.insertBefore(current_tr.prev());
                }
                return false;
            });

            $(document).on("click", ".attr-up-dowm-btn", function () {
                var current_tr = $(this).parent().parent().parent();
                current_tr.insertAfter(current_tr.next());
                return false;
            });

            $(document).on("click", ".attr-delete-btn", function () {
                if($(".layui-table tbody tr").length > 1) {
                    $(this).parent().parent().parent().remove();
                }

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
    });
</script>