{if $LOGGED}
    <form id="ajaxSaveComment" class="b-comments-add" action="/ajax/saveComment">
        <input type="hidden" name="item[object_type]" value="{if isset($object_type) && $object_type}{$object_type}{else}1{/if}"/>
        <input type="hidden" name="item[object_id]" value="{if isset($object_id) && $object_id}{$object_id}{else}0{/if}"/>
        <textarea name="item[idesc]" class="input-block-level"></textarea>
        <a class="btn btn-large right" data-form="#ajaxSaveComment" onclick="Ajax.save(this);">Добавить</a>
        <div class="clearfix"></div>
    </form>
{else}
    <a onclick="Window.load('/modal/login','win-login','')">Login</a>
{/if}

<script type="text/javascript">
    $(document).ready(function(){
        Ajax.callback = function(data){
            $('.b-comments-items').append(data.html);
        }
    });
</script>