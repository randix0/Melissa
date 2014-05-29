{if isset($instagram) && $instagram && isset($instagram.data)}
{foreach from=$instagram.data item=item}
    {if $item}
        <a href="{$item.link}" target="_blank" class="b-photos-item">
            <img src="{$item.images.thumbnail.url}">
        </a>
    {/if}
{/foreach}
{/if}