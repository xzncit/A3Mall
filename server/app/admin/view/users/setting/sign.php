<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>会员管理</a></li>
            <li><a href="javascript:;">签到设置</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-editor-box">
        <div class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
            <ul class="layui-tab-title">
                <li class="layui-this">基础设置</li>
            </ul>
            <form action="" class="layui-form layui-form-pane">
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <div class="layui-form-item">
                            <label class="layui-form-label">签到状态</label>
                            <div class="layui-input-block">
                                <select name="status">
                                    <option value="0" {if isset($data['status']) && $data['status'] == 0}selected{/if}>关闭</option>
                                    <option value="1" {if isset($data['status']) && $data['status'] == 1}selected{/if}>开启</option>
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <button id="add-table-btn" type="button" class="layui-btn layui-bg-light-blue">添加</button>
                        </div>

                        <div>
                            <table class="layui-table">
                                <colgroup>
                                    <col>
                                    <col>
                                    <col width="160">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th style="text-align:center">名称</th>
                                    <th style="text-align:center">积分</th>
                                    <th style="text-align:center">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                {if !empty($data['list'])}
                                {volist name="data['list']" id="item"}
                                <tr>
                                    <td>
                                        <input type="text" name="list[name][]" value="{$item.name}" placeholder="请输入名称" autocomplete="off" class="layui-input">
                                    </td>
                                    <td>
                                        <input type="text" name="list[point][]" value="{$item.point}" placeholder="请输入积分" autocomplete="off" class="layui-input">
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
                            <script id="container" style="width:100%;height: 500px;" name="content" type="text/plain">{$content|raw|default=""}</script>
                            <div class="layui-form-mid layui-word-aux">请填写活动规则</div>
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
                <input type="text" name="list[name][]" placeholder="请输入名称" autocomplete="off" class="layui-input">
            </td>
            <td>
                <input type="text" name="list[point][]" placeholder="请输入积分" autocomplete="off" class="layui-input">
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

<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
    $(function () {
        var ue = UE.getEditor('container');
        layui.use(["form", "element",'layer'], function () {
            var form = layui.form;
            var layer = layui.layer;

            $("#add-table-btn").on("click",function (){
                if($(".layui-table tbody tr").length + 1 > 7){
                    return false;
                }
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
                $.post('{:createUrl("sign")}', data.field, function (result) {
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