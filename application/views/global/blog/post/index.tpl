<section id="blog" class="s-section s-section__blog">
    {include file="std/nav/blog.tpl" __PAGE="blog"}

    <div class="s-content">
        <div class="s-content-body s-content-body__folio">
            {include file="std/blogFilter/index.tpl"}

            {if isset($post) && $post}
            <div class="b-blog-article">
                <h1 class="b-blog-article-title">
                    <a class="b-blog-article-title-link" href="/blog/post/{$post.id}/">{$post.iname}</a>
                </h1>
                {$post.add_date|actionTime:'time':'b-blog-article-time timestamp'}
                <div class="b-blog-article-idesc">
                    {$post.idesc|truncate:300}
                </div>
                {*if isset($post.tags) && $post.tags}
                    {counter name="post_tags" assign="t" start=0}
                    <div class="tags">
                        {foreach from=$post.tags item=tag}
                            <a href="#">{if $t > 0},{/if}{$tag.tag}</a>
                        {/foreach}
                    </div>
                {/if*}
                <div class="b-blog-article-tags">
                    {if $post.tags}
                        {foreach from=$post.tags item=tag}
                            <a class="b-tag" href="/blog/tags/{$tag.uri}">
                                {$tag.iname}
                            </a>
                        {/foreach}
                    {/if}
                </div>
                <div class="b-comments-number">Коментарии ({$post.comments_count})</div>
                {include file="std/comments/index.tpl" object_type=1 object_id=$post.id comments=$post.comments}
            </div>
            {/if}
        </div>
    </div>


</section>