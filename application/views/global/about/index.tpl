<section id="about" class="s-section s-section__about" data-speed="30" data-type="background">
    <div class="s-section-stars" data-speed="10" data-type="background">
        <div class="s-section-label">Биография</div>
        {include file="std/nav/index.tpl" __PAGE="about"}
        <div class="s-content">
            <div class="s-content-body s-content-body__folio">
                <h1>Начало</h1>
                {if isset($about) && $about}
                    {$about.idesc}
                {else}
                    {include file="global/about/content.tpl"}
                {/if}
            </div>
        </div>
    </div>
</section>