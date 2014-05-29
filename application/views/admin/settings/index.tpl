<div class="span3">
    <div class="well sidebar-nav">
        <ul class="nav nav-list">
            <li class="nav-header"><h3>SETTINGS</h3></li>
        </ul>
    </div><!--/.well -->
</div><!--/span-->

<div class="span9">
    {include file="admin/settings/items.tpl"}
</div>


<script type="text/javascript">
    $(document).ready(function(){
        $('.b-settings').masonry({
            itemSelector: '.b-settings-item',
            singleMode: true,
            isResizable: false
            //isAnimated: !Modernizr.csstransitions
        });
    });

    Settings = {
        save: function(code){
            $.ajax({
                url: '/ajax/saveSettings',
                type: 'POST',
                data: $('#settings_'+code).serialize(),
                dataType: 'json',
                beforeSend: function(){
                    $('#settings_'+code+' .btn').removeClass('btn-danger').removeClass('btn-success');
                },
                success: function(data){
                    if (data.status == 'success'){
                        //console.log('Idea.save: success!');
                        $('#settings_'+code+' .btn').addClass('btn-success');
                        if (data.goto)
                            window.location = data.goto;
                    } else {
                        $('#settings_'+code+' .btn').addClass('btn-danger');
                        //console.log('Idea.save: error!');
                    }
                }
            });
        }
    };
</script>