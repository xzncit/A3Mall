<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;配送管理</a></li>
            <li><a href="javascript:;">编辑配送</a></li>
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
                                <label class="layui-form-label">配送名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="title" value="{$data.title|default=''}" lay-reqtext="请填写配送名称" lay-verify="required" placeholder="请输入配送名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <table class="layui-table">
                                    <colgroup><col><col><col><col></colgroup>
                                    <thead>
                                        <tr>
                                            <th>首重重量</th>
                                            <th>首重费用（元）</th>
                                            <th>续重重量</th>
                                            <th>续重费用（元）</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="first_weight" lay-verify="required">
                                                    {volist name="weight" id="item"}
                                                    <option value="{$i}" label="{$item}">{$item}</option>
                                                    {/volist}
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="first_price" value="{$data.first_price|default=''}" required  lay-verify="required" placeholder="请输入首重费用（元）" autocomplete="off" class="layui-input">
                                            </td>
                                            <td>
                                                <select name="second_weight" lay-verify="required">
                                                    {volist name="weight" id="item"}
                                                    <option value="{$i}" label="{$item}">{$item}</option>
                                                    {/volist}
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="second_price" value="{$data.second_price|default=''}" required  lay-verify="required" placeholder="请输入续重费用（元）" autocomplete="off" class="layui-input">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">地区运费：</label>
                                <div class="layui-input-block">
                                    <input type="radio" lay-filter="filter-type-price" name="type" value="0" {if empty($data.type) || $data.type==0}checked{/if} title="统一地区运费">
                                    <input type="radio" lay-filter="filter-type-price" name="type" value="1" {if isset($data.type) && $data.type==1}checked{/if} title="指定地区运费">
                                </div>
                                <div class="layui-form-mid layui-word-aux">
                                    注意：如果使用指定地区运费后，没有设置运费的地区将按照默认运费来计算。
                                </div>
                            </div>

                            <div class="layui-form-item" id="filter-type-price-box" {if isset($data.type) && $data.type==1}style="display:block;"{/if}>
                                <div class="layui-btn-group">
                                    <button type='button' class="layui-btn delivery-btn layui-bg-light-blue"><i class="layui-icon">&#xe654;</i> 添加</button>
                                </div>
                                <table id="delivery-table" class="layui-table">
                                    <colgroup>
                                        <col>
                                        <col>
                                        <col width="300">
                                        <col width="148">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;">首重费用（元）</th>
                                            <th style="text-align:center;">续重费用（元）</th>
                                            <th style="text-align:center;">支持的配送地区</th>
                                            <th style="text-align:center;">操作</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        {if !empty($data.attr)}
                                            {volist name="data['attr']" id="attr"}
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="area_group[]" value="{$attr.id}" />
                                                    <input type="text" name="first_price_group[]" value="{$attr.first}" autocomplete="off" class="layui-input">
                                                </td>
                                                <td>
                                                    <input type="text" name="second_price_group[]" value="{$attr.second}" autocomplete="off" class="layui-input">
                                                </td>
                                                <td>{$attr.title}</td>
                                                <td style="padding:9px 16px;">
                                                    <div class="layui-btn-group">
                                                        <button type="button" class="layui-btn layui-btn-sm delivery-select-btn layui-bg-light-blue">选择地区</button>
                                                        <button type="button" class="layui-btn layui-btn-sm delivery-delete-btn layui-bg-light-blue">删除</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            {/volist}
                                        {/if}
                                    </tbody>
                                </table>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">排序</label>
                                <div class="layui-input-block">
                                    <input type="text" name="sort" value="{$data.sort|default='0'}" lay-reqtext="请填写排序" lay-verify="required" placeholder="请输入排序" autocomplete="off" class="layui-input">
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
            </form>
                
        </div>
    </div>
</section>
<div id='delivery-template'>
    <table>
        <tr>
            <td>
                <input type="hidden" name="area_group[]" value="" />
                <input type="text" name="first_price_group[]" value="" autocomplete="off" class="layui-input">
            </td>
            <td>
                <input type="text" name="second_price_group[]" value="" autocomplete="off" class="layui-input">
            </td>
            <td></td>
            <td style="padding:9px 16px;">
                <div class="layui-btn-group">
                    <button type="button" class="layui-btn layui-btn-sm delivery-select-btn layui-bg-light-blue">选择地区</button>
                    <button type="button" class="layui-btn layui-btn-sm delivery-delete-btn layui-bg-light-blue">删除</button>
                </div>
            </td>
        </tr>
    </table>
</div>
<style type="text/css">#delivery-template,#filter-type-price-box { display: none; }</style>
<script type="text/javascript">
    $(function () {
        layui.use(["form", "element",'layer'], function () {
            var form = layui.form;
            var layer = layui.layer;
            
            layui.form.on('radio(filter-type-price)', function(data){
                if(data.elem.value == 1){
                    $("#filter-type-price-box").show();
                }else{
                    $("#filter-type-price-box").hide();
                }
            });
            
            if ($.trim($("#delivery-table tbody").html()) == '') {
                $("#delivery-table tbody").append($($("#delivery-template table tbody").html()));
                layui.form.render();
            }
            
            $('.delivery-btn').on("click", function () {
                $("#delivery-table tbody").append($("#delivery-template table tbody").html());
                layui.form.render();
                return false;
            });
            
            $(document).on("click", ".delivery-select-btn", function () {
                $(".delivery-select-btn").removeAttr("data-field");
                $(this).attr("data-field",'1');
                layer.open({
                    type: 2,
                    title: '查询地区',
                    shadeClose: true,
                    shade: 0.8,
                    area: ['80%', '90%'],
                    content: '{:createUrl("common.ajax/get_distribution")}'
                });

                return false;
            });
            
            $(document).on("click", ".delivery-delete-btn", function () {
                if ($(".layui-table tbody tr").length > 1) {
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