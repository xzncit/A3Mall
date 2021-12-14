<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;发货管理</a></li>
            <li><a href="javascript:;">编辑信息</a></li>
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
                                <label class="layui-form-label">发货点名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="title" value="{$data.title|default=''}" lay-reqtext="请填写发货点名称" lay-verify="required" placeholder="请输入发货点名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">发货人姓名</label>
                                <div class="layui-input-block">
                                    <input type="text" name="username" value="{$data.username|default=''}" lay-reqtext="请填写发货人姓名" lay-verify="required" placeholder="请输入发货人姓名" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">公司名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="company" value="{$data.company|default=''}" placeholder="请输入公司名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">地址</label>
                                <div class="layui-input-block">
                                    <input type="text" name="address" value="{$data.address|default=''}" lay-reqtext="请填写地址" lay-verify="required" placeholder="请输入地址" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">邮编</label>
                                <div class="layui-input-block">
                                    <input type="text" name="zip" value="{$data.zip|default=''}" placeholder="请输入排序" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">手机</label>
                                <div class="layui-input-block">
                                    <input type="text" name="mobile" value="{$data.mobile|default=''}" lay-reqtext="请填写排序" lay-verify="required" placeholder="请输入手机" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">电话</label>
                                <div class="layui-input-block">
                                    <input type="text" name="phone" value="{$data.phone|default=''}" placeholder="请输入电话" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">所在地区</label>
                                <div class="layui-input-inline">
                                    <select name="province" lay-filter="province-filter">
                                        <option value="">请选择省</option>
                                        {if !empty($province)}
                                        {volist name="province" id="value"}
                                        <option value="{$value.id}"{if !empty($data.province) && $value.id==$data.province} selected{/if}>{$value.name}</option>
                                        {/volist}
                                        {/if}
                                    </select>
                                </div>
                                <div class="layui-input-inline">
                                    <select name="city" lay-filter="city-filter">
                                        <option value="">请选择市</option>
                                        {if !empty($city)}
                                        {volist name="city" id="value"}
                                        <option value="{$value.id}"{if !empty($data.city) && $value.id==$data.city} selected{/if}>{$value.name}</option>
                                        {/volist}
                                        {/if}
                                    </select>
                                </div>
                                <div class="layui-input-inline">
                                    <select name="area" lay-filter="area-filter">
                                        <option value="">请选择县/区</option>
                                        {if !empty($area)}
                                        {volist name="area" id="value"}
                                        <option value="{$value.id}"{if !empty($data.area) && $value.id==$data.area} selected{/if}>{$value.name}</option>
                                        {/volist}
                                        {/if}
                                    </select>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">默认地址</label>
                                <div class="layui-input-block">
                                    <input type="checkbox" {if isset($data.is_default) && $data.is_default==1}checked="checked"{/if} name="is_default" value="1" lay-skin="switch" lay-text="ON|OFF">
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
        layui.use(["form", "element",'layer'], function () {
            var form = layui.form;
            var layer = layui.layer;
            
            form.on('select(province-filter)', function(data){
                if(data.value == ""){
                    return true;
                }

                $.get("{:createUrl('common.ajax/get_area')}",{
                    "id" : data.value
                },function (result){
                    if(result.code){
                        $('[name="city"]').html(result.data);
                        $('[name="area"]').html('<option value="">请选择</option>');
                        layui.form.render();
                    }else{
                        layer.msg(result.msg,{ icon : 2 });
                    }
                },"json");
            });
            
            form.on('select(city-filter)', function(data){
                if(data.value == ""){
                    return true;
                }

                $.get("{:createUrl('common.ajax/get_area')}",{
                    "id" : data.value
                },function (result){
                    if(result.code){
                        $('[name="area"]').html(result.data);
                        layui.form.render();
                    }else{
                        layer.msg(result.msg,{ icon : 2 });
                    }
                },"json");
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