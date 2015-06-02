<?php
require_once("includes/functions.php");

if(logged_in()){
	redirect_to($site_info['base_url']."/index.php");
}

$pgsettings = array(
	"title" => "Login to ".$site_info['name'],
	"pageselection" => false,
	"nav" => true,
	"banner" => 1,
	"use_google_analytics" => 1,
);
require_once("includes/begin_html.php");
?>
<form action="login/login.php" method="post">
	<input type="hidden" name="redirect_to" value="index.php"/>
    <input type="hidden" name="redirect_from" value="<?=($_SERVER['PHP_SELF']);?>"/>
    <table width="35%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td>Username:</td>
        <td><input type="text" name="username" value="<?php if(isset($user)){echo $user;} ?>"/></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type="password" name="password" /></td>
      </tr>
      <tr>
        <td>Remember Me</td>
        <td><input name="remember" type="checkbox" value=""  id="remember"/><label for="remember"></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="submit" name="submit" value="Login" /><br /><br />
        <a href="forgot_password.php">(Forgot password/Username?)</a></td>
      </tr>
    </table>
</form>

<?php
	require_once("includes/end_html.php");
?>