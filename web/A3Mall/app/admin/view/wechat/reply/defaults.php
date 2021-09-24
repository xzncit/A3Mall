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
                            <div class="wechat-body">
                                <iframe id="wechat-ifrmae-box" frameborder="0" marginheight="0" marginwidth="0" src=""></iframe>
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

                                                <div class="layui-form-item">
                                                    <label class="layui-form-label">状态</label>
                                                    <div class="layui-input-block">
                                                        <input type="radio" name="status" title="开启" value="0" {if empty($data.status) || $data.status==0}checked="checked"{/if}>
                                                        <input type="radio" name="status" title="关闭" value="1" {if isset($data.status) && $data.status==1}checked="checked"{/if}>
                                                    </div>
                                                </div>

                                                <div class="layui-form-item">
                                                    <label class="layui-form-label">消息类型</label>
                                                    <div class="layui-input-block">
                                                        <input type="radio" name="type" title="文字" value="text" lay-filter="type" {if empty($data.type) || $data.type=="text"}checked="checked"{/if}>
                                                        <input type="radio" name="type" title="图文" value="news" lay-filter="type" {if empty($data.type) || $data.type=="news"}checked="checked"{/if}>
                                                        <input type="radio" name="type" title="图片" value="image" lay-filter="type" {if empty($data.type) || $data.type=="image"}checked="checked"{/if}>
                                                        <!-- input type="radio" name="type" title="音乐" value="music" lay-filter="type">
                                                        <input type="radio" name="type" title="视频" value="video" lay-filter="type">
                                                        <input type="radio" name="type" title="语音" value="voice" lay-filter="type" -->
                                                    </div>
                                                </div>

                                                <div class="layui-form-item data-type" data-type="text">
                                                    <label class="layui-form-label">回复内容</label>
                                                    <div class="layui-input-block">
                                                        <textarea class="layui-textarea" name="content">{$data.content|default=''}</textarea>
                                                    </div>
                                                </div>

                                                <div class="layui-form-item data-type" data-type="news">
                                                    <label class="layui-form-label">选择图文</label>
                                                    <div class="layui-input-block">
                                                        <button type="button" id="select-news-btn" class="layui-btn layui-btn-primary">选择图文</button>
                                                    </div>
                                                </div>

                                                <div class="layui-form-item data-type" data-type="image">
                                                    <label class="layui-form-label">图片地址</label>
                                                    <div class="layui-input-block">
                                                        <input type="text" id="image-input-text" name="image_url" value="{$data.image_url|default=''}" placeholder="请上传图片" readonly="readonly" style="width: 70%;float: left;" autocomplete="off" class="layui-input">
                                                        <button type="button" id="upload-images" class="layui-btn layui-btn-primary" style="float: left">上传图片</button>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="layui-form-item">
                                            <div class="layui-input-block">
                                                <input type="hidden" name="news_id" value="{$data.news_id|default='0'}">
                                                <input type="hidden" name="id" value="{$data.id|default='0'}">
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
<style type="text/css">
.data-type{ display: none; }
</style>
<script type="text/javascript">
    var setUrl = function (type){
        var url = "";
        var content = "";
        switch(type){
            case "text":
                url = '{:createUrl("common.wechat/index",["type"=>"text"])}';
                content = $('[name="content"]').val();
                break;
            case "news":
                url = '{:createUrl("common.wechat/index",["type"=>"news"])}';
                content = $('[name="news_id"]').val();
                break;
            case "image":
                url = '{:createUrl("common.wechat/index",["type"=>"image"])}';
                content = $('[name="image_url"]').val();
                break;
        }
        url += url.indexOf("?") != -1 ? "&content=" + content : "?content=" + content;
        $("#wechat-ifrmae-box").attr("src",url);
    };
    var handleNews = function (id){
        $('[name="news_id"]').val(id);
        setUrl("news");
    };
    $(function (){

        layui.use(["form", "element","layer","upload"], function () {
            var form = layui.form;
            var layer = layui.layer;
            var upload = layui.upload;

            {if isset($data.type)}
            $('[data-type="{$data.type}"]').show();
            setUrl("{$data.type}");
            {else}
            $('[name="type"]').prop("checked",false);
            $('[value="text"]').prop("checked",true);
            form.render();
            $('[data-type="text"]').show();
            setUrl("text");
            {/if}

            $("#select-news-btn").on("click",function (){
                layer.open({
                    type: 2,
                    title: false,
                    closeBtn: 1,
                    shadeClose: true,
                    skin: 'yourclass',
                    area: ['80%', '90%'],
                    content: '{:createUrl("common.wechat/article")}'
                });

                return false;
            });

            form.on('radio(type)', function(data){
                $(".data-type").hide();
                $('[data-type="'+data.value+'"]').show();
                setUrl(data.value);
            });

            $('[name="content"]').on("input",function (){
                setUrl("text");
            })

            upload.render({
                elem: '#upload-images'
                ,url: '{:url("common.uploadfiy/image")}'
                ,multiple: false
                ,exts: 'jpg|png|gif|bmp|jpeg'
                ,data: {
                    module: function (){
                        return "keys";
                    },
                    method: function (){
                        return "";
                    }
                }
                ,done: function(res){
                    if(!res.code){
                        $("#image-input-text").val(res.data.src);
                        setTimeout(function (){ setUrl("image"); },200);
                    }else{
                        layer.msg(res.msg,{ icon : 2 });
                    }
                }
            });

            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("defaults")}', data.field, function (result) {
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



