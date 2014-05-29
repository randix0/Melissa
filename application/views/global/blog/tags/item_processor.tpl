{if isset($tags) && $tags}
    {foreach from=$tags item=tag}
        <a href="/blog/tags/{$tag.uri}" class="b-tag {if isset($current_tag) && $current_tag && $current_tag.id == $tag.id}active{/if}">{$tag.iname}</a>
    {/foreach}
{/if}