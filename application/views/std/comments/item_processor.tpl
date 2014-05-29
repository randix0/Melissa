{if isset($comments) && $comments}
    {foreach from=$comments item=comment}
        <div class="b-comments-item">
            <div class="b-comments-item-author">{$comment.user_full_name}  {$comment.add_date|actionTime:'time':'b-comments-item-time timestamp'}</div>
            <div class="b-comments-item-author-avatar" style="background-image: url(/{$comment.user_avatar})"></div>
            <div class="b-comments-item-content">
                <div class="well">{$comment.idesc}</div>
            </div>
        </div>
    {/foreach}
{/if}