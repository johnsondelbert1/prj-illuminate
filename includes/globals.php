<?php
//Get URL pieces
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
if(in_array($_SERVER['SERVER_PORT'],array(80, 443))){
	$serv_port = '';
}else{
	$serv_port=':'.$_SERVER['SERVER_PORT'];
}
if(strpos($_SERVER['SCRIPT_NAME'], '/administrator')===FALSE){
	$path = str_replace('/'.basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
}else{
	$path = str_replace('/administrator/'.basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
}

//global variables
$GLOBALS['HOST'] = $protocol.$_SERVER['HTTP_HOST'].$serv_port.$path;
$site_version = '1.8';
$db_compatability = '1.6';

//global constants
define('USER_DIR','user-data/');
define('USER_DIR_URL',$GLOBALS['HOST'].'/'.USER_DIR);
?>
