<section id="blog" class="s-section s-section__blog">
    {include file="std/nav/blog.tpl" __PAGE="blog"}

    <div class="s-content">
        <div class="s-content-body s-content-body__folio">
            {include file="std/blogFilter/index.tpl"}
            {if isset($tags) && $tags}
                Теги на сайте: {include file="global/blog/tags/item_processor.tpl"}

                {if isset($current_tag) && $current_tag}
                    <div style="margin: 20px 0;">
                        Поиск по тегу: <strong>{$current_tag.iname}</strong>
                    </div>
                {/if}

                {if isset($posts) && $posts}
                    {include file="global/blog/posts/item_processor.tpl"}
                {/if}

            {else}
                <div class="b-blog-article">
                    <h1 class="b-blog-article-title">
                        <span class="b-blog-article-title-link">Здравствуйте!</span>
                    </h1>
                    <div class="article_idesc">
                        В блоге еще нет тегов
                    </div>
                </div>
            {/if}
        </div>
    </div>
</section>