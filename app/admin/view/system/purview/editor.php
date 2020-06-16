<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;权限管理</a></li>
            <li><a href="javascript:;">编辑权限</a></li>
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
                                <label class="layui-form-label">权限名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="title" value="{$data.title|default=''}" lay-reqtext="请填写权限名称" lay-verify="required" placeholder="请输入权限名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                        
                            <div class="layui-form-item">
                                    
                                    <table class="layui-table">
                                        <colgroup>
                                            <col width="50">
                                            <col width="150">
                                            <col>
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" lay-filter="purview-all" name="all" value="1" title="" lay-skin="primary"></th>
                                                <th>权限类型</th>
                                                <th>权限列表</th>
                                            </tr> 
                                        </thead>
                                        <tbody>
                                            {if !empty($group)}
                                            {volist name="group" id="item"}
                                            <tr>
                                                <td>
                                                <input type="checkbox" lay-filter="purview-list"  title="" lay-skin="primary">
                                                </td>
                                                <td style="text-align:right">{$item.name}：</td>
                                                <td>
                                                {if !empty($item.children)}
                                                {volist name="item['children']" id="value"}
                                                    <p><span style="position: relative;top: 4px;">{$value.name}：</span>
                                                    {volist name="value['children']" id="v"}
                                                    <input type="checkbox" {if !empty($data['purview'][$v["controller"]][$v["method"]])}checked{/if} name="purview[{$v.controller}][{$v.method}]" value="{$v.id}" title="{$v.name}" lay-skin="primary">
                                                    {/volist}
                                                    <p>
                                                {/volist}
                                                {/if}
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
            </form>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function () {
        layui.use(["form", "element",'layer','laydate'], function () {
            var form = layui.form;
            var layer = layui.layer;
            
            form.on('checkbox(purview-all)',function (data){
                if(data.elem.checked){
                    $(data.elem).parent().parent().parent().parent().find("input").prop("checked",true);
                }else{
                    $(data.elem).parent().parent().parent().parent().find("input").prop("checked",false);
                }
                form.render();
            });
            
            form.on('checkbox(purview-list)', function(data){
                if(data.elem.checked){
                    $(data.elem).parent().parent().find("input").prop("checked",true);
                }else{
                     $(data.elem).parent().parent().find("input").prop("checked",false);
                }
                form.render();
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