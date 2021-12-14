{__NOLAYOUT__}
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>A3Mall | Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/font/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/js/layui/css/layui.css">
    <link rel="stylesheet" href="{__SYSTEM_PATH__}/css/base.css">
    <script src="{__SYSTEM_PATH__}/js/jquery/jquery.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/bootstrap/js/bootstrap.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/layui/layui.js"></script>
    <script src="{__SYSTEM_PATH__}/js/common/common.js"></script>
</head>
<body>

    <section class="content clearfix">
        <div class="layui-editor-box">
            <div class="layui-tab layui-tab-brief layui-tab-bg">
                <ul class="layui-tab-title">
                    <li class="layui-this">选择区域</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <form action="" class="layui-form layui-form-pane">

                            <div class="layui-form-item">
                                <table class="layui-table">
                                    <colgroup>
                                        <col width="100">
                                        <col>
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>区域名称</th>
                                            <th>包含地区</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {if !empty($data)}
                                        {volist name="data" id="item"}
                                        <tr>
                                            <td>
                                                <input lay-filter="filter-group" type="checkbox" name="group[]" value="{$item.id}" title="{$item.name}" lay-skin="primary" />
                                            </td>
                                            <td>
                                            {volist name="item['children']" id="value"}
                                            <input type="checkbox" name="district[]" value="{$value.id}" title="{$value.name}" lay-skin="primary" />
                                            {/volist}
                                            </td>
                                        </tr>
                                        {/volist}
                                        {/if}
                                    </tbody>
                                </table>
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
            </div>
        </div>
    </section>

    <script type="text/javascript">
        $(function () {
            layui.use(["form", "element", 'layer'], function () {
                var form = layui.form;
                var layer = layui.layer;

                layui.form.on('checkbox(filter-group)', function(data){
                    var pt = $(data.elem).parent().parent();
                     if(data.elem.checked){
                         $('[name="district[]"]',pt).prop("checked",true);
                     }else{
                         $('[name="district[]"]',pt).prop("checked",false);
                     }
                     layui.form.render();
               });


                form.on('submit(layui-submit-filter)', function (data) {
                    var aTitle = [];
                    var aId = [];
                    $.each(data.field,function (index,obj){
                        aTitle.push($('[name^="'+index+'"]').attr("title"));
                        aId.push(obj);
                    });

                    var pt = parent.$('[data-field]').parent().parent().parent();
                    $('td',pt).eq(2).html(aTitle.join(","));
                    $('[name="area_group[]"]',pt).val(aId.join(","));
                    parent.layer.closeAll();
                    return false;
                });
            });
        });
    </script>
</body>
</html>
