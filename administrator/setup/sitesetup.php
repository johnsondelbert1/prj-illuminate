<?php
require_once('begin.php');

try{
	$connection = mysqli_connect($dbconnection->server,  $dbconnection->username,  $dbconnection->password, $dbconnection->name);
	
	if($dbconnection->firstrun == "false"){
		$_SESSION=array();
		
		if(isset($_COOKIE[session_name()])){
			unset($_SESSION['username']);
			unset($_SESSION['password']);
		}
		
		session_destroy();
		header("Location: ../index.php");
	}
	
}catch (ErrorException $e){
	header("Location: dbconnect.php");
}

if(isset($_POST['submit'])){
	if($_POST['site-name']!=""){
		if(filter_var($_POST['site-email'], FILTER_VALIDATE_EMAIL)) {
			$query="UPDATE `site_info` SET 
				`name` = '{$_POST['site-name']}', `contact_email` = '{$_POST['site-email']}', `base_url`='{$_POST['site-url']}', `timezone` = '{$_POST['site-tz']}'";
			$result=mysqli_query($connection, $query);
			
			$info = simplexml_load_file('../../includes/database.xml');
			$info->firstrun = "false";
			$info->asXML('../../includes/database.xml');
			
			header("Location: ../index.php");
		}else{
			$message = "<h3 class=\"error\">Not a valid email address!</h3>";
		}
	}else{
		$message = "<h3 class=\"error\">Site Name cannot be blank!</h3>";
	}
}

$pgtitle = 'Step 3 of 3: Site Setup';
require_once('begin_html.php');
?>
<form method="post">
	Site Name <input type="text" name="site-name" value="<?php if(isset($_POST['site-name'])){echo $_POST['site-name'];}?>" /><br>
    Site Contact Email <input type="text" name="site-email" value="<?php if(isset($_POST['site-email'])){echo $_POST['site-email'];}?>" /><br>
    Website Timezone
        <select name="site-tz">
			<?php foreach(DateTimeZone::listIdentifiers(DateTimeZone::ALL) as $timezone){ ?>
            <option value="<?php echo $timezone; ?>"<?php if(!isset($_POST['site_tz'])&&$timezone == "America/Los_Angeles"){echo 'selected';}elseif(isset($_POST['site_tz'])&&$_POST['site_tz'] == $timezone){echo 'selected';}?>><?php echo $timezone; ?></option>
            <?php } ?>
        </select><br>
	Site URL (ex: http://www.example.com) <input type="text" name="site-url" value="<?php if(isset($_POST['site-url'])){echo $_POST['site-url'];}?>" /><br>
    <input type="submit" name="submit" value="Submit" />
</form>
</body>
</html>