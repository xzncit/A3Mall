<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;营销管理</a></li>
            <li><a href="javascript:;">订单活动</a></li>
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
                            <div class="">
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
                                    {if !empty($data["goods"])}
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

                        <div class="layui-form-item" id="product-table-box" {if !empty($data.products)}style="display:block;"{/if} ?>

                        <div class="">
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
                                {volist name="data.products" id="val"}
                                <tr>
                                    <td><input type="radio" name="product_id" value="{$val.id}" {if $val.checked}checked{/if}></td>
                                    <td>{$val.spec_item}</td>
                                    <td>{$val.sell_price}</td>
                                    <td>{$val.market_price}</td>
                                    <td>{$val.cost_price}</td>
                                    <td>{$val.store_nums}</td>
                                </tr>
                                {/volist}
                                {/if}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="">
                            <table id="users-table-box" style="width:100%" class="layui-table">
                                <colgroup>
                                    <col>
                                    <col>
                                    <col>
                                    <col>
                                </colgroup>
                                <thead>
                                <tr>
                                    <th style="text-align:center;">会员等级</th>
                                    <th style="text-align:center;">优惠价格（如果注册会员需要优惠10元，则填写10）</th>
                                    <th style="text-align:center;">默认价格</th>
                                    <th style="text-align:center;">会员折扣</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(!empty($group)) : foreach($group as $val) : ?>
                                <tr>
                                    <td style="text-align:right;"><?php echo $val["name"]; ?>：</td>
                                    <td><input type="text" name="price[<?php echo $val["id"]; ?>]" value="<?php echo isset($data["item"][$val["id"]]) ? $data["item"][$val["id"]] : ""; ?>" required  lay-verify="required" placeholder="请输入价格" autocomplete="off" class="layui-input"></td>
                                    <td data-price="<?php echo $val['discount']/100; ?>"><?php echo !empty($data["goods"]["sell_price"]) ? "￥".number_format(($val['discount']/100)*$data["goods"]["sell_price"],2) : ""; ?></td>
                                    <td><?php echo $val["discount"]; ?>%</td>
                                </tr>
                                <?php endforeach; endif; ?>
                                </tbody>
                            </table>
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
        $.get("{:url('common.ajax/get_goods_data')}",{
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
                $('#users-table-box tbody tr').each(function (){
                    var obj = $('td:eq(2)',this);
                    var price = obj.attr("data-price");
                    obj.html("￥"+(data.sell_price * price));
                });

                if(data.item.length > 0){
                    $.each(data.item,function (i,obj){
                        var str = "";
                        str = '<tr>';
                        str += '<td>';
                        str += '<input type="radio" name="product_id" value="'+obj.id+'"  '+(i==0 ? "checked" : "")+'>';
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