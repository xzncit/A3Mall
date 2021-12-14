<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>充值管理</a></li>
            <li><a href="javascript:;">充值设置</a></li>
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
                            <button id="add-table-btn" type="button" class="layui-btn layui-bg-light-blue">添加</button>
                        </div>

                        <div>
                            <table class="layui-table">
                                <colgroup>
                                    <col>
                                    <col width="160">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th style="text-align:center">金额</th>
                                    <th style="text-align:center">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                {if !empty($data['list'])}
                                {volist name="data['list']" id="item"}
                                <tr>
                                    <td>
                                        <input type="text" name="list[num][]" value="{$item.num}" placeholder="请输入金额" autocomplete="off" class="layui-input">
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
                <input type="text" name="list[num][]" placeholder="请输入金额" autocomplete="off" class="layui-input">
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
    $(function () {
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
                $.post('{:createUrl("setting")}', data.field, function (result) {
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