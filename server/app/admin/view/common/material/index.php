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
    <script src="{__SYSTEM_PATH__}/js/fastclick/fastclick.js"></script>
    <script src="{__SYSTEM_PATH__}/js/adminlte/adminlte.min.js"></script>
    <script src="{__SYSTEM_PATH__}/js/jquery-slimscroll/jquery.slimscroll.min.js"></script>
</head>
<body>

<div class="material-wrap">

    <div class="material-l">
        <ul>
            <li class="active" data-id="0">全部文件</li>
        </ul>
    </div>

    <div class="material-r">
        <div class="material-tools-box">
            <div class="left-btn">
                <button id="create-category" type="button" class="layui-btn layui-btn-normal layui-btn-sm">
                    <i class="layui-icon">&#xe654;</i>添加分类
                </button>
                <button id="delete-image" type="button" class="layui-btn layui-btn-normal layui-btn-sm layui-btn-danger">
                    <i class="layui-icon">&#xe640;</i>删除选中文件
                </button>
            </div>
            <div class="upload">
                <button id="uploadfiy" type="button" class="layui-btn layui-btn-normal layui-btn-sm">
                    <i class="layui-icon">&#xe67c;</i>上传
                </button>
            </div>
        </div>
        <div class="upload-list-box clearfix">
            <div id="upload-loading-box"><span class="fa fa-spinner fa-spin fa-2x fa-fw"></span></div>
            <div id="upload-empty-list">暂无内容</div>
            <div class="layui-row"></div>
        </div>
        <div class="bottom">
            <div class="sel-box">
                <button id="sel-btn" type="button" class="layui-btn layui-btn-normal layui-btn-sm">
                    选中图片(<i>0</i>)张
                </button>
            </div>
            <div class="page" id="hide"></div>
        </div>
    </div>

</div>
<script>
    var array = [];
    layui.use(['upload','layer'], function () {
        var upload = layui.upload;
        var layer = layui.layer;

        $("#sel-btn").on("click",function (){
            parent.{$callback}(array);
        });

        $(".page").show();

        var handle = function (res){
            $("#upload-loading-box").hide();
            $(".upload-list-box .layui-row").html("");
            if(res.data.result.length){
                $("#upload-empty-list").hide();
                for(var obj in res.data.result){
                    var data = res.data.result[obj];
                    var str = "<div class='layui-col-md2 layui-col-upload-box'> <div class='upload-ls-box' data-id='"+data.id+"' data-img='"+data.path+"'> <div class='img' align='center'><img src='"+data.path+"'></div><div class='tit'>"+data.name+"</div></div></div>";
                    $(".upload-list-box .layui-row").append(str);
                }
                $(".upload-list-box").scrollTop(0);
            }else{
                if(res.data.count){
                    if($(".upload-list-box .layui-row .layui-col-upload-box").length){
                        $("#upload-empty-list").hide();
                    }else{
                        $("#upload-empty-list").show();
                    }
                }else{
                    $("#upload-empty-list").show();
                }
            }
            $(".page").html(res.data.page);
        };

        var getMenu = function (){
            $.get("{:createUrl('common.material/get_cat',['type'=>$type])}",function (res){
                if(res.data.length){
                    var str = "<li data-id='0' class='active'>全部文件</li>";
                    for(var i=0; i<res.data.length; i++){
                        str += "<li data-id='"+res.data[i].id+"'>"+res.data[i].name+"<span class='delete'>x</span></li>";
                    }
                    $(".material-l ul").html(str);
                    $("#upload-loading-box").hide();
                }
            },"json");
        };

        getMenu();

        $(document).on("click",".material-l li",function (e){
            e.stopPropagation();
            var index = $(".material-l li").index($(this));
            $(".material-l li").removeClass("active").eq(index).addClass("active");
            $("#upload-loading-box").show();
            $.get("{:createUrl('common.material/get_list')}",{
                type: '{$type}',"cat_id": $(".material-l li.active").attr("data-id")
            },function (res){
                handle(res);
            },"json");
        });

        $(document).on("click",".material-l li span",function (e){
            e.stopPropagation();
            var that = $(this);
            var pid = $(this).parent().attr("data-id");
            layer.confirm('确定要删除吗，该操作将删除分类下所有资源？', {
                btn: ['确定','取消']
            }, function(){
                $.get("{:createUrl('common.material/delete_category')}",{ id: pid },function (res){
                    if(res.code){
                        that.parent().remove();
                        $(".material-l li").eq(0).trigger("click");
                    }
                    layer.msg(res.msg);
                },"json");
            }, function(){});
        });

        $("#create-category").on("click",function (){
            layer.prompt({title: '请输入分类名称', formType: 0}, function(text, index){
                var loadIndex = layer.load(1, {
                    shade: [0.1,'#fff'],time: 5000 * 1000
                });
                $.post("{:createUrl('common.material/create_category')}",{ name: text,type: '{$type}' },function(res){
                    if(res.code){
                        layer.close(index);
                        getMenu();
                    }else{
                        layer.msg(res.msg);
                    }
                },"json");

                layer.close(loadIndex);
            });
        });

        var getList = function (){
            $("#upload-loading-box").show();
            $.get("{:createUrl('common.material/get_list')}",{
                type: '{$type}',cat_id: $(".material-l li.active").attr("data-id")
            },function (res){
                handle(res);
            },"json");
        };

        getList();

        upload.render({
            elem: '#uploadfiy'
            ,url: '{:createUrl("common.uploadfiy/upload")}'
            ,multiple: true
            {if input('type','image') == 'image'}
            ,exts: 'jpg|png|gif|bmp|jpeg'
            {elseif input('type') == 'video'}
            ,exts: 'mp4'
            {elseif input('type') == 'file'}
            ,exts: 'zip|rar|txt'
            {/if}
            ,data: {
                cat_id: function (){
                    return $(".material-l li.active").attr("data-id");
                },
                module: function (){
                    return "{:input('module')}";
                },
                method: function (){
                    return "{:input('method')}";
                }
            }
            //,number: 5
            ,done: function(res){
                if(res.code == 0){
                    getList();
                }else{
                    layer.msg(res.msg,{ icon : 2 });
                }
            }
            ,error: function(){
                layer.msg("调用上传接口失败，请稍后在试。",{ icon : 2 });
            }
        });

        $(document).on("mouseover",".material-l li",function (){
            $(this).find("span").show();
        });

        $(document).on("mouseout",".material-l li",function (){
            $(this).find("span").hide();
        });

        $(document).on("click",".page a",function (){
            $.get($(this).attr("href"),function (res){
                handle(res);
            },"json");
            return false;
        });

        $(document).on("click",".upload-ls-box",function (){
            if($(this).is(".active")){
                $(this).removeClass("active");
            }else{
                $(this).addClass("active");
            }

            if($(".upload-ls-box.active",document).length){
                array = [];
                $(".upload-ls-box.active",document).each(function (index,item){
                    array.push($(item).attr("data-img"));
                });
                $(".sel-box").show();
                $(".sel-box i").text($(".upload-ls-box.active",document).length);
            }else{
                array = [];
                $(".sel-box").hide();
                $(".sel-box i").text(0);
            }
        });

        $("#delete-image").on("click",function (){
            if($(".upload-ls-box.active",document).length <= 0){
                layer.msg("请选择要删除的图片");
                return false;
            }

            var array = [];
            $(".upload-ls-box.active",document).each(function (){
                array.push($(this).attr("data-id"));
            });

            if(array.length <= 0){
                return false;
            }

            $.get("{:createUrl('common.material/delete')}",{ id: array.join(",") },function(res){
                if(res.code){
                    $.get("{:createUrl('common.material/get_list')}",{
                        type: '{$type}',"cat_id": $(".material-l li.active").attr("data-id")
                    },function (res){
                        handle(res);
                    },"json");
                }
                layer.msg(res.msg);
            },"json");
        });

    });
</script>
<style type="text/css">
    #hide { display: none; }
    .material-wrap { width: 100%; height: 550px; background: #fff; margin: 0px auto 20px auto; }

    .material-l { float: left; width: 14%; height: 550px; overflow-y: auto; }
    .material-l ul { display: block; overflow-y: auto; }
    .material-l ul li { position: relative; color: #333; cursor: pointer; text-align: center; height: 30px; line-height: 30px; }
    .material-l ul li.active,.material-l ul li:hover { color: #1E9FFF; }
    .material-l ul li:first-child { padding-top: 5px; }
    .material-l ul li .delete { display: none; position: absolute; top: 0; right: 10px; }

    .material-r { float: right; width: 85%; height: 550px; border-left: 1px solid #e6e6e6; position: relative; }
    .material-r #upload-loading-box { background: rgba(255,255,255,.7); z-index: 9999; position: absolute; left: 0; top: 0; width: 100%; height: 550px; }
    .material-r #upload-loading-box span { z-index: 19999; color: #666; position: absolute; left: 45%; top: 42%; transform: translate(-45%,-42%); }
    .material-r #upload-empty-list { display: none; text-align: center; font-size: 16px; position: absolute; left: 45%; top: 42%; transform: translate(-45%,-42%); }
    .material-tools-box { float: left; width: 100%; padding: 10px 1% 0 1%; height: 50px; border-bottom: 1px solid #e6e6e6; }
    .material-tools-box .left-btn { float: left; }
    .material-tools-box .upload { float: right; }

    .upload-list-box { width: 100%; height: 450px; padding-top: 15px; overflow-y: auto; }
    .upload-list-box .upload-ls-box { margin: 0 auto; border-radius: 4px; border: 1px solid #fff; background: #f4f4f4; margin-bottom: 10px; width: 123px; height: 171px; overflow: hidden; }
    .upload-list-box .upload-ls-box.active { border: 1px solid #f17d7d; }
    .upload-list-box .upload-ls-box .img { background: #fff; padding-bottom: 10px; }
    .upload-list-box .upload-ls-box .tit { text-align: center; font-size: 14px; padding-top: 5px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .upload-list-box .upload-ls-box div img { text-align: center; max-width: 120px; height: 130px; }
    .upload-list-box .upload-ls-box div.img { width: 120px; height: 130px; }
    .layui-col-upload-box { text-align: center; }

    .material-r .bottom { position: absolute; left: 0; bottom: 0px; width: 100%; }
    .material-r .bottom .sel-box { float: left; margin-left: 9px; margin-top: 10px; display: none; }
    .material-r .bottom .sel-box i { font-style: normal; }
    .material-r .page { background-color: #fff; padding-top: 10px; width: 100%; height: 50px; line-height: 50px; display: inline-block; text-align: center; }
    .material-r .page .pagination { margin: 0; position: relative; }
    .material-r .pagination>li>a,
    .material-r .pagination>li>span { position: relative; float: left; height: 30px; margin-left: -1px; line-height: 30px; padding: 0px 12px; color: #337ab7; text-decoration: none; background-color: #fff; border: 1px solid #ddd; }
    .material-r .pagination>.active>a,
    .material-r .pagination>.active>a:focus,
    .material-r .pagination>.active>a:hover,
    .material-r .pagination>.active>span,
    .material-r .pagination>.active>span:focus,
    .material-r .pagination>.active>span:hover { z-index: 3; color: #fff; cursor: default; background-color: #337ab7; border-color: #337ab7; }
</style>
</body>
</html>