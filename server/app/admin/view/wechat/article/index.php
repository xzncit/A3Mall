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
                    <ul class="flow-default" style="height: 450px;" id="lay-flow-box"></ul>
                </div>
            </div>
        </div>

    </div>

</section>
<style type="text/css">
.flow-default {
    width: 98%;
    height: 450px;
    overflow: auto;
    font-size: 0;
    margin: 0 1%;

}
.flow-default li {
    display: inline-block;
    font-size: 14px;
    width: 25%;
    margin: 10px 0px;
    height: 200px;
    text-align: center;
}
.article-item{
    padding: 5px;
    border: 0px solid #ccc;
    box-sizing: content-box;
    height: 200px;
    display: block;
    position: relative;
    z-index: 1;
    overflow: hidden;
}
.article-item-img{
    width: 100%;
    height: 100%;
    display: block;
    background-size: 100%;
}
.article-item-img img {
    width: 100%;
    height: 100%;
    display: inline-block;
}
.article-item-title {
    position: absolute;
    bottom: 5px;
    color: #fff;
    font-size: 12px;
    background:rgba(0,0,0,0.5);
    width: 96%;
    height: 30px;
    line-height: 30px;
    padding: 0 15px;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}
.article-item-top-box{
    position: absolute;
    top: 5px;
    color: #fff;
    font-size: 12px;
    background:rgba(0,0,0,0.5);
    width: 96%;
    height: 40px;
    line-height: 40px;
    padding: 0 15px;
    overflow:hidden;
    display: none;
}
.article-item-top-box a {
    float: right;
    color: #fff;
    margin: 0;
}
.article-item-top-box a:hover {
    color: red;
}
.article-item:hover .article-item-top-box {
    display: block;
}
.article-item-top-box a:last-child {
    margin-right: 15px;
}
</style>
<script type="text/javascript">
layui.use('flow', function(){
    var flow = layui.flow;

    flow.load({
        elem: '#lay-flow-box' //流加载容器
        ,scrollElem: '#lay-flow-box' //滚动条所在元素，一般不用填，此处只是演示需要。
        ,done: function(page, next){ //执行下一页的回调
            $.get("{:createUrl('index')}",{ page: page },function(result){
                var lis = [];
                for(var i = 0; i < result.data.length; i++){
                    var str = '<div class="article-item">';
                    str += '<div class="article-item-top-box">';
                    str += '<a class="article-item-delete" href="'+result.data[i].remove+'">删除</a>';
                    str += '<a href="'+result.data[i].editor+'">编辑</a>';
                    str += '</div>';
                    str += '<div class="article-item-img"><img src="'+result.data[i]['list'][0].local_url+'"></div>';
                    str += '<div class="article-item-title">'+result.data[i]['list'][0].title+'</div>';
                    str += '</div>';
                    lis.push('<li>'+ str +'</li>')
                }

                if(lis.length <= 0 && page <= 1){
                    $(".layui-flow-more").hide();
                    $("#lay-flow-box").append('<div style="text-align: center;font-size: 14px;margin-top: 150px;">没有数据哦</div>');
                }

                //执行下一页渲染，第二参数为：满足“加载更多”的条件，即后面仍有分页
                //pages为Ajax返回的总页数，只有当前页小于总页数的情况下，才会继续出现加载更多
                next(lis.join(''), result.total == result.page - 1); //假设总页数为 10
            },"json");

        }
    });

    $(document).on("click",".article-item-delete",function (){
        var pt = $(this).parent().parent().parent();
        $.get($(this).attr("href"),function (result){
            if(result.code){
                pt.remove();
            }else{
                layer.msg(result.msg,{ icon : 2 });
            }
        },"json");

        return false;
    });
});
</script>



