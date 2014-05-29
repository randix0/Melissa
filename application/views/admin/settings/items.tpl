{if $settings}
    <div class="b-settings">
    {foreach from=$settings item=item}
        <div class="b-settings-item">
            <form id="settings_{$item.code}" class="b-settings-item-wrap">
                <h3 class="b-settings-item-iname b-section-h3">{$item.iname}</h3>
                <code class="block mB10px">[{$item.code}]</code>
                <h4 class="b-settings-item-idesc b-section-h4">{$item.idesc}</h4>
                <input type="hidden" name="id" value="{$item.id}"/>
                <select name="item[type]" class="input-block-level">
                    <option value="var">var</option>
                    <option {if $item.type == 'date'}selected="selected"{/if} value="date">date</option>
                </select>
                <input type="hidden" name="code" value="{$item.code}"/>
                <div class="mB25px">
                    <input class="input-block-level" type="{if $item.type}{$item.type}{else}text{/if}" name="item[value]" value="{if $item.type == 'date' && $item.value}{$item.value|date_format:"%Y-%m-%d"}{else}{$item.value}{/if}">
                    {if $item.type == 'date'}
                        <input class="input-block-level" type="time" name="item[time]" value="{if $item.value}{$item.value|date_format:"%H:%M"}{/if}"/>
                    {/if}
                </div>
                <a class="btn" onclick="Settings.save('{$item.code}');">{l}SAVE{/l}</a>
            </form>
        </div>
    {/foreach}
    </div>
{/if}

<style type="text/css">
    .b-settings {
        overflow: hidden;
    }
    .b-settings-item {
        position: relative;
        display: inline-block;
        width: 216px;
        margin: 10px 17px 10px 17px;
        padding: 0;
        border: 1px solid #eeeeee;
        box-shadow: 0 1px 3px rgba(197,197,197,0.56);
    }
    .b-settings-item-wrap {
        margin: 10px 15px;
    }
</style>