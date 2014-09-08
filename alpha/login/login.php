<?php
require_once("../includes/functions.php");

if(isset($_POST['submit'])){
	
	$user=$_POST['username'];
	$pass=$_POST['password'];
	$hashed_pass=sha1($pass);
	
	$query="SELECT id, username FROM users
			WHERE username='{$user}'
			AND hashed_pass='{$hashed_pass}'";
	$result=mysqli_query($connection, $query);
	confirm_query($result);
	if (mysqli_num_rows($result)==1){
		
		$found_user=mysqli_fetch_array($result);
		$_SESSION['user_id']=$found_user['id'];
		$_SESSION['username']=$found_user['username'];
		if(isset($_POST['remember'])){$remember=1;}else{$remember=0;}
		if($remember==1){
			setcookie("rememberme",$found_user['username'], time()+60*60*24*30);
		}
		
		session_name("login");
		session_start();
		
		$date=date("Y/m/d H:i:s");
		$query="UPDATE `users` SET 
				`last_logged_in` = '{$date}' 
				WHERE `id` = {$_SESSION['user_id']}";
		$result=mysqli_query($connection, $query);
		confirm_query($result);
		
		redirect_to("../".$_POST['redirect_to']);
	}else{
		redirect_to($_POST['redirect_from']."?error=".urlencode("Login was not successful."));
	}
}else{
	if(isset($_GET['logout']) && $_GET['logout']==1){
		redirect_to($_POST['redirect_from']."?success=".urlencode("You have successfully logged out!"));
	}
	redirect_to("../login.php");
}
?>