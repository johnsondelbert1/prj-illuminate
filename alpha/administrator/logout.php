<?php
require_once("../includes/functions.php");
confirm_logged_in();

session_start();

$_SESSION=array();

if(isset($_COOKIE[session_name()])){
	setcookie(session_name(),'',time()-42000,'/');
}
if(isset($_COOKIE['rememberme'])){
	setcookie("rememberme", "", time()-3600);	
}

session_destroy();

if(isset($_GET['delete']) && $_GET['delete']=1){
	redirect_to("login.php");
}else{
	redirect_to("login.php");
}
?>