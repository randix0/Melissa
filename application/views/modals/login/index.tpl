<h3 class="b-section-h3">Sign-in with</h3>
<a class="b-login-btn fb mR10px" onclick="Auth.facebook({if isset($reason) && $reason}'{$reason}'{/if});">facebook</a>
<a class="b-login-btn vk mR10px" onclick="Auth.vkontakte({if isset($reason) && $reason}'{$reason}'{/if});">vkontakte</a>
<a class="b-login-btn gp mR10px" onclick="Auth.google({if isset($reason) && $reason}'{$reason}'{/if});">google</a>
{*<a class="b-login-btn tw" onclick="Auth.twitter({if isset($reason) && $reason}'{$reason}'{/if});"></a>*}