<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;商品管理</a></li>
            <li><a href="javascript:;">编辑商品</a></li>
        </ul>
    </div>
</div>
   
<section class="content clearfix">
    <div class="layui-editor-box">
        <form action="" class="layui-form layui-form-pane">
            <div class="layui-tab layui-tab-brief layui-tab-bg layui-tab-content-box">
                <ul class="layui-tab-title">
                    <li class="layui-this">基本信息</li>
                    <li>商品相册</li>
                    <li>商品描述</li>
                    <li>商品规格</li>
                    <li>商品属性</li>
                </ul>

                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <div class="layui-form-item">
                            <label class="layui-form-label">商品分类</label>
                            <div class="layui-input-block">
                                <select name="cat_id" lay-verify="required" lay-reqtext="请选择分类">
                                    <option value="">请选择</option>
                                    {if !empty($cat)}
                                    {volist name="cat" id="value"}
                                    <option value="{$value.id}"{if isset($data.cat_id) && $value.id==$data.cat_id} selected{/if}>{$value.level|raw}{$value.title|raw}</option>
                                    {/volist}
                                    {/if}
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
                            <label class="layui-form-label">商品属性：</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="goods_extends[]" value="news" {if !empty($goods_extends) && in_array('news',$goods_extends)}checked{/if} title="新品" lay-skin="primary">
                                <input type="checkbox" name="goods_extends[]" value="hot" {if !empty($goods_extends) && in_array('hot',$goods_extends)}checked{/if} title="热销" lay-skin="primary">
                                <input type="checkbox" name="goods_extends[]" value="special" {if !empty($goods_extends) && in_array('special',$goods_extends)}checked{/if} title="特价" lay-skin="primary">
                                <input type="checkbox" name="goods_extends[]" value="recommend" {if !empty($goods_extends) && in_array('recommend',$goods_extends)}checked{/if} title="推荐" lay-skin="primary">
                            </div>
                        </div>
                        
                        <div class="layui-form-item">
                            <table class="layui-table">
                                <colgroup>
                                    <col>
                                    <col>
                                    <col>
                                    <col>
                                    <col>
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>销售价格</th>
                                        <th>市场价格</th>
                                        <th>成本价格</th>
                                        <th>重量(克)</th>
                                        <th>库存</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="product_sell_price" value="{$data.sell_price|default=''}" required  lay-verify="required" placeholder="请输入销售价格" autocomplete="off" class="layui-input"></td>
                                        <td><input type="text" name="product_market_price" value="{$data.market_price|default=''}" required  lay-verify="required" placeholder="请输入市场价格" autocomplete="off" class="layui-input"></td>
                                        <td><input type="text" name="product_cost_price" value="{$data.cost_price|default=''}" required  lay-verify="required" placeholder="请输入成本价格" autocomplete="off" class="layui-input"></td>
                                        <td><input type="text" name="product_weight" value="{$data.goods_weight|default=''}" required  lay-verify="required" placeholder="请输入重量(克)" autocomplete="off" class="layui-input"></td>
                                        <td><input type="text" name="product_store_nums" value="{$data.store_nums|default=''}" required  lay-verify="required" placeholder="请输入库存" autocomplete="off" class="layui-input"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="layui-form-item">
                            <table class="layui-table">
                                <colgroup>
                                    <col>
                                    <col>
                                    <col>
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>货号</th>
                                        <th>赠送积分</th>
                                        <th>赠送经验</th>
                                        <th>排序</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="goods_number" value="{$data.goods_number|default=''}" placeholder="请输入货号" autocomplete="off" class="layui-input"></td>
                                        <td><input type="text" name="point" value="{$data.point|default='0'}" placeholder="请输入赠送积分" autocomplete="off" class="layui-input"></td>
                                        <td><input type="text" name="exp" value="{$data.exp|default='0'}" placeholder="请输入赠送经验" autocomplete="off" class="layui-input"></td>
                                        <td><input type="text" name="sort" value="{$data.sort|default='0'}" placeholder="请输入排序" autocomplete="off" class="layui-input"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="layui-form-item">
                            <label class="layui-form-label">商品品牌</label>
                            <div class="layui-input-block">
                                <select name="brand_id">
                                    <option value="">请选择</option>
                                    {if !empty($brand)}
                                    {volist name="brand" id="value"}
                                    <option value="{$value.id}"{if isset($data.brand_id) && $value.id==$data.brand_id} selected{/if}>{$value.name}</option>
                                    {/volist}
                                    {/if}
                                </select>
                            </div>
                        </div>
                        
                        <div class="layui-form-item">
                            <label class="layui-form-label">配送方式</label>
                            <div class="layui-input-block">
                                <select name="delivery_id">
                                    {if !empty($distribution)}
                                    {volist name="distribution" id="value"}
                                    <option value="{$value.id}"{if isset($data.delivery_id) && $value.id==$data.delivery_id} selected{/if}>{$value.title}</option>
                                    {/volist}
                                    {/if}
                                </select>
                            </div>
                        </div>
                        
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">描述</label>
                            <div class="layui-input-block">
                                <textarea name="briefly" placeholder="请输入描述" class="layui-textarea">{$data.briefly|default=""}</textarea>
                            </div>
                        </div>
                        
                        <div class="layui-form-item">
                            <label class="layui-form-label">商品状态：</label>
                            <div class="layui-input-block">
                                <input type="radio" name="status" value="0" {if empty($data.status) || $data.status==0}checked{/if} title="上架">
                                <input type="radio" name="status" value="1" {if isset($data.status) && $data.status==1}checked{/if} title="下架">
                            </div>
                        </div>
                    </div>

                    <div class="layui-tab-item">
                        <div class="layui-form-item clearfix">
                            <div class="layui-upload clearfix">
                                <button type="button" class="layui-btn layui-bg-light-blue" id="uploadfiy-btn"><i class="layui-icon"></i>上传图片</button> 
                                <blockquote class="layui-elem-quote layui-quote-nm clearfix" style="margin-top: 10px;">
                                    预览图：
                                    <div class="layui-upload-list" id="uploadfiy-list-box">
                                        {if !empty($photo)}
                                        {volist name="photo" id="value"}
                                        <div class="uploadfiy-box">
                                            <input type="hidden" name="images[]" value="{$value.path}">
                                            <a class="upload-image"><img src="{$value.path}"></a>
                                            <div class="uploadfiy-button">
                                                <a href="javascript:;" class="n6-thumb{if isset($data.photo) && $data.photo == $value.path} active{/if}">封面</a>
                                                <a href="javascript:;"></a>
                                                <a href="javascript:;" class="n6-delete">删除</a>
                                            </div>
                                        </div>
                                        {/volist}
                                        {/if}
                                    </div>
                                </blockquote>
                            </div>
                        </div>
                    </div>

                    <div class="layui-tab-item">

                        <div class="layui-form-item">
                            <script id="container" style="width:100%;height: 500px;" name="content" type="text/plain">{$data.content|raw|default=""}</script>
                        </div>
                        
                    </div>
                    
                    <!-- 商品规格 -->
                    <div class="layui-tab-item">
                        <div class="layui-form-item">
                                <label class="layui-form-label">规格分类：</label>
                                <div class="layui-input-block">
                                    <select name="attr_id" lay-filter="attr-select">
                                        <option value="0">请选择</option>
                                        {if !empty($attribute)}
                                        {volist name="attribute" id="value"}
                                        <option value="{$value.id}" {if !empty($data.attr_id) && $data.attr_id == $value.id}selected{/if}>{$value.name}</option>
                                        {/volist}
                                        {/if}
                                    </select>
                                </div>
                            </div>
                            
                            <div id="spec-table-wrap"></div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">设置规格：</label>
                                <div class="layui-input-block">
                                    <div>
                                        <button type="button" class="layui-btn layui-submit-btn">确定</button>
                                    </div>
                                </div>
                            </div>
                            <div id="spec-data-table-wrap"></div>
                    </div>
                    
                    <!-- 商品属性 -->
                    <div class="layui-tab-item">
                        <div class="layui-form-item">
                                <label class="layui-form-label">选择模型：</label>
                                <div class="layui-input-block">
                                    <select lay-filter="module-select" name="model_id">
                                        <option value="">请选择</option>
                                        {if !empty($model)}
                                        {volist name="model" id="val"}
                                        <option value="{$val.id}" {if !empty($data.model_id) && $data.model_id == $val.id}selected{/if}>{$val.name}</option>
                                        {/volist}
                                        {/if}
                                    </select>
                                </div>
                            </div>
                            
                            <div class="layui-form-item" id="module-data-wrap"></div>
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input type="hidden" name="photo" value="{$data.photo|default=''}">
                        <input name="id" type="hidden" value="{$data.id|default='0'}">
                        <button class="layui-btn layui-bg-light-blue" lay-filter="layui-submit-filter" lay-submit="">立即提交</button>
                        <button class="layui-btn layui-btn-primary" type="reset">重置</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>
<div id="spec-set-attr-box">
    <table class="layui-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
        </colgroup>
        <thead>
            <tr>
                <th>销售价格</th>
                <th>市场价格</th>
                <th>成本价格</th>
                <th>重量(克)</th>
                <th>库存</th>
            </tr> 
        </thead>
        <tbody>
            <tr>
                <td><input type="text" name="set_sell_price" value="" placeholder="请输入销售价格" autocomplete="off" class="layui-input"></td>
                <td><input type="text" name="set_market_price" value="" placeholder="请输入市场价格" autocomplete="off" class="layui-input"></td>
                <td><input type="text" name="set_cost_price" value="" placeholder="请输入成本价格" autocomplete="off" class="layui-input"></td>
                <td><input type="text" name="set_goods_weight" value="" placeholder="请输入重量(克)" autocomplete="off" class="layui-input"></td>
                <td><input type="text" name="set_store_nums" value="" placeholder="请输入库存" autocomplete="off" class="layui-input"></td>
            </tr>
        </tbody>
    </table>
    <div class="layui-input-block" style="text-align:center;margin-left:0px!important">
        <button id="lay-set-attr-btn" class="layui-btn">立即提交</button>
        <button id="lay-clear-attr-btn" class="layui-btn layui-btn-primary">重置</button>
    </div>
</div>

<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="{__SYSTEM_PATH__}/js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
    function photo(array){
        for(var i = 0; i < array.length; i++){
            var string = '<div class="uploadfiy-box">';
            string += '<input type="hidden" name="images[]" value="'+array[i]+'">';
            string += '<a class="upload-image"><img src="'+array[i]+'"></a>';
            string += '<div class="uploadfiy-button">';
            string += '<a href="javascript:;" class="n6-thumb">封面</a>';
            string += '<a href="javascript:;"></a>';
            string += '<a href="javascript:;" class="n6-delete">删除</a>';
            string += '</div>';
            string += '</div>';
            $("#uploadfiy-list-box").append(string);
        }
        layui.layer.closeAll();
    }

    $(function () {
        setUEUpload("container",'{:createUrl("common.material/index",["type"=>"image","callback"=>"upload","module"=>"goods","method"=>"image"])}');

        function get_module(id){
            if(id != "" && id != undefined){
                $.get("{:createUrl('common.ajax/get_model')}",{
                    "id" : id,
                    "goods_id" : "{$data.id|default='0'}"
                },function (result){
                    if(result.code){
                        $("#module-data-wrap").show();
                        $("#module-data-wrap").html(result.data);
                        layui.form.render();
                    }else{
                        layer.msg(result.msg,{ icon : 2 });
                    }
                },"json");
            }
        }

        function get_childer_spec(id){
            $.get("{:createUrl('common.ajax/get_attr')}",{
                "id" : id,
                "goods_id" : "{$data.id|default='0'}"
            },function (result){
                if(result.code){
                    $("#spec-table-wrap").html(result.data);
                    layui.form.render();
                }else{
                    layer.msg(result.msg,{ icon : 2 });
                }
            },"json");
        }
        
        function get_childer_spec_data(flag){
            var arr = [];
            var t = flag || 0;
            $(".layui-btn-group-spec").each(function (){
                if(!$(".layui-btn-spec",this).is(".layui-btn-primary")){
                    arr.push($(this).find("input").val());
                }
            });

            $.post("{:createUrl('common.ajax/get_attr_data')}",{
                "id" : arr.join(","),
                "goods_id" : "{$data.id|default='0'}",
                "t" : t
            },function (result){
                layer.closeAll();
                if(result.code){
                    $("#spec-data-table-wrap").html(result.data);
                    $("#spec-data-table-wrap").show();
                    layui.form.render();
                }else{
                    layer.msg(result.msg,{ icon : 2 });
                }
            },"json");
        }
        
        $(document).on("click",".layui-set-btn-spec",function (){
            layer.open({
                type: 1,
                title: false,
                closeBtn: 1,
                shadeClose: true,
                skin: 'yourclass',
                area: ['800px', '200px'], //宽高
                content: $("#spec-set-attr-box")
            });

            return false;
        });
        
        $(document).on("click",".layui-btn-spec",function (){
            if($(this).is(".layui-btn-primary")){
                $(this).removeClass("layui-btn-primary");
            }else{
                $(this).addClass("layui-btn-primary");
            }

            return false;
        });
        
        $("#lay-set-attr-btn").on("click",function (){
            var set_sell_price = $.trim($('[name="set_sell_price"]').val());
            var set_market_price = $.trim($('[name="set_market_price"]').val());
            var set_cost_price = $.trim($('[name="set_cost_price"]').val());
            var set_goods_weight = $.trim($('[name="set_goods_weight"]').val());
            var set_store_nums = $.trim($('[name="set_store_nums"]').val());

            $('[name="sell_price[]"]').val(set_sell_price);
            $('[name="market_price[]"]').val(set_market_price);
            $('[name="cost_price[]"]').val(set_cost_price);
            $('[name="goods_weight[]"]').val(set_goods_weight);
            $('[name="store_nums[]"]').val(set_store_nums);
            layer.closeAll();
            return false;
        });
        
        $("#lay-clear-attr-btn").on("click",function (){
            $('[name="set_sell_price"]').val("");
            $('[name="set_market_price"]').val("");
            $('[name="set_cost_price"]').val("");
            $('[name="set_goods_weight"]').val("");
            $('[name="set_store_nums"]').val("");
            return false;
        });
        
        $(document).on("click",".layui-clear-btn-spec",function (){
            $('[name="sell_price[]"]').val("");
            $('[name="market_price[]"]').val("");
            $('[name="cost_price[]"]').val("");
            $('[name="goods_weight[]"]').val("");
            $('[name="store_nums[]"]').val("");
            return false;
        });
        
        $(document).on("click",".layui-submit-btn",function (){
            layer.load(1, {shade: [0.1, '#fff'], time: 0});
            get_childer_spec_data(1);
            return false;
        });

        layui.use(["form", "element",'layer','upload','colorpicker'], function () {
            var form = layui.form;
            var layer = layui.layer;
            var upload = layui.upload;

            var colorpicker = layui.colorpicker;

            colorpicker.render({
                elem: '#picker-box',color: '{$data.briefly_color|default="#333333"}',change: function(color){
                    if(color == ""){
                        $("[name='briefly_color']").val("");
                        $('[name="briefly"]').removeAttr("style");
                    }else{
                        $("[name='briefly_color']").val(color);
                        $('[name="briefly"]').css("color", color);
                    }
                }
            });

           {if !empty($data.id)}
                {if !empty($data.attr_id)}
                get_childer_spec('{$data.attr_id|default=""}');
                get_childer_spec_data();
                {/if}
                {if !empty($data.model_id)}
                get_module('{$data.model_id|default=""}');
                {/if}
           {/if}
           
            layui.form.on('select(attr-select)',function (data){
                var id = $.trim(data.value);
                if(id <= 0){
                    $("#spec-table-wrap").html("");
                    $("#spec-data-table-wrap").html("");
                    return false;
                }

                $("#spec-data-table-wrap").html("");
                get_childer_spec(id);

                return false;
            });
            
            layui.form.on('select(module-select)',function (data){
                var id = $.trim(data.value);
                get_module(id);
                return false;
            });
            
            //多图片上传
           $("#uploadfiy-btn").on("click",function (){
               layer.open({
                   type: 2,
                   title: '图库列表',
                   shadeClose: true,
                   shade: 0.3,
                   area: ['1100px', '600px'],
                   content: '{:createUrl("common.material/index",["type"=>"image","callback"=>"photo","module"=>"goods","method"=>"photo"])}'
               });
           });
            
            $(document).on("click",".n6-insert",function (){
               var pt = $(this).parent().parent();
               UE.getEditor('container').setContent('<p><img src="'+pt.find("img").attr("src")+'"></p>', true);
               return false;
           });
           
           $(document).on("click",".n6-thumb",function (){
               if($(this).is(".active")){
                   $(this).removeClass("active");
                   $('[name="photo"]').val("");
                   return false;
               }
               
               $(".n6-thumb").removeClass("active");
               $(this).addClass("active");
               var pt = $(this).parent().parent();
               $('[name="photo"]').val(pt.find("img").attr("src"));
                return false;
           });
            
           $(document).on("click",".n6-delete",function (){
               var pt = $(this).parent().parent();
               pt.remove();
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