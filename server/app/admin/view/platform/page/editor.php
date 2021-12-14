<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;单页管理</a></li>
            <li><a href="javascript:;">编辑单页</a></li>
        </ul>
    </div>
</div>

<section class="content clearfix">
    <div class="layui-editor-box">
        <form action="" class="layui-form layui-form-pane">
            <div class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
                <ul class="layui-tab-title">
                    <li class="layui-this">基本信息</li>
                </ul>

                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <div class="layui-form-item">
                            <label class="layui-form-label">选择分类</label>
                            <div class="layui-input-block">
                                <select lay-verify="required" name="pid">
                                    <option value="0">顶级分类</option>
                                    {volist name="cat" id="v"}
                                    <option value="{$v.id}" {if !empty($data.pid) && $v.id == $data.pid}selected{/if}>{$v.level|raw}{$v.title|raw}</option>
                                    {/volist}
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{$data.title|default=''}" lay-reqtext="请填写名称" lay-verify="required" placeholder="请输入名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">封面</label>
                            <div class="layui-input-block">
                                <button type="button" class="layui-btn" id="uploadfiy">
                                    <i class="layui-icon">&#xe67c;</i>上传图片
                                </button>
                                <img id="uploadfiy-image" src="{if !empty($data.photo)}{$data.photo}{else}{__STATIC_PATH__}/images/default.jpg{/if}" width="50" height="38">
                                <input type="hidden" name="photo" value="{$data.photo|default=''}">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <script id="container" style="width:100%;height: 500px;" name="content" type="text/plain">{$data.content|raw|default=""}</script>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">排序</label>
                            <div class="layui-input-block">
                                <input type="text" name="sort" value="{$data.sort|default='0'}" placeholder="请输入排序" autocomplete="off" class="layui-input">
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

            </div>
        </form>
    </div>
</section>

<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
    function photo(array){
        $("#uploadfiy-image").attr("src",array[0]);
        $('[name="photo"]').val(array[0]);
        layui.layer.closeAll();
    }
    $(function () {
        setUEUpload("container",'{:createUrl("common.material/index",["type"=>"image","callback"=>"upload","module"=>"page","method"=>"image"])}');
        layui.use(["form", "element",'layer','upload'], function () {
            var form = layui.form;
            var layer = layui.layer;
            var upload = layui.upload;

            $("#uploadfiy").on("click",function (){
                layer.open({
                    type: 2,
                    title: '图库列表',
                    shadeClose: true,
                    shade: 0.3,
                    area: ['1100px', '600px'],
                    content: '{:createUrl("common.material/index",["type"=>"image","callback"=>"photo","module"=>"page","method"=>""])}'
                });
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