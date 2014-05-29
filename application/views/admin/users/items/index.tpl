<div class="span3">
    <div class="well sidebar-nav">
        <ul class="nav nav-list">
            <li class="nav-header"><h3>Users</h3></li>
            <li class="{if $__SUBPAGE == 'all'}active{/if}"><a href="{$SITE_URI}admin/users/all">All</a></li>
            <li class="{if $__SUBPAGE == 'active'}active{/if}"><a href="{$SITE_URI}admin/users/active">Active</a></li>
            <li class="{if $__SUBPAGE == 'deleted'}active{/if}"><a href="{$SITE_URI}admin/users/deleted">Deleted</a></li>
            <li class="{if $__SUBPAGE == 'admin'}active{/if}"><a href="{$SITE_URI}admin/users/admin">ADMIN</a></li>
        </ul>
    </div><!--/.well -->
</div><!--/span-->

<div class="span9">
    <h1>Users</h1>
    <hr>

    <div>
        <form action="/admin/users" class="form-horizontal">
            <div class="control-group">
                <label class="control-label">author</label>
                <div class="controls">
                    <input type="text" class="input-small" name="item[id]" placeholder="user_id" value="{if isset($s.id) && $s.id}{$s.id}{/if}">
                    <input type="text" class="" name="item[first_name]" placeholder="Имя" value="{if isset($s.first_name) && $s.first_name}{$s.first_name}{/if}">
                    <input type="text" class="" name="item[last_name]" placeholder="Фамилия" value="{if isset($s.last_name) && $s.last_name}{$s.last_name}{/if}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">social</label>
                <div class="controls">
                    <input type="text" class="" name="item[facebook_id]" placeholder="facebook_id" value="{if isset($s.facebook_id) && $s.facebook_id}{$s.facebook_id}{/if}">
                    <input type="text" class="" name="item[vkontakte_id]" placeholder="vkontakte_id" value="{if isset($s.vkontakte_id) && $s.vkontakte_id}{$s.vkontakte_id}{/if}">
                    <input type="text" class="" name="item[odnoklassniki_id]" placeholder="odnoklassniki_id" value="{if isset($s.odnoklassniki_id) && $s.odnoklassniki_id}{$s.odnoklassniki_id}{/if}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">email</label>
                <div class="controls">
                    <input type="text" class="input-xxlarge" name="item[email]" placeholder="title" value="{if isset($s.email) && $s.email}{$s.email}{/if}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">ip</label>
                <div class="controls">
                    <input type="text" class="input-xxlarge" name="item[ip]" placeholder="127.0.0.1" value="{if isset($s.ip) && $s.ip}{$s.ip}{/if}">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">is_deleted</label>
                <div class="controls">
                    <select name="item[is_deleted]">
                        <option value="0">active</option>
                        <option {if isset($s.is_deleted) && $s.is_deleted}selected="selected"{/if} value="1">deleted</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <input type="submit" class="btn" value="Search" />
                </div>
            </div>

        </form>
    </div>

    <table class="table table-bordered" data-offset="">
        <tbody id="admin_users">
        <tr class="info">
            <td>user_id</td>
            <td>avatar</td>
            <td>author</td>
            <td>connected</td>
            <td>email</td>
            <td>ip</td>
            <td>city</td>
            <td>add_date</td>
        </tr>
        {include file="admin/users/items/items_proccessor.tpl"}
        </tbody>
    </table>
    <a class="btn" data-code="{$getItems_code}" data-order_by="{$order_by}" onclick="LoadMore.q(this);">load more</a>
    <script type="text/javascript">
        LoadMore = {
            start: 0,
            target: '#admin_users',
            code: null,
            order_by: null,
            q: function(el){
                LoadMore.start = $(LoadMore.target+' tr:last-child').attr('data-id');
                if (!LoadMore.code) LoadMore.code = $(el).attr('data-code');
                if (!LoadMore.order_by) LoadMore.order_by = $(el).attr('data-order_by');
                $.ajax({
                    url: '/ajax/getUsers/'+ LoadMore.code+'/'+ LoadMore.order_by + '/' + LoadMore.start,
                    type: 'POST',
                    dataType: 'html',
                    success: function(data){
                        if (data)
                            $(LoadMore.target).append(data);
                        else
                            $(el).hide();
                    }
                });
            }
        };
    </script>

</div>

{*
<script type="text/javascript">
    onScrollLoader.init('/ajax/getPhotosExt/{$getItems_code}/{$order_by}/', '#admin_photos .admin_photos_item');
</script>
*}