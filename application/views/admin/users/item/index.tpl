<div class="span3">
    <div class="well">
        <div class="">
            <div>Add date</div>
            <input type="text" disabled="disabled" class="input-block-level" id="input" placeholder="" value="{$user.add_date|date_format:'%e/%m/%Y %H:%M:%S'}">
        </div>
        <div class="">
            <div>IP</div>
            <input type="text" disabled="disabled" class="input-block-level" id="input" placeholder="" value="{$user.ip}">
        </div>
    </div><!--/.well -->
</div><!--/span-->

<div class="span9">
<h1>User</h1>
<hr>

<form id="ajaxSaveUser" class="form-horizontal" action="/ajax/saveUser">
    <div class="control-group">
        <label class="control-label">avatar</label>
        <div class="controls">
            <img src="/{$user.avatar_b}" alt=""/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">id</label>
        <div class="controls">
            <input type="hidden" name="id" value="{$user.id}">
            <input type="text" disabled="disabled" class="input-block-level" id="inputEmail" value="{$user.id}">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="input_iname">First name</label>
        <div class="controls">
            <input type="text" disabled="disabled" class="input-block-level" id="input_iname" name="item[first_name]" placeholder="" value="{$user.first_name}">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="input_iname">Last name</label>
        <div class="controls">
            <input type="text" disabled="disabled" class="input-block-level" id="input_iname" name="item[last_name]" placeholder="" value="{$user.last_name}">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="input_iname">Connected</label>
        <div class="controls">
            {if $user.facebook_id}<div><a href="http://facebook.com/{$user.facebook_id}">facebook</a></div>{/if}
            {if $user.vkontakte_id}<div><a href="http://vk.com/id{$user.vkontakte_id}">vkontakte</a></div>{/if}
            {if $user.google_id}<div><a href="http://plus.google.com/profile/{$user.google_id}">google</a></div>{/if}
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="inputPassword">Is deleted</label>
        <div class="controls">
            <select name="item[is_deleted]">
                <option value="0">active</option>
                <option value="1" {if $user.is_deleted}selected="selected"{/if}>deleted</option>
            </select>
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            <a class="btn btn-large" onclick="User.save(this);">{l}SAVE{/l}</a>
        </div>
    </div>


</form>
<hr>

    <script type="text/javascript">
        {literal}
        User = {
            save: function(el){
                $.ajax({
                    url: '/ajax/saveUser',
                    type: 'POST',
                    data: $('#ajaxSaveUser').serialize(),
                    dataType: 'json',
                    beforeSend: function(){
                        $(el).removeClass('btn-success').removeClass('btn-danger');
                    },
                    success: function(data){
                        if (data.status == 'success'){
                            //console.log('User.save: success!');
                            $(el).addClass('btn-success');
                        } else {
                            $(el).addClass('btn-danger');
                            //console.log('User.save: error!');
                            Window.load(SITE_URI+'modal/alertError/'+data.error,'win-alertError','');
                        }
                    }
                });
            }

        };
        {/literal}
    </script>