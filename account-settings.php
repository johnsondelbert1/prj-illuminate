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
<h1>Account Settings</h1>

<h2>Change Password</h2>

<form action="account-settings.php" method="post">
<table width="50%" border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td>Current Password:</td>
    <td><input type="password" name="cpass" class="text"/></td>
  </tr>
  <tr>
    <td>New Password:</td>
    <td><input type="password" name="npass" class="text"/></td>
  </tr>
  <tr>
    <td>Confirm New Password:</td>
    <td><input type="password" name="cnpass" class="text"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="chngpass" value="Change Password" class="submit"/></td>
  </tr>
</table>
</form>
<br />
<h2>Change Username</h2>

<form action="account-settings.php" method="post">
<table width="50%" border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td>New Username:</td>
    <td><input type="text" name="newuser" class="text" maxlength="50"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="chnguser" value="Change Username" class="submit"/></td>
  </tr>
</table>
</form>
<h2>Change Email</h2>

<form action="account-settings.php" method="post">
<table width="50%" border="0" cellspacing="5" cellpadding="0">
  <tr>
    <td>New Email:</td>
    <td><input type="text" name="newemail" class="text" maxlength="50"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="chngemail" value="Change Email" class="submit"/></td>
  </tr>
</table>
</form>
<br />
<?php
	require_once("includes/end_html.php");
?>