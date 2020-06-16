{__NOLAYOUT__}
<table class="layui-table goods-table-box">
    <colgroup>
        <col width="100">
        <col>
    </colgroup>
    <tbody>
        <?php if(!empty($result)) : foreach($result as $item) : ?>
        <?php $checked = isset($goods_attr[$item["id"]]) ? $goods_attr[$item["id"]] : 0; ?>
        <?php if($item["type"] == 0) : ?>
        <tr>
            <th>
                <input type="hidden" name="attr_id_<?php echo $item["id"]; ?>" value="<?php echo $item["name"].':'.$item["value"]; ?>">
                <?php echo $item["name"]; ?>：
            </th>
            <td><?php echo $item["value"]; ?></td>
        </tr>
        <?php elseif($item["type"] == 4) : ?>
        <tr>
            <th><?php echo $item["name"]; ?>：</th>
            <td><input type="text" name="attr_id_<?php echo $item["id"]; ?>" value="<?php echo empty($checked) ? $item["value"] : $checked; ?>" placeholder="请输入内容" autocomplete="off" class="layui-input"></td>
        </tr>
        <?php elseif($item["type"] == 2) : ?>
        <tr>
            <th><?php echo $item["name"]; ?>：</th>
            <td>
                <?php $arr = explode(",",$item["value"]); ?>
                <?php if(!empty($arr)) : foreach($arr as $value) : ?>
                <input type="checkbox" name="attr_id_<?php echo $item["id"]; ?>[]" value="<?php echo $value; ?>" <?php if(!empty($checked) && in_array($value,explode(",",$checked))) { echo 'checked'; } ?> title="<?php echo $value; ?>" lay-skin="primary">
                <?php endforeach; endif; ?>
            </td>
        </tr>
        <?php elseif($item["type"] == 1) : ?>
        <tr>
            <th><?php echo $item["name"]; ?>：</th>
            <td>
                <?php $arr = explode(",",$item["value"]); ?>
                <?php if(!empty($arr)) : foreach($arr as $value) : ?>
                <input type="radio" name="attr_id_<?php echo $item["id"]; ?>" value="<?php echo $value; ?>" <?php if(!empty($checked) && $value == $checked) { echo 'checked'; } ?> title="<?php echo $value; ?>">
                <?php endforeach; endif; ?>
            </td>
        </tr>
        <?php elseif($item['type'] == 3) : ?>
        <tr>
            <th><?php echo $item["name"]; ?>：</th>
            <td>
                <select name="attr_id_<?php echo $item["id"] ?>">
                    <option value=""></option>
                    <?php $arr = explode(",",$item["value"]); ?>
                    <?php if(!empty($arr)) : foreach($arr as $value) : ?>
                    <option value="<?php echo $value; ?>" <?php if(!empty($checked) && $value == $checked) { echo 'selected="selected"'; } ?>><?php echo $value; ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </td>
        </tr>
        <?php endif; ?>
        <?php endforeach; endif; ?>
    </tbody>
</table>