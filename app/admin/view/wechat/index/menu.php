<div class="row content-nav inline-page-box">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;微信管理</a></li>
            <li><a href="javascript:;">公众号</a></li>
        </ul>
    </div>
</div>

<section class="content">
    <div class="row">
        <div class="col-md-3 l-col-md-3">

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">菜单</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        {include file="wechat/common/wechat_menu"}
                    </ul>
                </div>

            </div>

        </div>

        <div class="col-md-9 r-col-md-9">
            <div class="layui-fluid">
                <div class="layui-card">

                    <div class="col-md-4 l-col-md-4">
                        <div class="wechat-preview inline-block">
                            <div class="wechat-header">公众号</div>
                            <div class="wechat-body"></div>
                            <div class="wechat-menu-box">
                                <ul class="wechat-footer notselect"></ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 r-col-md-8">
                        <section class="content clearfix" style="padding-top: 0;">
                            <div class="layui-editor-box">
                                <div style="margin-top: 0;" class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
                                    <ul class="layui-tab-title">
                                        <li class="layui-this">基本信息</li>
                                    </ul>
                                    <form action="" class="layui-form layui-form-pane">
                                        <div class="layui-tab-content">

                                            <div class="layui-tab-item layui-show">
                                                <div class="menu-empty">
                                                    <blockquote class="layui-elem-quote text-center menu-border">请在左侧创建菜单...</blockquote>
                                                </div>
                                                <div class="menu-fields-box">
                                                    <div class="layui-form-item">
                                                        <label class="layui-form-label">菜单名称</label>
                                                        <div class="layui-input-block">
                                                            <input type="text" name="title" lay-reqtext="请填写菜单名称" lay-verify="required" placeholder="请输入菜单名称" autocomplete="off" class="layui-input">
                                                        </div>
                                                    </div>

                                                    <div class="layui-form-item">
                                                        <label class="layui-form-label">菜单类型</label>
                                                        <div class="layui-input-block">
                                                            <input type="radio" name="type" title="匹配规则" value="click" lay-filter="type">
                                                            <input type="radio" name="type" title="跳转网页" value="view" lay-filter="type">
                                                            <input type="radio" name="type" title="打开小程序" value="miniprogram" lay-filter="type">
                                                            <input type="radio" name="type" title="扫码推事件" value="scancode_push" lay-filter="type">
                                                            <input type="radio" name="type" title="扫码推事件且弹出“消息接收中”提示框" value="scancode_waitmsg" lay-filter="type">
                                                            <input type="radio" name="type" title="弹出系统拍照发图" value="pic_sysphoto" lay-filter="type">
                                                            <input type="radio" name="type" title="弹出拍照或者相册发图" value="pic_photo_or_album" lay-filter="type">
                                                            <input type="radio" name="type" title="弹出微信相册发图器" value="pic_weixin" lay-filter="type">
                                                            <input type="radio" name="type" title="弹出地理位置选择器" value="location_select" lay-filter="type">
                                                        </div>
                                                    </div>

                                                    <div class="layui-form-item data-type-box" data-type="keys">
                                                        <label class="layui-form-label">匹配规则</label>
                                                        <div class="layui-input-block">
                                                            <select name="key" lay-filter="attr-select">
                                                                {if !empty($keys)}
                                                                {volist name="keys" id="item"}
                                                                <option value="{$item.keys}">{$item.keys}</option>
                                                                {/volist}
                                                                {/if}
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="layui-form-item data-type-box" data-type="url">
                                                        <label class="layui-form-label">跳转链接</label>
                                                        <div class="layui-input-block">
                                                            <textarea class="layui-textarea" name="jump"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="layui-form-item data-type-box" data-type="mini">
                                                        <label class="layui-form-label">链接</label>
                                                        <div class="layui-input-block">
                                                            <input type="text" name="url" placeholder="请输入小程序链接" autocomplete="off" class="layui-input">
                                                        </div>
                                                    </div>

                                                    <div class="layui-form-item data-type-box" data-type="mini">
                                                        <label class="layui-form-label">APPID</label>
                                                        <div class="layui-input-block">
                                                            <input type="text" name="appid" placeholder="请输入小程序APPID" autocomplete="off" class="layui-input">
                                                        </div>
                                                    </div>

                                                    <div class="layui-form-item data-type-box" data-type="mini">
                                                        <label class="layui-form-label">页面</label>
                                                        <div class="layui-input-block">
                                                            <input type="text" name="pagepath" placeholder="请输入小程序页面" autocomplete="off" class="layui-input">
                                                        </div>
                                                    </div>

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
                    </div>

                </div>
            </div>
        </div>

    </div>

</section>

<script id="menu-list-box" type="text/html">
    <ul class="wechat-footer notselect">
        {{#  layui.each(d, function(index, item){ }}
        <li class="parent-menu" {{#  if(d.length == 1){ }}style="width:50%"{{#  } }}{{#  if(d.length >= 2){ }}style="width:33.333%"{{#  } }}>
            <a data-index="{{ index }}">
                <i class="icon-sub" ></i>
                <span>{{ item.name }}</span>
            </a>
            <i class="close layui-bg-gray layui-icon" data-index="{{ index }}">ဆ</i>
            <div class="sub-menu text-center" >
                <ul>
                    {{#  layui.each(item.sub_button, function(j, value){ }}
                    <li>
                        <a class="bottom-border" data-index="{{ index }}-{{ j }}">
                            <span>{{ value.name }}</span>
                        </a>
                        <i class="close layui-bg-gray layui-icon" data-index="{{ index }}-{{ j }}">ဆ</i>
                    </li>
                    {{#  }); }}
                    {{#  if(item.sub_button == undefined || item.sub_button.length <= 4){ }}
                    <li class="menu-add" data-index="{{ index }}">
                        <a><i class="icon-add"></i></a>
                    </li>
                    {{#  } }}
                </ul>
                <i class="arrow arrow_out"></i>
                <i class="arrow arrow_in"></i>
            </div>

        </li>
        {{#  }); }}
        {{#  if(d.length <= 2){ }}
        <li class="parent-menu menu-add" {{#  if(d.length == 0){ }}style="width:100%"{{#  } }}{{#  if(d.length == 1){ }}style="width:50%"{{#  } }}{{#  if(d.length == 2){ }}style="width:33.333%"{{#  } }}>
            <a><i class="icon-add"></i></a>
        </li>
        {{#  } }}
    </ul>
</script>
<script type="text/javascript">
$(function (){
    layui.use(["form", "element","layer","laytpl"], function () {
        var form = layui.form;
        var layer = layui.layer;
        var laytpl = layui.laytpl;

        var data = JSON.parse('{$data|raw|default="[]"}');
        var activeIndex = 0;
        var activeChildrenIndex = 0;
        var checkChildren = false;

        $(".menu-empty").show();

        var renderHtml = function (){
            laytpl(document.getElementById("menu-list-box").innerHTML).render(data, function (html) {
                $(".wechat-menu-box").html(html);
            });
        };

        var sw = function (type){
            $('[data-type="keys"]').hide();
            $('[data-type="url"]').hide();
            $('[data-type="mini"]').hide();
            switch (type) {
                case "click":
                    $('[data-type="keys"]').show();
                    break;
                case "view":
                    $('[data-type="url"]').show();
                    break;
                case "miniprogram":
                    $('[data-type="mini"]').show();
                    break;
            }
        };
        renderHtml();

        $(document).on("click",".wechat-preview .wechat-footer li > .close",function (event){
            var pt = $(this).parent();
            var index = $(this).attr("data-index").split("-");
            if(index.length == 2){
                data[index[0]].sub_button.splice(index[1],1);
            }else{
                data.splice(index[0],1);
            }
            $(".menu-fields-box").hide();
            $(".menu-empty").show();
            //event.preventDefault();
            event.stopPropagation();
            renderHtml();
        });

        $(document).on("click",".wechat-footer a",function (event){
            if($(this).parent().is(".menu-add")){
                return true;
            }
            $(".menu-empty").hide();
            $(".menu-fields-box").show();
            $(".wechat-footer a").removeClass("active");
            $(this).addClass("active");
            var arr = $(this).attr("data-index").split("-");

            var array = [];
            activeIndex = arr[0];
            if(arr.length == 2){
                activeChildrenIndex = arr[1];
                array = data[activeIndex].sub_button[activeChildrenIndex];
                checkChildren = true;
            }else{
                array = data[activeIndex];
                checkChildren = false;
            }

            sw(array.type);
            $('[value="'+(array.type)+'"]').prop("checked",true);
            $('[name="title"]').val(array.name);

            switch (array.type) {
                case "click":
                    $('[option="'+array.key+'"]').prop("selected",true);
                    form.render();
                    break;
                case "view":
                    $('[name="jump"]').val(array.url);
                    break;
                case "miniprogram":
                    $('[name="url"]').val(array.url);
                    $('[name="appid"]').val(array.appid);
                    $('[name="pagepath"]').val(array.pagepath);
                    break;
            }
            form.render();
        });

        $(document).find('[name="title"]').on("input",function (){
            if(checkChildren){
                data[activeIndex].sub_button[activeChildrenIndex].name = $(this).val();
            }else {
                data[activeIndex].name = $(this).val();
            }
            renderHtml();
        });

        form.on('radio(type)', function(result){
            if(checkChildren){
                data[activeIndex].sub_button[activeChildrenIndex].type = result.value;
            }else {
                data[activeIndex].type = result.value;
            }
            if(result.value == "click"){
                var value = $('[name="key"] option:first').val();
                if(checkChildren){
                    data[activeIndex].sub_button[activeChildrenIndex].key = value;
                }else {
                    data[activeIndex].key = value;
                }
            }

            sw(result.value);
        });

        form.on('select(attr-select)', function(result){
            if(checkChildren){
                data[activeIndex].sub_button[activeChildrenIndex].key = result.value;
            }else {
                data[activeIndex].key = result.value;
            }
        });

        $(document).on("input",'[name="jump"]',function(){
            if(checkChildren){
                data[activeIndex].sub_button[activeChildrenIndex].url = $(this).val();
            }else {
                data[activeIndex].url = $(this).val();
            }
        });

        $(document).on("input",'[name="url"]',function(){
            if(checkChildren){
                data[activeIndex].sub_button[activeChildrenIndex].url = $(this).val();
            }else {
                data[activeIndex].url = $(this).val();
            }
        });

        $(document).on("input",'[name="appid"]',function(){
            if(checkChildren){
                data[activeIndex].sub_button[activeChildrenIndex].appid = $(this).val();
            }else {
                data[activeIndex].appid = $(this).val();
            }
        });

        $(document).on("input",'[name="pagepath"]',function(){
            if(checkChildren){
                data[activeIndex].sub_button[activeChildrenIndex].pagepath = $(this).val();
            }else {
                data[activeIndex].pagepath = $(this).val();
            }
        });

        $(document).on("click",".menu-add",function (){
            if($(this).is(".parent-menu")){
                data.push({"name": "请输入名称","type":"click","key":""});
            }else{
                var index = $(this).attr("data-index");
                if(data[index].sub_button == undefined){
                    delete data[index].type;
                    delete data[index].key;
                    data[index].sub_button = [];
                }
                data[index].sub_button.push({
                    "name":"请输入名称","type":"click","key":""
                });
            }

            $(".menu-fields-box").hide();
            $(".menu-empty").show();
            renderHtml();
        });

        $(document).on("mouseover",".wechat-preview .wechat-footer li",function (){
            $(this).find(".close").show();
        });

        $(document).on("mouseout",".wechat-preview .wechat-footer li",function (){
            $(this).find(".close").hide();
        });

        form.on('submit(layui-submit-filter)', function (r) {
            var index = layer.load(1, { shade: [0.2,'#fff'] });
            $.post('{:createUrl("menu")}', { post: data }, function (result) {
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
<style type="text/css">

</style>



