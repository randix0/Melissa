<section id="blog" class="s-section s-section__blog">
    {include file="std/nav/blog.tpl" __PAGE="blog"}

    <div class="s-content">
        <div class="s-content-body s-content-body__folio">
            {include file="std/blogFilter/index.tpl"}
            {if isset($posts) && $posts}
                {include file="global/blog/posts/item_processor.tpl"}
            {else}
                <div class="b-blog-article">
                    <h1 class="b-blog-article-title">
                        <span class="b-blog-article-title-link">Здравствуйте!</span>
                    </h1>
                    <div class="article_idesc">
                        В блоге еще нет записей
                    </div>
                </div>
            {/if}
        </div>
    </div>
</section>