{if isset($posts) && $posts}
    {foreach from=$posts item=item}
        <tr>
            <td>{$item.id}</td>
            <td>{$item.uri}</td>
            <td><a href="/admin/posts/edit/{$item.id}">{$item.iname}</a></td>
            <td>{$item.idesc|truncate:200}</td>
        </tr>
    {/foreach}
{/if}