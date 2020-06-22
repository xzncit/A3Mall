<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;营销管理</a></li>
            <li><a href="javascript:;">积分商品</a></li>
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
                            <label class="layui-form-label">名称：</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{$data.title|default=''}" required  lay-verify="required" placeholder="请输入名称" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">开始时间：</label>
                            <div class="layui-input-block">
                                <input id="start-time-box" type="text" name="start_time" value="{$data.start_time|default=''}" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">结束时间：</label>
                            <div class="layui-input-block">
                                <input id="end-time-box" type="text" name="end_time" value="{$data.end_time|default=''}" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">商品：</label>
                            <div class="layui-input-block">
                                <div class="layui-btn-group">
                                    <button class="layui-btn goods-btn"><i class="layui-icon">&#xe654;</i> 添加商品</button>
                                </div>
                                <table id="goods-table-box" style='width: 100%;' class="layui-table">
                                    <colgroup>
                                        <col>
                                        <col>
                                        <col>
                                        <col>
                                        <col>
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th style="text-align:center;">商品名称</th>
                                        <th style="text-align:center;">销售价格</th>
                                        <th style="text-align:center;">市场价格</th>
                                        <th style="text-align:center;">成本价格</th>
                                        <th style="text-align:center;">库存</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {if !empty($data.goods)}
                                    <tr>
                                        <td>{$data.goods.title|default=""}</td>
                                        <td>{$data.goods.sell_price|default=""}</td>
                                        <td>{$data.goods.market_price|default=""}</td>
                                        <td>{$data.goods.cost_price|default=""}</td>
                                        <td>{$data.goods.store_nums|default=""}</td>
                                    </tr>
                                    {/if}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="layui-form-item" id="product-table-box" {if !empty($data.products)}style="display:block;"{/if}>
                        <label class="layui-form-label">货品：</label>
                        <div class="layui-input-block">
                            <table style='width: 100%;' class="layui-table">
                                <colgroup>
                                    <col width="60">
                                    <col>
                                    <col>
                                    <col>
                                    <col>
                                    <col>
                                </colgroup>
                                <thead>
                                <tr>
                                    <th>选择</th>
                                    <th style="text-align:center;">规格</th>
                                    <th style="text-align:center;">销售价格</th>
                                    <th style="text-align:center;">市场价格</th>
                                    <th style="text-align:center;">成本价格</th>
                                    <th style="text-align:center;">库存</th>
                                </tr>
                                </thead>
                                <tbody>
                                {if !empty($data.products)}
                                {volist name="$data.products" id="val"}
                                <tr>
                                    <td><input type="checkbox" name="product_id[]" value="{$val.id}" lay-skin="primary" {if $val.checked}checked{/if}></td>
                                    <td>{$val.spec_item|default=""}</td>
                                    <td>{$val.sell_price|default=""}</td>
                                    <td>{$val.market_price|default=""}</td>
                                    <td>{$val.cost_price|default=""}</td>
                                    <td>{$val.store_nums|default=""}</td>
                                </tr>
                                {/volist}
                                {/if}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">商品属性：</label>
                        <div class="layui-input-block">
                            <table style='width: 100%;' class="layui-table">
                                <colgroup>
                                    <col>
                                    <col>
                                    <col>
                                    <col>
                                    <col>
                                </colgroup>
                                <thead>
                                <tr>
                                    <th style="text-align:center;">积分数量</th>
                                    <th style="text-align:center;">库存量</th>
                                    <th style="text-align:center;">最大购买量</th>
                                    <th style="text-align:center;">排序</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="text" name="price" value="{$data.price|default=''}" required  lay-verify="required" placeholder="请输入积分数量" autocomplete="off" class="layui-input"></td>
                                    <td><input type="text" name="store_nums" value="{$data.store_nums|default=''}" required  lay-verify="required" placeholder="请输入库存量" autocomplete="off" class="layui-input"></td>
                                    <td><input type="text" name="limit_max_count" value="{$data.limit_max_count|default=''}" required  lay-verify="required" placeholder="请输入最大购买量" autocomplete="off" class="layui-input"></td>
                                    <td><input type="text" name="sort" value="{$data.sort|default=''}" required  lay-verify="required" placeholder="请输入排序" autocomplete="off" class="layui-input"></td>
                                </tr>
                                </tbody>
                            </table>
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
                        <input name="goods_id" type="hidden" value="{$data.goods_id|default='0'}">
                        <button class="layui-btn layui-bg-light-blue" lay-filter="layui-submit-filter" lay-submit="">立即提交</button>
                        <button class="layui-btn layui-btn-primary" type="reset">重置</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>
<style type="text/css">#product-table-box { display: none; }</style>
<script type="text/javascript">
    var handleGoods = function (goods_id){
        $.get("{:createUrl('common.ajax/get_goods_data')}",{
            "id" : goods_id
        },function (result){
            if(result.code){
                var data = result.data;

                if($.trim($("#goods-table-box tbody").html()) == ""){
                    $("#goods-table-box tbody").append("<tr><td></td><td></td><td></td><td></td><td></td></tr>");
                }

                $("#goods-table-box tbody tr td:eq(0)").html(data.title);
                $("#goods-table-box tbody tr td:eq(1)").html(data.sell_price);
                $("#goods-table-box tbody tr td:eq(2)").html(data.market_price);
                $("#goods-table-box tbody tr td:eq(3)").html(data.cost_price);
                $("#goods-table-box tbody tr td:eq(4)").html(data.store_nums);
                $('[name="goods_id"]').val(data.id);

                if(data.item.length > 0){
                    $.each(data.item,function (i,obj){
                        var str = "";
                        str = '<tr>';
                        str += '<td>';
                        str += '<input type="checkbox" name="product_id[]" value="'+obj.id+'" lay-skin="primary" checked>';
                        str += '</td>';
                        str += '<td>'+obj.spec_item+'</td>';
                        str += '<td>'+obj.sell_price+'</td>';
                        str += '<td>'+obj.market_price+'</td>';
                        str += '<td>'+obj.cost_price+'</td>';
                        str += '<td>'+obj.store_nums+'</td>';
                        str += '</tr>';
                        $("#product-table-box table tbody").append(str);
                    });

                    $("#product-table-box").show();

                    layui.use(['form'], function () {
                        layui.form.render();
                    });
                }else{
                    $("#product-table-box table tbody").html(" ");
                    $("#product-table-box").hide();
                }
            }else{
                layer.msg(result.msg,{ icon : 2 });
            }
        },"json");
    };

    $(".goods-btn").on("click",function (){
        layer.open({
            type: 2,
            title: '查询商品',
            shadeClose: true,
            shade: 0.8,
            area: ['80%', '90%'],
            content: '{:createUrl("common.ajax/get_goods")}'
        });
        return false;
    });

    $(function () {

        layui.use(["form", "element",'layer','laydate'], function () {
            var form = layui.form;
            var layer = layui.layer;

            var laydate = layui.laydate;

            laydate.render({ elem: '#start-time-box',format: 'yyyy-MM-dd HH:mm:ss' });
            laydate.render({ elem: '#end-time-box',format: 'yyyy-MM-dd HH:mm:ss' });


            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("editor")}', data.field, function (result) {
                    layer.close(index);
                    if(result.code){
                        layer.msg(result.msg, {
                            time: 0
                            ,btn: ['继续发布', '返回列表']
                            ,yes: function(index){
                                window.location.href = '{:url("editor")}';
                            }
                            ,btn2: function (index, layero){
                                window.location.href = '{:url("index")}';
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