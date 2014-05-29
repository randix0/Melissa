<div class="span3">
    <div class="well sidebar-nav">
        <ul class="nav nav-list">
            <li class="nav-header"><h3>Pages</h3></li>
        </ul>
    </div><!--/.well -->
</div><!--/span-->

<div class="span9">
    {if $pages}
        {foreach from=$pages item=page}
            <h1>{$page.iname}</h1>
            <hr>
            <form id="ajaxSavePage__{$page.code}" class="form-horizontal" action="/ajax/savePage">
                <input type="hidden" name="id" value="{$page.id}" />
                <input type="hidden" id="file_upload_path" name="file[upload_path]" value="" />

                <div class="control-group">
                    <label class="control-label">Description</label>
                    <div class="controls">
                        <textarea name="item[idesc]" rows="15" class="wysihtml5 input-block-level">{$page.idesc}</textarea>
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
                <div class="control-group">
                    <div class="controls">
                        <a class="btn btn-large" data-form="#ajaxSavePage__{$page.code}" data-action="/ajax/savePage" onclick="Ajax.save(this);">{l}SAVE{/l}</a>
                    </div>
                </div>
            </form>
        {/foreach}
    {/if}
</div>

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