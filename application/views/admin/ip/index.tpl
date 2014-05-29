{if $data}
    <div class="span3">
    <div class="well">
        {foreach from=$data key=key item=item}
            <div class="">
                <div>{$key}</div>
                <input type="text" disabled="disabled" class="input-block-level" id="input" placeholder="" value="{$item}">
            </div>
        {/foreach}
    </div><!--/.well -->
</div><!--/span-->

    <div class="span9">
    <h1>IP: {$ip} ({$data.latitude}, {$data.longitude})</h1>
    <hr>
    <div id="map-canvas" style="height: 480px;"></div>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>
        var map;
        function initialize() {
            var myLatlng = new google.maps.LatLng({$data.latitude}, {$data.longitude});
            var mapOptions = {
                zoom: 12,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById('map-canvas'),
                    mapOptions);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title:"IP: {$ip}"
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script></div>
{/if}

