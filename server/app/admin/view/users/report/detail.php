<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;评论管理</a></li>
            <li><a href="javascript:;">举报详情</a></li>
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
                            <table class="layui-table">
                                    <colgroup>
                                        <col width="150">
                                        <col>
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th colspan="2">内容</th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        
                                        <tr>
                                            <td>会员：</td>
                                            <td>{$data.username}</td>
                                        </tr>
                                        <tr>
                                            <td>商品标题：</td>
                                            <td>{$data.goods_name}</td>
                                        </tr>
                                        <tr>
                                            <td>内容：</td>
                                            <td>{$data.content|strip_tags}</td>
                                        </tr>
                                        <tr>
                                            <td>创建时间：</td>
                                            <td>{$data.create_time|date="Y-m-d H:i:s"}</td>
                                        </tr>
                                        {if $data.status==1}
                                        <tr>
                                            <td>回复时间：</td>
                                            <td>{$data.reply_time|date="Y-m-d H:i:s"}</td>
                                        </tr>
                                        {/if}
                                        <tr>
                                            <td>状态：</td>
                                            <td>{if $data.status==1}已回复{else}未回复{/if}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="layui-form-item">
                                <script id="container" style="width:100%;height: 500px;" name="reply_content" type="text/plain">{$data.reply_content|raw|default=""}</script>
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

<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
    $(function () {
        var ue = UE.getEditor('container');
        layui.use(["form", "element",'layer'], function () {
            var form = layui.form;
            var layer = layui.layer;
            
            
            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("detail")}', data.field, function (result) {
                    layer.close(index);
                    if(result.code){
                        layer.msg(result.msg, {
                            time: 0
                            ,btn: ['继续编辑', '返回列表']
                            ,yes: function(index){
                                window.location.href = '{:createUrl("detail",["id"=>$data["id"]])}';
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