<?php
require_once("includes/connection.php");
require_once("includes/functions.php");
?>
<?php
if (isset($_POST['submit'])){
	function checkstr($string){
		if (ctype_alnum($string)) {
			$invalchar=false;
		} else {
			$invalchar=true;
		}
		return $invalchar;
	}
	
	
	date_default_timezone_set("America/Los_Angeles");
	
	$user=strip_tags(trim(mysql_prep($_POST['username'])));
	$email=strip_tags(trim(mysql_prep($_POST['email'])));
	$mcuser=strip_tags(trim(mysql_prep($_POST['mcuser'])));
	$validmcuser=false;
	
	/*$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, "http://www.minecraft.net/haspaid.jsp?user=".$mcuser);
	$check = curl_exec($ch);
	curl_close($ch);*/
	
	
	$phone=strip_tags(trim(mysql_prep($_POST['phone'])));
	$location=strip_tags(trim(mysql_prep($_POST['location'])));
	$gender=strip_tags(trim(mysql_prep($_POST['gender'])));
	$dob=$_POST['year']."-".$_POST['month']."-".$_POST['day'];
	$datejoined=date("Y/m/d H:i:s");
	$age = get_age($dob);
	$pass=$_POST['pass'];
	$confirmpass=$_POST['confirmpass'];
	$hashed_pass=sha1($pass);
	$verifcode=randstring();
	if(checkdate(intval($_POST['month']), intval($_POST['day']), intval($_POST['year']))){
		if ($pass == $confirmpass){
			if (!empty($user) && !empty($email) && !empty($pass) && !empty($mcuser) && $user != " " && $email != " " && $pass != " " && $mcuser != " "){
				if($age >= 13){
					if(checkstr($user)==false){
						if(filter_var($email,FILTER_VALIDATE_EMAIL)){
							if($mcuser!=""){
								$query="SELECT id, username, email, minecraft_username FROM users
										WHERE username='{$user}' OR email='{$email}' OR minecraft_username='{$mcuser}'";
							}else{
								$query="SELECT id, username, email, minecraft_username FROM users
										WHERE username='{$user}' OR email='{$email}'";
							}
							$result=mysql_query($query,$connection);
							confirm_query($result);
							if (mysql_num_rows($result)>=1){
								if($mcuser!=""){
									$error="Error: An account with this username, email, or minecraft username already exists!";
								}else{
									$error="Error: An account with this username or email already exists!";
								}
								$pass="";
								$confirmpass="";
							}else{
								$query="INSERT INTO users (
											username, email, minecraft_username, datejoined, dob, phone, location, gender, hashed_pass, verifcode
										) VALUES (
											'{$user}', '{$email}', '{$mcuser}', '{$datejoined}','{$dob}', '{$phone}', '{$location}', '{$gender}', '{$hashed_pass}', '{$verifcode}'
										)";
								$result=mysql_query($query, $connection);
								
								$to = $email;
								$subject = "Account activation code";
								$message = "Activate your account: http://minecraft.secondgenerationdesign.com/activate.php?code=".$verifcode." You must be logged in already on the same browser for the activation to take place.";
								$headers = "From:StormForge@minecraft.secondgenerationdesign.com".PHP_EOL;
								
								mail ( $to , $subject , $message , $headers );
								
								$to = "johnsondelbert1@gmail.com";
								$subject = "User has Registered";
								$message = $user." has registered on the website. http://stormforgemc.com/user_list.php";
								$headers = "From:StormForge@minecraft.secondgenerationdesign.com".PHP_EOL;
								
								mail ( $to , $subject , $message , $headers );
								
								if($result){
									$success="Account was created successfully for ".$_POST['username']."! An email containing your verification code has been sent to the email you have provided.";
									$pass="";
									$confirmpass="";
								}else{
									$error="Account was not created successfully. ".mysql_error();
									$pass="";
									$confirmpass="";
								}
							}
						}else{
							$error="The email you have entered is invalid.";
							$pass="";
							$confirmpass="";
						}
					}else{
						$error="Username has an invalid character in it.";
						$pass="";
						$confirmpass="";
					}
				}else{
					$error="Sorry, but you must be at least 13 years of age to create and use an account on this website.";
					$pass="";
					$confirmpass="";
				}
			}else{
				$error="Required field left blank.";
				$pass="";
				$confirmpass="";
			}
		}else{
			$error="Confirm password does not match entered password.";
			$pass="";
			$confirmpass="";
		}
	}else{
		$error="Not a valid date for Birthday, ".$dob;
		$pass="";
		$confirmpass="";
	}
}else{
	$user="";
	$email="";
	$mcuser="";
	$phone="";
	$location="";
	$age="";
	$gender="";
	$pass="";
	$confirmpass="";
}
?>
<?php
$pgsettings = array(
	"title" => "Register",
	"pageselection" => false,
	"nav" => true,
	"banner" => 1,
	"use_google_analytics" => 1,
);
require_once("includes/begin_html.php"); ?>

<?php if(!empty($message)){echo "<p>".$message."</p>";} ?>
<form action="register.php" method="post">
<table width="35%" border="0" cellspacing="5" cellpadding="0" align="center">
  <tr>
    <td><input type="text" name="username" class="text" maxlength="50" placeholder="Username*" style="width:300px;" value="<?php echo $user; ?>"/></td>
  </tr>
  <tr>
    <td><input type="text" name="email" class="text" maxlength="50" placeholder="E-mail*" style="width:300px;" value="<?php echo $email; ?>"/></td>
  </tr>
  <tr>
    <td><input type="text" name="phone" class="text" maxlength="16" placeholder="Phone (555)555-555" style="width:300px;" value="<?php echo $phone; ?>"/></td>
  </tr>
  <tr>
    <td><input type="text" name="location" class="text" maxlength="50" placeholder="Location" style="width:300px;" value="<?php echo $location; ?>"/></td>
  </tr>
  <tr>
    <td>Birthday:*
    <select name="month"></option><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select>
    <select name="day"><?php $count=1; while($count<32){echo "<option value=\"{$count}\">{$count}</option>"; $count++;} ?></select>
    <select name="year"><?php $year=date("Y"); $count=0; while($count<131){$newyear=(intval($year)-$count); echo "<option value=\"".$newyear."\">".$newyear."</option>"; $count++;} ?></select>
    </td>
  </tr>
  <tr>
    <td>Gender:*
    <select name="gender">
      <option value="male" <?php if($gender=="male"){echo "selected=\"selected\"";} ?>>Male</option>
      <option value="female" <?php if($gender=="female"){echo "selected=\"selected\"";} ?>>Female</option>
    </select>
    </td>
  </tr>
  <tr>
    <td><input type="password" name="pass" class="text" placeholder="Password*" style="width:300px;" value="<?php echo $pass; ?>"/></td>
  </tr>
  <tr>
    <td><input type="password" name="confirmpass" class="text" placeholder="Confirm Password*" style="width:300px;" value="<?php echo $confirmpass; ?>"/></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input class="button" type= "submit" name="submit" value="Create Account" /></td>
  </tr>
</table>

</form>
<?php
include("includes/end_html.php");
?>