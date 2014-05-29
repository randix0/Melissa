<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$AUTO_CONF=array(
    '/var/www/melissa.lo/'				                                => 'bsd',
    '/home/ukrai531/domains/melissaband.com.ua/public_html/test/'   => 'test', //Release Candidate
    '/home/ukrai531/domains/melissaband.com.ua/public_html/'        => 'live',
// 'func'				=> 'conf_check_devel',
);


$conf_name='live';


foreach($AUTO_CONF as $k => $v){


    if(preg_match('@^'.preg_quote($k).'@', dirname(__FILE__)) &&  is_dir($k)) {
        $conf_name=$v;
        //$test_server=1;
        break;
    }

}



$config['config_profile']	= $conf_name;


include_once(dirname(__FILE__).'/config.'.$conf_name.'.php');

$config['access_levels'] = array(
    'ACL_EDIT' => 50,
    'ACL_ADMIN' => 80
);
$config['full_account_creation_needed'] = false;
$config['account_email_needed'] = true;

$config['index_page'] = '';
$config['uri_protocol']	= 'AUTO';
$config['url_suffix'] = '';
$config['language']	= 'english';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = FALSE;
$config['subclass_prefix'] = 'MY_';
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['allow_get_array']		= TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger']	= 'c';
$config['function_trigger']		= 'm';
$config['directory_trigger']	= 'd'; // experimental not currently in use

/*
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
*/
$config['log_threshold'] = 4;
$config['log_path'] = '';
$config['log_date_format'] = 'Y-m-d H:i:s';
$config['cache_path'] = '';
$config['encryption_key'] = '2ca478ea0f4e512dc66bc434f8f43bf4';
$config['sess_cookie_name']		= 'ci_session';
$config['sess_expiration']		= 7200;
$config['sess_expire_on_close']	= FALSE;
$config['sess_encrypt_cookie']	= FALSE;
$config['sess_use_database']	= FALSE;
$config['sess_table_name']		= 'ci_sessions';
$config['sess_match_ip']		= FALSE;
$config['sess_match_useragent']	= TRUE;
$config['sess_time_to_update']	= 300;

$config['cookie_prefix']	= "";
$config['cookie_domain']	= "";
$config['cookie_path']		= "/";
$config['cookie_secure']	= FALSE;
$config['global_xss_filtering'] = FALSE;
$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['compress_output'] = FALSE;
$config['time_reference'] = 'local';
$config['rewrite_short_tags'] = FALSE;
$config['proxy_ips'] = '';