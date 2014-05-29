<section id="music" class="s-section s-section__music" data-speed="30" data-type="background">
    <div class="s-section-stars" data-speed="10" data-type="background">
        <div class="s-section-label">Музыка</div>
        {include file="std/nav/index.tpl" __PAGE="music"}
        <div class="s-content">
            <div class="s-content-body">
                {if isset($music) && $music}
                    {foreach from=$music item=item}
                        <a href="{$item.link}" class="sc-player">{$item.iname}</a
                    {/foreach}
                {/if}
            </div>
        </div>
    </div>
</section>