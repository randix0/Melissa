{if isset($type) && $type == 'music' && isset($music) && $music}
    {foreach from=$music item=item}
        <tr id="music__{$item.id}" data-id="{if isset($item.raw_id) && $item.raw_id}{$item.raw_id}{else}{$item.id}{/if}" class="admin_music_item">
            <td>
                <div>{$item.id}</div>
                <div>
                    {*<a class="icon-edit"></a>*}
                    <a class="icon-trash" onclick="Ajax.del('music', {$item.id});"></a>
                </div>
            </td>
            <td>
                <a href="{$item.link}">{$item.link}</a>
            </td>
            <td>{$item.iname}</td>
            <td>{$item.idesc|truncate:80}</td>
            <td>{$item.position}</td>
            <td>{$item.add_date|date_format:'%e/%m/%Y %H:%M:%S'}</td>
            <td>{$item.is_deleted}</td>
        </tr>
    {/foreach}
{/if}

{if isset($type) && $type == 'video' && isset($video) && $video}
    {foreach from=$video item=item}
        <tr id="video__{$item.id}" data-id="{if isset($item.raw_id) && $item.raw_id}{$item.raw_id}{else}{$item.id}{/if}" class="admin_music_item">
            <td>
                <div>{$item.id}</div>
                <div>
                    <a class="icon-trash" onclick="Ajax.del('video', {$item.id});"></a>
                </div>
            </td>
            <td>
                <a target="_blank" href="http://youtube.com/watch?v={$item.code}">http://youtube.com/watch?v={$item.code}</a>
            </td>
            <td>{$item.iname}</td>
            <td>{$item.idesc|truncate:80}</td>
            <td>{$item.poster_s}</td>
            <td>{$item.position}</td>
            <td>{$item.add_date|date_format:'%e/%m/%Y %H:%M:%S'}</td>
            <td>{$item.is_deleted}</td>
        </tr>
    {/foreach}
{/if}