{__NOLAYOUT__}
<div class="layui-form-item">
    <label class="layui-form-label">设置规格：</label>
    <div class="layui-input-block">
        <div>
            <button class="layui-btn layui-set-btn-spec">设置商品属性</button>
            <button class="layui-btn layui-clear-btn-spec layui-btn-primary">清空商品属性</button>
        </div>
    </div>
</div>
<table class="layui-table">
    <colgroup>
        {if !empty($head)}
        {volist name="head" id="val"}
        <col>
        {/volist}
        {/if}
        <col width="120">
        <col width="120">
        <col width="120">
        <col width="120">
        <col width="120">
    </colgroup>
    <thead>
        <tr>
            {if !empty($head)}
            {volist name="head" id="val"}
            <th>{$val.name}</th>
            {/volist}
            {/if}
            <th>销售价格</th>
            <th>市场价格</th>
            <th>成本价格</th>
            <th>重量(克)</th>
            <th>库存</th>
        </tr> 
    </thead>
    <tbody>
        <?php if(!empty($data)) : foreach($data as $val) : $spec_key = $spec_data = ""; ?>
        <tr>
            <?php foreach($val as $item) : $param = explode(";;;",$item); ?>
            <td>
                <?php 
                $spec_key .= $param[2] . ':' . $param[3].','; 
                $spec_data .= $param[0] . ':' . $param[1].',';
                echo $param[1];
                ?>
            </td>
            <?php endforeach; ?>
            <?php $spec_key = trim($spec_key,','); ?>
            
            <td>
                <input type="hidden" name="spec_list_key[]" value="<?php echo $spec_key; ?>">
                <input type="hidden" name="spec_list_data[]" value="<?php echo trim($spec_data,','); ?>">
                <input type="text" name="sell_price[]" value="<?php echo isset($goods[$spec_key]['sell_price']) ? $goods[$spec_key]['sell_price'] :''; ?>" placeholder="销售价格" autocomplete="off" class="layui-input">
            </td>
            <td>
                <input type="text" name="market_price[]" value="<?php echo isset($goods[$spec_key]['market_price']) ? $goods[$spec_key]['market_price'] : ''; ?>" placeholder="市场价格" autocomplete="off" class="layui-input">
            </td>
            <td>
                <input type="text" name="cost_price[]" value="<?php echo isset($goods[$spec_key]['cost_price']) ? $goods[$spec_key]['cost_price'] : ''; ?>" placeholder="成本价格" autocomplete="off" class="layui-input">
            </td>
            <td>
                <input type="text" name="goods_weight[]" value="<?php echo isset($goods[$spec_key]['goods_weight']) ? $goods[$spec_key]['goods_weight'] : ''; ?>" placeholder="重量(克)" autocomplete="off" class="layui-input">
            </td>
            <td>
                <input type="text" name="store_nums[]" value="<?php echo isset($goods[$spec_key]['store_nums']) ? $goods[$spec_key]['store_nums'] : ''; ?>" placeholder="库存" autocomplete="off" class="layui-input">
            </td>
        </tr>
        <?php endforeach; endif; ?>
    </tbody>
</table>
