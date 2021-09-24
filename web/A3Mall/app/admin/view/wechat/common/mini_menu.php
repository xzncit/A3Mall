<ul class="nav nav-pills nav-stacked">
    {volist name="sidebar['menu']" id="sidebar"}
        {volist name="sidebar['children']" id="children"}
            {if $children.active}
                {volist name="children['children']" id="value"}
                <li{if $value.active} class="active"{/if}><a href="{$value.url}">{$value.name}</a></li>
                {/volist}
            {/if}
        {/volist}
    {/volist}
</ul>