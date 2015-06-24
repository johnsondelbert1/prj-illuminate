<?php
require_once("includes/session.php");
require_once("includes/functions.php");
confirm_logged_in();
$noval="--";
?>
<?php
		if(isset($_POST['chngpass'])){
			if(!empty($_POST['cpass']) && !empty($_POST['npass']) && !empty($_POST['cnpass'])){
				$cpass=$_POST['cpass'];
				$npass=$_POST['npass'];
				$cnpass=$_POST['cnpass'];
				$hashedcpass=sha1($cpass);
				$hashednpass=sha1($npass);
				if($npass==$cnpass){
					$query="SELECT hashed_pass FROM users
							WHERE `id`={$_SESSION['user_id']}";
					$result=mysqli_query($connection, $query);
					confirm_query($result);
					$pass=mysqli_fetch_array($result);
					if($pass['hashed_pass']==$hashedcpass){
						$query="UPDATE `users` SET 
								`hashed_pass` = '{$hashednpass}' 
								WHERE `id` = {$_SESSION['user_id']}";
						$result=mysqli_query($connection, $query);
						confirm_query($result);
						$success="Your password has been changed.";
					}else{
						$error="The current password you entered does not match your password.";
					}
				}else{
					$error="Confirm new password does not match New password.";
				}
			}else{
				$error="Required field left blank.";
			}
		}
		if(isset($_POST['chnguser'])){
			$newuser=$_POST['newuser'];
			if($newuser != "--"){
				if(!empty($newuser)&&$newuser!=" "){
					$query="SELECT username FROM `users`
								WHERE username='{$newuser}'";
					$result=mysqli_query($connection, $query);
					confirm_query($result);
					if($newuser=="" || mysqli_num_rows($result)<1){
						$query="UPDATE `users` SET 
								`username` = '{$newuser}' 
								WHERE `id` = {$_SESSION['user_id']}";
						$result=mysqli_query($connection, $query);
						confirm_query($result);
						$success="Your username has been changed.";
					}else{
						$error="An account already has this username.";
					}
				}else{
					$error="Cannot have blank username.";
				}
			}else{
				$error="\"--\" not allowed.";
			}
		}
		if(isset($_POST['chngemail'])){
			$newemail=$_POST['newemail'];
			if(filter_var($newemail,FILTER_VALIDATE_EMAIL)){
				if(!empty($newemail)&&$newemail!=" "){
					$query="UPDATE `users` SET 
							`email` = '{$newemail}' 
							WHERE `id` = {$_SESSION['user_id']}";
					$result=mysqli_query($connection, $query);
					confirm_query($result);
					$success="Your email has been changed.";
				}else{
					$error="Cannot have blank email.";
				}
			}else{
				$error="Invalid Email.";
			}
		}
		$query="SELECT * FROM users
				WHERE username='{$_SESSION['username']}'";
		$result=mysqli_query($connection, $query);
		confirm_query($result);
		$user=mysqli_fetch_array($result);
?>
<?php
$pgsettings = array(
	"title" => "Account Settings",
	"pageselection" => false,
	"nav" => true,
	"banner" => 1,
	"use_google_analytics" => 1,
);
require_once("includes/begin_html.php");
?>
<div class="row center">
<div class="col s12"><h3>Account Settings</h3>
</div>
</div>
</div>
<div class="card">
<div class="row blogtitle">
<div class="col s12"><h5>Change Password</h5>
</div>
</div>
<div class="container">
<form action="account-settings.php" method="post">
<div class="row">
    <div class="col s12 l3">Current Password:</div>
    <div class="col s12 l9"><input type="password" name="cpass" class="text"/></div>
  </div>
  <div class="row">
    <div class="col s12 l3">New Password:</div>
    <div class="col s12 l9"><input type="password" name="npass" class="text"/></div>
  </div>
  <div class="row">
    <div class="col s12 l3">Confirm New Password:</div>
    <div class="col s12 l9"><input type="password" name="cnpass" class="text"/></div>
    <div class="col col offset-s6 s6 l3"><input type="submit" name="chngpass" value="Save" class="green btn"/></div>
  </div>

</form>
</div>
</div>
<div class="card">
<div class="row blogtitle">
<div class="col s12"><h5>Change Username</h5></div>
</div>
<div class="container">
<form action="account-settings.php" method="post">
<div class="row">
    <div class="col s12 l3">New Username:</div>
    <div class="col s12 l9"><input type="text" name="newuser" class="text" maxlength="50"/></div>
    <div class="col col offset-s6 s6 l3"><input type="submit" name="chnguser" value="Save" class="green btn"/></div>
  </div>
</form>
</div>
</div>
<div class="card">
<div class="row blogtitle">
<div class="col s12"><h5>Change Email</h5></div>
</div>
<div class="container">
<form action="account-settings.php" method="post">
<div class="row">
    <div class="col s12 l3">New Email:</div>
    <div class="col s12 l9"><input type="text" name="newemail" class="text" maxlength="50"/></div>
    <div class="col offset-s6 s6 l3"><input type="submit" name="chngemail" value="Save" class="green btn"/></div>
</div>
</form>
</div>
</div>
<?php
	require_once("includes/end_html.php");
?>