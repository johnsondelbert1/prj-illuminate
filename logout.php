<?php
require_once("includes/session.php");
require_once("includes/functions.php");
confirm_logged_in();

session_start("login");

if(isset($_SESSION['user_id'])){
	unset($_SESSION['user_id']);
}
if(isset($_SESSION['username'])){
	unset($_SESSION['username']);
}

if(isset($_COOKIE[session_name()])){
	setcookie(session_name(), "", time()-3600, '/');
}
if(isset($_COOKIE['rememberme'])){
	unset($_COOKIE['rememberme']);
	setcookie("rememberme", "", time()-3600, '/');
}

session_destroy();

if(isset($_GET['delete']) && $_GET['delete']=1){
	redirect_to("login.php?success=".urlencode("Account has been disabled."));
}else{
	redirect_to("login.php?success=".urlencode("You have successfully logged out!"));
}
?>