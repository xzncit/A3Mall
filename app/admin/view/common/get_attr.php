{__NOLAYOUT__}
<table class="layui-table goods-table-box">
    <colgroup>
        <col width="150">
        <col>
    </colgroup>
    <tbody>
        {if !empty($result)}
        {volist name="result" id="val"}
        <tr>
            <th>{$val.data.name}</th>
            <td>
                {if !empty($val.item)}
                {volist name="val['item']" id="item"}
                <div class="layui-btn-group layui-btn-group-spec">
                    <button class="layui-btn {if !in_array($item.id,$spec_checked)}layui-btn-primary{/if} layui-btn-spec layui-btn-sm">{$item.value}</button>
                    <input type="hidden" name="spec_item[]" value="{$item.id}">
                </div>
                {/volist}
                {/if}
            </td>
        </tr>
        {/volist}
        {/if}
    </tbody>
</table>