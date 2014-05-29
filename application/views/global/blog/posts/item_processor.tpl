{if isset($posts) && $posts}
    {foreach from=$posts item=post}
        <div class="b-blog-article">
            <h1 class="b-blog-article-title">
                <a class="b-blog-article-title-link" href="/blog/post/{$post.id}/">{$post.iname}</a>
            </h1>
            {$post.add_date|actionTime:'time':'b-blog-article-time timestamp'}
            <div class="b-blog-article-idesc">
                {$post.idesc|strip_tags|truncate:300}
            </div>
            {if isset($post.tags) && $post.tags && is_array($post.tags)}
                <div class="b-blog-article-tags">
                    {foreach from=$post.tags item=tag}
                        {if $tag}
                        <a class="b-tag" href="/blog/tags/{$tag.uri}">
                            {$tag.iname}
                        </a>
                        {/if}
                    {/foreach}
                </div>
            {/if}
            <a href="#" class="comment">Коментарии ({$post.comments_count})</a>
        </div>
    {/foreach}
{/if}