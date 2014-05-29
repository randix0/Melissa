<section id="video" class="s-section s-section__video" data-speed="30" data-type="background">
    {include file="std/nav/index.tpl" __PAGE="video"}

    <script src="{$RESOURCES_URL}js/video.js"></script>
    <script src="{$RESOURCES_URL}js/yt.video.js"></script>

    <video id="video_screen" class="video-js vjs-default-skin" controls preload="auto" width="100%" height="100%" data-setup='{ }'></video>

    {if $video && count($video) > 1}
    <div class="vjs-playlist" style="display: none;">
        {foreach from=$video item=item}
            <a class="vjs-playlist-item" data-type="video/youtube" data-src="http://www.youtube.com/embed/{$item.code}" data-poster="{$item.poster_b}" style="background-image: url('{if $item.poster_b}/{$item.poster_b}{else}http://img.youtube.com/vi/{$item.code}/0.jpg{/if}')">
                <div class="vjs-playlist-item-iname">{$item.iname}</div>
            </a>
        {/foreach}
    </div>
    {/if}

    <script>
        _V_("video_screen"{if $video}, { "techOrder":["youtube"], "src": "http://www.youtube.com/embed/{$video[0].code}", {if (isset($video[0].poster_b) && $video[0].poster_b)}"poster": '/{$video[0].poster_b}'{/if} }{/if}).ready(function(){
            var myPlayer = this;

            if ($('.vjs-playlist').length) {
                myPlayer.on("pause",function(){ $('.vjs-playlist').show(); });
                myPlayer.on("play",function(){ $('.vjs-playlist').hide(); });
            }

            $('.vjs-playlist-item').bind('click', function(){
                var videoSrc = $(this).attr('data-src');
                var videoType = $(this).attr('data-type');
                myPlayer.src({ "type": videoType, "src": videoSrc });
                myPlayer.play();
            });
        });

    </script>
</section>

<section id="video" class="s-section s-section__video__mobile" data-speed="30" data-type="background">
    <div class="s-section-stars" data-speed="10" data-type="background">
        <div class="s-section-label">Видео</div>
        {include file="std/nav/index.tpl" __PAGE="video"}
        <div class="s-content">
            <div class="s-content-body">
                {if isset($video) && $music}
                    {foreach from=$video item=item}
                        <a target="_blank" href="http://youtu.be/{$item.code}">
                            <img src="{$item.poster_b}" alt="{$item.iname}" style="width: 100%; max-width: 480px;"/>
                        </a>
                    {/foreach}
                {/if}
            </div>
        </div>
    </div>
</section>