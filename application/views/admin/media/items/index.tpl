<div class="span3">
    <div class="well sidebar-nav">
        <ul class="nav nav-list">
            <li class="nav-header"><h3>Music/Video</h3></li>
        </ul>
    </div><!--/.well -->
</div><!--/span-->

<div class="span9">
    <div class="mB30">
        <form class="left mR30" method="post" action="/ajax/saveMedia/">
            <input type="hidden" name="item[type]" value="music"/>
            <h3>Add music</h3>
            <div><input type="text" name="item[iname]" placeholder="title"/></div>
            <div><input type="text" name="item[link]" placeholder="soundcloud-link"/></div>
            <div><textarea name="item[idesc]" rows="5" placeholder="description"></textarea></div>
            <div><input type="submit" class="btn"/></div>
        </form>
        <form class="left" method="post" action="/ajax/saveMedia/">
            <input type="hidden" name="item[type]" value="video"/>
            <h3>Add video</h3>
            <div><input type="text" name="item[iname]" placeholder="title"/></div>
            <div><input type="text" name="item[link]" placeholder="youtube-code"/></div>
            <div><textarea name="item[idesc]" rows="5" placeholder="description"></textarea></div>
            <input type="hidden" id="file_upload_path" name="file[upload_path]" value="" />

            <div id="video_photos"></div>
            <a id="file_preview" class="btn btn-info" onclick="Window.load('/modal/upload/video/-1/0','win-upload','');">Load</a>
            <input type="submit" class="btn"/>
        </form>
        <div class="clearfix"></div>
    </div>

    <table class="table table-bordered" data-offset="">
        <tbody id="admin_users">
        <tr class="info">
            <td>id</td>
            <td>link</td>
            <td>iname</td>
            <td>idesc</td>
            <td>position</td>
            <td>add_date</td>
            <td>is_deleted</td>
        </tr>
        {include file="admin/media/items/items_proccessor.tpl" type='music'}
        </tbody>
    </table>

    <table class="table table-bordered" data-offset="">
        <tbody id="admin_users">
        <tr class="info">
            <td>id</td>
            <td>code</td>
            <td>iname</td>
            <td>idesc</td>
            <td>poster</td>
            <td>position</td>
            <td>add_date</td>
            <td>is_deleted</td>
        </tr>
        {include file="admin/media/items/items_proccessor.tpl" type='video'}
        </tbody>
    </table>

    {*<a class="btn" data-code="{$getItems_code}" data-order_by="{$order_by}" onclick="LoadMore.q(this);">load more</a>
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
    *}

</div>