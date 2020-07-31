<div class="row content-nav">
    <div class="col-xs-12">
        <ul>
            <li><a href="javascript:;"><i></i>&nbsp;商品管理</a></li>
            <li><a href="javascript:;">秒杀活动</a></li>
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
                                        <td>{$data.goods.title|default=''}</td>
                                        <td>{$data.goods.sell_price|default=''}</td>
                                        <td>{$data.goods.market_price|default=''}</td>
                                        <td>{$data.goods.cost_price|default=''}</td>
                                        <td>{$data.goods.store_nums|default=''}</td>
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
                                {volist name="data['products']" id="val"}
                                <tr>
                                    <td><input type="checkbox" name="product_id[]" value="{$val.id}" lay-skin="primary" {if isset($val.checked)&&$val.checked}checked{/if}></td>
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
                                    <th style="text-align:center;">秒杀价格</th>
                                    <th style="text-align:center;">销量</th>
                                    <th style="text-align:center;">库存</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="text" name="sell_price" value="{$data.sell_price|default=$data.goods.sell_price}" required  lay-verify="required" placeholder="请输入拼团价格" autocomplete="off" class="layui-input"></td>
                                    <td><input type="text" name="sum_count" value="{$data.sum_count|default=mt_rand(1,1000)}" required  lay-verify="required" placeholder="请输入销量" autocomplete="off" class="layui-input"></td>
                                    <td><input type="text" name="store_nums" value="{$data.store_nums|default=$data.goods.store_nums}" required  lay-verify="required" placeholder="请输入库存" autocomplete="off" class="layui-input"></td>
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
    $(function () {

        layui.use(["form", "element",'layer','laydate'], function () {
            var form = layui.form;
            var layer = layui.layer;

            var laydate = layui.laydate;

            laydate.render({ elem: '#start-time-box',format: 'yyyy-MM-dd HH:mm:ss' });
            laydate.render({ elem: '#end-time-box',format: 'yyyy-MM-dd HH:mm:ss' });


            form.on('submit(layui-submit-filter)', function (data) {
                var index = layer.load(1, { shade: [0.2,'#fff'] });
                $.post('{:createUrl("editor_second")}', data.field, function (result) {
                    layer.close(index);
                    if(result.code){
                        layer.msg(result.msg, {
                            time: 0
                            ,btn: ['返回列表']
                            ,yes: function(index){
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