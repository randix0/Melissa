{if $users}
    {foreach from=$users item=item}
        <tr data-id="{if isset($item.raw_id) && $item.raw_id}{$item.raw_id}{else}{$item.id}{/if}" class="admin_users_item {if isset($filter_type) && ($filter_type == 'stage' && !$item.top_ten_place)}error{/if}">
            <td>
                <div>
                    <a href="{$SITE_URI}admin/users/{$item.id}">
                        {$item.id}
                    </a>
                </div>
                <div>
                    <label class="checkbox">
                        <input type="checkbox" name="id[]" value="{$item.id}" />
                    </label>
                    <i class="icon-edit"></i>
                    <i class="icon-trash"></i>
                </div>
            </td>
            <td>
                <a href="{$SITE_URI}admin/users/{$item.id}">
                    <img src="/{$item.avatar_b}" alt=""/>
                </a>
            </td>
            <td><a href="{$SITE_URI}admin/users/{$item.id}">{$item.first_name} {$item.last_name}</a></td>
            <td>
                {if $item.facebook_id}<div><a href="http://facebook.com/{$item.facebook_id}">facebook</a></div>{/if}
                {if $item.vkontakte_id}<div><a href="http://vk.com/id{$item.vkontakte_id}">vkontakte</a></div>{/if}
                {if $item.google_id}<div><a href="http://plus.google.com/profile/{$item.google_id}">google+</a></div>{/if}
            </td>
            <td>{$item.email}</td>
            <td><a target="_blank" href="/admin/ip/{$item.ip}">{$item.ip}</a></td>
            <td>{$item.city}</td>
            <td>{$item.add_date|date_format:'%e/%m/%Y %H:%M:%S'}</td>
        </tr>
    {/foreach}
{/if}