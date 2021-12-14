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
            <a href="{:createUrl('editor')}" class="btn btn-primary btn-block margin-bottom">添加图片</a>
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

                    <div class="col-md-3">
                        <div class="article-left">
                            <div class="layui-card-body">
                                <div class="ls-box">
                                    <div id="left-wrap"></div>
                                </div>
                                <a class="article-add"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-9">
                        <div class="article-right">
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
                                                        <label class="layui-form-label">文章标题</label>
                                                        <div class="layui-input-block">
                                                            <input type="text" name="title" value="" lay-reqtext="请填写文章标题" lay-verify="required" placeholder="请输入文章标题" autocomplete="off" class="layui-input">
                                                        </div>
                                                    </div>

                                                    <div class="layui-form-item">
                                                        <label class="layui-form-label">文章作者</label>
                                                        <div class="layui-input-block">
                                                            <input type="text" name="author" value="" lay-reqtext="请填写文章作者" lay-verify="required" placeholder="请输入文章作者" autocomplete="off" class="layui-input">
                                                        </div>
                                                    </div>

                                                    <div class="layui-form-item clearfix">
                                                        <div class="layui-upload clearfix">
                                                            <button type="button" class="layui-btn layui-bg-light-blue" id="uploadfiy-btn"><i class="layui-icon"></i>上传图片</button>
                                                            <blockquote class="layui-elem-quote layui-quote-nm clearfix" style="margin-top: 10px;">
                                                                预览图：
                                                                <div class="layui-upload-list" id="uploadfiy-list-box">

                                                                </div>
                                                            </blockquote>
                                                        </div>
                                                    </div>

                                                    <div class="layui-form-item">
                                                        <script id="container" style="width:100%;height: 500px;" name="content" type="text/plain"></script>
                                                    </div>

                                                    <div class="layui-form-item data-type">
                                                        <label class="layui-form-label">摘要</label>
                                                        <div class="layui-input-block">
                                                            <textarea class="layui-textarea" name="digest"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="layui-form-item">
                                                        <label class="layui-form-label">原文链接</label>
                                                        <div class="layui-input-block">
                                                            <input type="text" name="content_source_url" value="" placeholder="请输入原文链接" autocomplete="off" class="layui-input">
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

    </div>

</section>

<script id="left" type="text/html">

    {{#  layui.each(d, function(index, item){ }}
    <div class="article-item-wrap">
        <div class="article-item" style="background-image:url({{ item.local_url }})">
        <span class="article-item-top">
            <a class="layui-icon dd">&#x1006;</a>
            <!-- a class="layui-icon down">&#xe61a;</a -->
            <!-- a class="layui-icon up">&#xe619;</a -->
        </span>
            <span class="article-title">{{ item.title }}</span>
        </div>
        <hr>
    </div>
    {{#  }); }}
    {{#  if(d.length === 0){ }}
    <div class="article-item-wrap">
        <div class="article-item active" style="background-image:url(/static/images/default.jpg)">
        <span class="article-item-top">
            <a class="layui-icon dd">&#x1006;</a>
            <!-- a class="layui-icon down">&#xe61a;</a -->
            <!-- a class="layui-icon up">&#xe619;</a -->
        </span>
            <span class="article-title">新建图文</span>
        </div>
        <hr>
    </div>
    {{#  } }}

</script>
<script id="images-list-box" type="text/html">
    {{#  layui.each(d.images, function(index, item){ }}
    <div class="uploadfiy-box">
        <input type="hidden" name="attachment_id[]" value="{{ item.id }}">
        <a class="upload-image"><img src="{{ item.path }}"></a>
        <div class="uploadfiy-button">
            <a href="javascript:;" class="n6-insert">插入</a>
            {{#  if(d.local_url.length > 0 && d.local_url == item.path){ }}
            <a href="javascript:;" class="n6-thumb active">封面</a>
            {{#  } else { }}
            <a href="javascript:;" class="n6-thumb">封面</a>
            {{#  } }}
            <a href="javascript:;" class="n6-delete">删除</a>
        </div>
    </div>
    {{#  }); }}
</script>

<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
UE.getEditor('container').addListener("ready", function (){
    var ue = UE.getEditor('container');
    layui.use(["form", "element","layer","upload","laytpl"], function() {
        var form = layui.form;
        var layer = layui.layer;
        var upload = layui.upload;
        var laytpl = layui.laytpl;

        var data = JSON.parse('{$data.article|raw|default="[]"}');
        var pid = '{$data.id|default="0"}';

        var activeIndex = 0;
        var renderHtml = function (){
            laytpl(document.getElementById("left").innerHTML).render(data, function (html) {
                $("#left-wrap").html(html);
                $(document).find(".article-item").eq(activeIndex).addClass("active");
                $(document).find('[name="title"]').val(data[activeIndex].title);
                $(document).find('[name="author"]').val(data[activeIndex].author);
                $(document).find('.article-item').eq(activeIndex).css("background-image",data[activeIndex].photo);
                UE.getEditor('container').setContent(data[activeIndex].content,false);
                $(document).find('[name="digest"]').val(data[activeIndex].digest);
                $(document).find('[name="content_source_url"]').val(data[activeIndex].content_source_url);
            });
        };

        var renderImage = function (){
            var image = data[activeIndex];
            laytpl(document.getElementById("images-list-box").innerHTML).render(image, function (html) {
                $(".layui-upload-list").html(html);
            });
        };

        $(document).find('[name="title"]').on("input",function (){
            data[activeIndex].title = $(this).val();
            renderHtml();
        });

        $(document).find('[name="author"]').on("input",function (){
            data[activeIndex].author = $(this).val();
            renderHtml();
        });

        $(document).find('[name="content"]').on("input",function (){
            data[activeIndex].content = $(this).val();
            renderHtml();
        });

        UE.getEditor('container').addListener('blur',function(editor){
            data[activeIndex].content = UE.getEditor('container').getContent();
            renderHtml();
        });

        $(document).find('[name="digest"]').on("input",function (){
            data[activeIndex].digest = $(this).val();
            renderHtml();
        });

        $(document).find('[name="content_source_url"]').on("input",function (){
            data[activeIndex].content_source_url = $(this).val();
            renderHtml();
        });

        var i = 0;
        $(document).on("click",".article-add",function (){
            data.push({
                "id": "0",
                "title":"新建图文-"+(i+1),
                "show_cover_pic":1,
                "author": "管理员",
                "local_url": "/static/images/default.jpg",
                "images":[],
                "content": "请填写内容",
                "digest": "",
                "content_source_url": ""
            });

            i++;
            renderHtml();
            renderImage();
        });

        {if !empty($data)}
            renderHtml();
        {else}
            $(".article-add").trigger("click");
        {/if}


        $(document).on("click",".article-item",function () {
            var index = $(this).index(".article-item");
            activeIndex = index;
            renderHtml();
            renderImage();
        });

        $(document).on("click",".article-item-top",function (e){
            var index = $(this).index(".article-item-top");
            if($(e.target).is(".up")){
                if(index!=0){
                    data[index] = data.splice(index-1, 1, data[index])[0];
                }else{
                    data.push(data.shift());
                }
            }else if($(e.target).is(".down")){
                if(index!=data.length-1){
                    data[index] = data.splice(index+1, 1, data[index])[0];
                }else{
                    data.unshift(data.splice(index,1)[0]);
                }
            }else if($(e.target).is(".dd")){
                if($(".article-item-top").length <= 1){
                    return false;
                }
                data.splice(index,1);
            }else{
                return false;
            }
            var arr = [];
            for(var i in data){
                if(data[i]){
                    arr.push(data[i]);
                }
            }
            data = arr;
            activeIndex = 0;
            renderHtml();
        });

        //多图片上传
        upload.render({
            elem: '#uploadfiy-btn'
            ,url: '{:createUrl("common.uploadfiy/upload")}'
            ,multiple: true
            ,exts: 'jpg|png|gif|bmp|jpeg'
            ,data: {
                module: function (){
                    return "wechat";
                },
                method: function (){
                    return "article";
                }
            }
            ,done: function(res){
                if(!res.code){
                    data[activeIndex].images.push({
                        "id":res.data.id,
                        "path":res.data.src
                    });
                    renderImage();
                }else{
                    layer.msg(res.msg,{ icon : 2 });
                }
            }
        });

        $(document).on("click",".n6-insert",function (){
            var pt = $(this).parent().parent();
            UE.getEditor('container').setContent('<p><img src="'+pt.find("img").attr("src")+'"></p>', true);
            return false;
        });

        $(document).on("click",".n6-thumb",function (){
            if($(this).is(".active")){
                $(this).removeClass("active");
                return false;
            }

            $(".n6-thumb").removeClass("active");
            $(this).addClass("active");
            var pt = $(this).parent().parent();
            data[activeIndex].local_url = pt.find("img").attr("src");
            renderHtml();
            return false;
        });

        $(document).on("click",".n6-delete",function (){
            var index = $(this).index(".n6-delete");
            var pt = $(this).parent().parent();
            $.post('{:createUrl("common.uploadfiy/delete")}',{
                path : pt.find("img").attr("src")
            },function (result){
                if(result.code){
                    data[activeIndex].images.splice(index,1);
                    renderImage();
                    pt.remove();
                }else{
                    layer.msg(result.msg,{ icon : 2 });
                }
            },"json");
            return false;
        });

        form.on('submit(layui-submit-filter)', function (r) {
            var index = layer.load(1, { shade: [0.2,'#fff'] });
            $.post('{:createUrl("editor")}', { pid:pid,post:data }, function (result) {
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
<style type="text/css">
.article-left {
    width: 100%;
    background: #fff;
}
.article-right{
    overflow: hidden;
    width: 100%;
    position: relative;
    display: inline-block;
    background: #fff;
}
.article-left .article-item {
    height: 150px;
    cursor: pointer;
    max-width: 270px;
    overflow: hidden;
    position: relative;
    border: 1px solid #ccc;
    background-size: cover;
    background-position: center center;
}
.article-left .article-item.active {
    border: 1px solid #44b549 !important;
}
.article-left .article-item a {
    color: #fff;
    width: 30px;
    height: 30px;
    float: right;
    font-size: 12px;
    margin-top: -1px;
    line-height: 34px;
    text-align: center;
    margin-right: -1px;
    background-color: rgba(0, 0, 0, .5);
}
.article-left .article-title {
    bottom: 0;
    color: #fff;
    width: 272px;
    display: block;
    padding: 0 5px;
    max-height: 6em;
    overflow: hidden;
    margin-left: -1px;
    position: absolute;
    text-overflow: ellipsis;
    background: rgba(0, 0, 0, .7);
}
.article-item .article-item-top { display: none; }
.article-item:hover .article-item-top {
    display: block;
}
.article-left .article-add {
    color: #999;
    display: block;
    font-size: 22px;
    text-align: center;
    cursor: pointer;
}
</style>



