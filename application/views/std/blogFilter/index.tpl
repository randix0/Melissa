<div class="b-filter">
    <a class="b-filter-item {if isset($BLOG_FILTER) && $BLOG_FILTER == 'posts'}active{/if}" href="/blog/">{if isset($BLOG_FILTER) && $BLOG_FILTER == 'post'}&larr; вернуться к последним записям{else}Свежие записи{/if}</a>
    <a class="b-filter-item {if isset($BLOG_FILTER) && $BLOG_FILTER == 'tags'}active{/if}" href="/blog/tags/">Тэги</a>
</div>