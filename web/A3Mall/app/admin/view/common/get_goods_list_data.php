{__NOLAYOUT__}
{volist name="data" id="vo"}
<tr>
    <td>{$vo.title|default=""}</td>
    <td>{$vo.sell_price|default=""}</td>
    <td>{$vo.market_price|default=""}</td>
    <td>{$vo.cost_price|default=""}</td>
    <td>{$vo.store_nums|default=""}</td>
    <td><button type="button" class="layui-btn layui-btn-primary layui-btn-sm layui-button-remove"><i class="layui-icon layui-icon-delete"></i></button></td>
</tr>
{/volist}