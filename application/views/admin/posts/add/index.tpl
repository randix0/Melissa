<div class="span3">
    <div class="well sidebar-nav">
        <ul class="nav nav-list">
            <li class="nav-header"><h3>Posts</h3></li>
        </ul>
    </div><!--/.well -->
</div><!--/span-->

<div class="span9">
    <h1>Add post</h1>
    <hr>

    <form id="ajaxSavePost" class="form-horizontal" action="/ajax/savePost">
        <input type="hidden" name="id" value="{$post.id}" />
        <input type="hidden" id="file_upload_path" name="file[upload_path]" value="" />
        {*
        <div class="control-group">
            <label class="control-label">Photo</label>
            <div class="controls">
                <div id="photos">
                    {if isset($post.photo_s) && $post.photo_s}
                        <img src="/{$post.photo_s}" alt=""/>
                    {/if}
                </div>
                <a id="file_preview" class="btn btn-info" onclick="Window.load('/modal/upload/news/-1','win-upload','');">Load</a>
            </div>
        </div>
        *}
        <div class="control-group">
            <label class="control-label">Title</label>
            <div class="controls">
                <input type="text" name="item[iname]" value="{$post.iname}" class="input-block-level"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Description</label>
            <div class="controls">
                <textarea name="item[idesc]" rows="15" class="wysihtml5 input-block-level">{$post.idesc}</textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Publish at</label>
            <div class="controls">
                <input type="date" name="item[add_date][date]" value="{$post.add_date|date_format:"%Y-%m-%d"}"/>
                <input type="time" name="item[add_date][time]" value="{$post.add_date|date_format:"%H:%M"}"/>
            </div>
        </div>


        <div class="control-group">
            <label class="control-label">
                <img src="{$RESOURCES_URL}img/misc/tags.png" alt=""/>
            </label>
            <div class="controls">
                {if $tags}
                    {if isset($tags.linked) && $tags.linked}
                        {foreach from=$tags.linked item=tag}
                            <span id="tags_links__{$tag.link_id}" class="a-tags-item">
                                {$tag.iname}
                                <a class="icon-trash" onclick="Ajax.del('tags_links', {$tag.link_id});"></a>
                            </span>
                        {/foreach}
                    {/if}
                    {if isset($tags.unlinked) && $tags.unlinked}
                        <div>
                        {foreach from=$tags.unlinked item=tag}
                            <label class="a-tags-item"><input type="checkbox" name="item[tags][]" value="{$tag.id}"/> {$tag.iname}</label>
                        {/foreach}
                        </div>
                    {/if}
                {/if}
                <div>
                    <h4>Введите теги через запятую:</h4>
                    <input type="text" name="item[new_tags]" placeholder="вдохновение, музыка вечна" class="input-block-level"/>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">Status</label>
            <div class="controls">
                <select name="item[is_deleted]">
                    <option value="0">published</option>
                    <option value="1" {if $post && isset($post.is_deleted) && $post.is_deleted}selected="selected"{/if}>deleted</option>
                </select>
            </div>
        </div>

        {*
        {if $post.id}
            <div><label>
                    <input type="radio" name="file[cover]" checked="checked" value="-1" /> не менять главную
                </label></div>
        {/if}
        *}

        <div class="control-group">
            <div class="controls">
                <a class="btn btn-large" data-form="#ajaxSavePost" data-action="/ajax/savePost" onclick="Ajax.save(this);">{l}SAVE{/l}</a>
            </div>
        </div>

    </form>
    <hr>

    {*
    <div class="form-horizontal">
        <div class="control-group">
            <label class="control-label">Photo</label>
            <div class="controls">
                <div id="photos">
                    {if isset($post.photos) && $post.photos}
                        {foreach from=$post.photos item=item}
                            <form id="ajaxSavePostsPhoto_{$item.id}" action="/ajax/savePostsPhoto">
                                <div id="product_photos_{$item.id}">
                                    <img class="left mB15 mR15" src="/{$item.photo_s}" alt=""/>
                                    <div><input type="hidden" name="id" value="{$item.id}"/></div>
                                    <div><input type="text" name="item[iname]" value="{$item.iname}"/></div>
                                    <div><input type="text" name="item[idesc]" value="{$item.idesc}"/></div>
                                </div>
                                <a class="btn btn-info" onclick="Posts.photoDefault(this);" data-target="products_photos" data-item_id="{$item.id}" data-news_id="{$post.id}">MAKE DEFAULT</a>
                                <a class="btn btn-info" data-form="#ajaxSavePostsPhoto_{$item.id}" data-action="/ajax/savePostsPhoto" onclick="Ajax.save(this);">SAVE</a>
                                <a onclick="Posts.del(this);" data-target="news_photos" data-item_id="{$item.id}" data-news_id="{$post.id}" class="btn btn-danger">delete</a>
                            </form>
                        {/foreach}
                    {/if}
                </div>
                <a id="file_preview" class="btn btn-info" onclick="Window.load('/modal/upload/news/-1','win-upload','');">Load</a>
            </div>
        </div>
    </div>
    *}


    {if $post && isset($post.comments) && $post.comments}
    <div>
        <h3>Comments ({$post.comments_count})</h3>
        <div class="form-horizontal">
            {foreach from=$post.comments item=comment}
                <div id="comments__{$comment.id}" class="control-group">
                    <div class="control-label" style="">
                        {$comment.user_full_name}
                        <div style="color: #ccc;">{$comment.add_date|actionTime:'time':'b-comments-item-time timestamp'}</div>
                    </div>
                    <div class="controls well">
                        <a class="btn btn-danger right" onclick="Ajax.del('comments', {$comment.id});">Удалить</a>
                        <div>{$comment.idesc}</div>
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
    {/if}

</div>


<script type="text/javascript">
    {literal}

    Posts = {
        del: function(el){
            //alert('target='+target+'target_id='+target_id+'product_id='+product_id);
            var target = $(el).attr('data-target'),
                    item_id = $(el).attr('data-item_id'),
                    news_id = $(el).attr('data-news_id');
            ajax_url = '/ajax/del/'+target;
            $.ajax({
                url: ajax_url,
                type: 'POST',
                data: 'item[item_id]='+item_id+'&item[parent_id]='+news_id,
                dataType: 'json',
                beforeSend: function(){
                },
                success: function(data){
                    if (data.status == 'success'){
                        $(el).addClass('btn-success');

                    } else {
                        $(el).addClass('btn-danger');
                        Window.load(SITE_URI+'modal/alertError/'+data.error,'win-alertError','');
                    }
                }
            });
        },
        photoDefault: function(el){
            var item_id = $(el).attr('data-item_id'),
                    news_id = $(el).attr('data-news_id');
            $.ajax({
                url: '/ajax/savePostsCover',
                type: 'POST',
                data: 'item[item_id]='+item_id+'&item[news_id]='+news_id,
                dataType: 'json',
                beforeSend: function(){
                },
                success: function(data){
                    if (data.status == 'success'){
                        $(el).addClass('btn-success');
                        if (typeof(data.goto) != 'undefined' && data.goto)
                            window.location.href = data.goto;
                    } else {
                        $(el).addClass('btn-danger');
                        Window.load(SITE_URI+'modal/alertError/'+data.error,'win-alertError','');
                    }
                }
            });
        }
    };

    {/literal}
</script>

<style type="text/css">
    .a-tags-item {
        display: inline-block;
        zoom: 1;
        vertical-align: middle;
        background-color: #cfe4ff;
        border-radius: 3px;
        padding: 5px 5px;
        margin: 0 5px 5px 0;
    }
</style>

<script type="text/javascript">
    {literal}
    $('.wysihtml5').wysihtml5({
        "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
        "emphasis": true, //Italics, bold, etc. Default true
        "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
        "html": true, //Button which allows you to edit the generated HTML. Default false
        "link": true, //Button to insert a link. Default true
        "image": true, //Button to insert an image. Default true,
        "color": false, //Button to change color of font

        parserRules: {
            classes: {
                "middle": 1
            },
            tags: {
                strong: {},
                em: {},
                p: {}
            }
        }
    });
    {/literal}
</script>