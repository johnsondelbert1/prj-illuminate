<?php
require_once("../includes/functions.php");
?>
<?php

if(isset($_POST['chng_info'])){
	if(isset($_POST['published'])){
		$published = 1;
	}else{
		$published = 0;
	}
	if($_POST['name']!=""){
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$query="UPDATE `site_info` SET 
				`name` = '{$_POST['name']}', `contact_email` = '{$_POST['email']}', `timezone` = '{$_POST['tz']}', `published` = '{$published}', `default_rank` = {$_POST['rank']}, `homepage` = {$_POST['homepage']}";
			$result=mysqli_query($connection, $query);
			confirm_query($result);
			$success = "Site Info has been updated!";
		}else{
			$error = "Not a valid email address!";
		}
	}else{
		$error = "Site Name cannot be blank!";
	}
}

if(isset($_POST['chngcolor'])){
	
	$query="UPDATE `site_layout` SET 
		`menu_color` = '{$_POST['menu_color']}', `contentbg_color` = '{$_POST['contentbg_color']}', `sitebg_color` = '{$_POST['sitebg_color']}', `accent_color` = '{$_POST['accent_color']}', `text_color` = '{$_POST['text_color']}'";
	$result=mysqli_query($connection, $query);
	confirm_query($result);
	$success = "Site colors have been updated!";
}

if(isset($_POST['chngganalytics'])){
	
	$analytics_code = strip_tags(mysqli_real_escape_string($connection, $_POST['analyticscode']), "<script>");
	if(isset($_POST['analyticsenabled'])){
		$analytics_enabled = 1;
	}else{
		$analytics_enabled = 0;
	}
	$query="UPDATE `site_info` SET 
		`g_analytics_enabled` = '{$analytics_enabled}', `g_analytics_code` = '{$analytics_code}'";
	$result=mysqli_query($connection, $query);
	confirm_query($result);
	$success = "Google Analytics has been updated!";
}

$query="SELECT * FROM  `site_info`";
$result=mysqli_query($connection, $query);
$site=mysqli_fetch_array($result);

$query="SELECT * FROM  `site_layout`";
$result=mysqli_query($connection, $query);
$layout=mysqli_fetch_array($result);

$query="SELECT * FROM `ranks`";
$rankresult=mysqli_query($connection, $query);

$query="SELECT * FROM `pages` ORDER BY `position` ASC";
$result_pages=mysqli_query($connection, $query);
?>
<?php
	$pgsettings = array(
		"title" => "Website Settings",
		"icon" => "icon-code"
	);
	require_once("includes/begin_cpanel.php");
?>
<script type="text/javascript">
	function chngcolor(select){
		 var selectedOption = select.options[select.selectedIndex];
		 
		 var menulight = "cccccc";
		 var menudark = "111111";
		 
		 var contentbglight = "999999";
		 var contentbgdark = "333333";
		 
		 var textlight = "333333";
		 var textdark = "cccccc";
		 
		 var redlight = "e74c3c";
		 var reddark = "c0392b";
		 
		 var bluelight = "3498db";
		 var bluedark = "2980b9";
		 
		 var greenlight = "2ecc71";
		 var greendark = "27ae60";
		 
		 var menu;
		 var contentbg;
		 var sitebg;
		 var accent;
		 var text;
		 
		 if(selectedOption.value == "Red (Dark)"){
			 var menu = menudark;
			 var contentbg = contentbgdark;
			 var sitebg = redlight;
			 var accent = reddark;
			 var text = textdark;
		 }else if(selectedOption.value == "Blue (Dark)"){
			 var menu = menudark;
			 var contentbg = contentbgdark;
			 var sitebg = bluelight;
			 var accent = bluedark;
			 var text = textdark;
		 }else if(selectedOption.value == "Green (Dark)"){
			 var menu = menudark;
			 var contentbg = contentbgdark;
			 var sitebg = greenlight;
			 var accent = greendark;
			 var text = textdark;
		 }else if(selectedOption.value == "Red (Light)"){
			 var menu = menulight;
			 var contentbg = contentbglight;
			 var sitebg = redlight;
			 var accent = reddark;
			 var text = textlight;
		 }else if(selectedOption.value == "Blue (Light)"){
			 var menu = menulight;
			 var contentbg = contentbglight;
			 var sitebg = bluelight;
			 var accent = bluedark;
			 var text = textlight;
		 }else if(selectedOption.value == "Green (Light)"){
			 var menu = menulight;
			 var contentbg = contentbglight;
			 var sitebg = greenlight;
			 var accent = greendark;
			 var text = textlight;
		 }
		 
		 document.getElementById('menucolor').value = "#"+menu;
		 document.getElementById('menucolor').style.backgroundColor = "#"+menu;
		 
		 document.getElementById('contentbg').value = "#"+contentbg;
		 document.getElementById('contentbg').style.backgroundColor = "#"+contentbg;
		 
		 document.getElementById('sitebg').value = "#"+sitebg;
		 document.getElementById('sitebg').style.backgroundColor = "#"+sitebg;
		 
		 document.getElementById('accent').value = "#"+accent;
		 document.getElementById('accent').style.backgroundColor = "#"+accent;
		 
		 document.getElementById('text').value = "#"+text;
		 document.getElementById('text').style.backgroundColor = "#"+text;
	}
	
  	var placeholder = 'This is a line \nthis should be a new line';
	$('#analytics').attr('value', placeholder);
	
	$('#analytics').focus(function(){
		if($(this).val() === placeholder){
			$(this).attr('value', '');
		}
	});
	
	$('#analytics').blur(function(){
		if($(this).val() ===''){
			$(this).attr('value', placeholder);
		}    
	});
</script>
<?php if(check_permission("Website","edit_site_settings")){ ?>
<form method="post" action="site-settings.php">
<table width="75%" border="0"  style="margin-left:auto; margin-right:auto;">
  <tr>
    <td>
        <h2>Site Name</h2>
        <input name="name" type="text" value="<?php echo $site['name']; ?>" maxlength="1024" />
    </td>
    <td>
    	<h2>Website Timezone</h2>
        <select name="tz">
			<?php foreach(DateTimeZone::listIdentifiers(DateTimeZone::ALL) as $timezone){ ?>
            <option value="<?php echo $timezone; ?>"<?php if($site['timezone'] == $timezone){echo " selected";} ?>><?php echo $timezone; ?></option>
            <?php } ?>
        </select>
     </td>
  </tr>
  <tr>
  	<td>
        <h2>Website Contact Email</h2>
        <input name="email" type="text" value="<?php echo $site['contact_email']; ?>" maxlength="128" />
    </td>
  	<td>
        <h2>New User Default Rank</h2>
        <select name="rank">
            <?php
            while($rank=mysqli_fetch_array($rankresult)){?>
                    <option value="<?php echo $rank['id'];?>"<?php if($site['default_rank']==$rank['id']){echo " selected";} ?>><?php echo $rank['name']?></option>
            <?php
            }?>
        </select>
      </td>
  </tr>
  <tr>
  	<td>
        <h2>Website Published</h2>
        <input name="published" type="checkbox"<?php if($site['published']==1){echo " checked";} ?> maxlength="128" />
    </td>
  	<td>
    	<h2>Homepage</h2>
        <select name="homepage">
            <?php
            while($homepages=mysqli_fetch_array($result_pages)){?>
                    <option value="<?php echo $homepages['id'];?>"<?php if($site['homepage']==$homepages['id']){echo " selected";} ?>><?php echo $homepages['name']?></option>
            <?php
            }?>
        </select>
    </td>
  </tr>
  <tr>
  	<td colspan="2"><input name="chng_info" type="submit" value="Change Website Info" /></td>
  </tr>
</table>
</form>
<?php } ?>
<?php if(check_permission("Website","edit_site_colors")){ ?>
<br><h2>Website Colors</h2>
<select onchange="chngcolor(this)">
	<option>Red (Dark)</option>
   	<option>Red (Light)</option>
	<option>Blue (Dark)</option>
   	<option>Blue (Light)</option>
	<option>Green (Dark)</option>
   	<option>Green (Light)</option>
</select><br><br>
<table width="75%" border="0" style="text-align:right;">
  <tr>
    <form method="post" action="site-settings.php"><td>Menu: <input id="menucolor" name="menu_color" type="text" value="<?php echo $layout['menu_color']; ?>" maxlength="7" class="color {hash:true}" /></td><td> Content BG: <input id="contentbg" name="contentbg_color" type="text" value="<?php echo $layout['contentbg_color']; ?>" maxlength="7" class="color {hash:true}" /></td></tr><tr>
    <td>Site BG: <input id="sitebg" name="sitebg_color" type="text" value="<?php echo $layout['sitebg_color']; ?>" maxlength="7" class="color {hash:true}" /></td><td> Accent: <input id="accent" name="accent_color" type="text" value="<?php echo $layout['accent_color']; ?>" maxlength="7" class="color {hash:true}" /> </td></tr><tr>
    <td>Text: <input id="text" name="text_color" type="text" value="<?php echo $layout['text_color']; ?>" maxlength="7" class="color {hash:true}" /> </td><td><input name="chngcolor" type="submit" value="Change Colors" /></td></form>
  </tr>
  </table>
  <?php } ?>
  <?php if(check_permission("Website","edit_google_analytics")){ ?>
  <form method="post" action="site-settings.php">
  	<h2>Google Analytics</h2>
    Enabled: <input name="analyticsenabled" type="checkbox"<?php if($site['g_analytics_enabled']){echo  "checked";} ?> /><br>
  	Google Analytics Code:<br>
	<textarea name="analyticscode" id="analytics" rows="15" cols="100"><?php echo $site['g_analytics_code']; ?></textarea><br>
    <input name="chngganalytics" type="submit" value="Change Google Analytics Settings" />
  </form>
  <?php } ?>
<?php require_once("includes/end_cpanel.php"); ?>