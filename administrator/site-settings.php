<?php
require_once("../includes/functions.php");
?>
<?php

$output_dir_banner = "../images/banner/";
$output_dir_icon = "../images/favicon/";

if(isset($_POST['chng_info'])){
	if(check_permission("Website","edit_site_settings")){
		if(isset($_POST['published'])){
			$published = 1;
		}else{
			$published = 0;
		}
		$metadata = strip_tags(mysqli_real_escape_string($connection, $_POST['metadata']), "<meta>");
		$css_js = strip_tags(mysqli_real_escape_string($connection, $_POST['css_js']), "<link><style><script>");
		$footer_content = mysqli_real_escape_string($connection, $_POST['foot_content']);
		
		if($_POST['name']!=""){
			if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$query="UPDATE `site_info` SET 
					`name` = '{$_POST['name']}', `contact_email` = '{$_POST['email']}', `base_url`='{$_POST['url']}', `timezone` = '{$_POST['tz']}', `published` = {$published}, `default_rank` = {$_POST['rank']}, `homepage` = {$_POST['homepage']}, `meta_tags` = '{$metadata}', `style_js_link_tags` = '{$css_js}', `footer_content` = '{$footer_content}'";
			$result=mysqli_query($connection, $query);
				confirm_query($result);
				$success = "Site Info has been updated!";
			}else{
				$error = "Not a valid email address!";
			}
		}else{
			$error = "Site Name cannot be blank!";
		}
	}else{
		$error="You do not have permission to edit Site Information.";
	}
}

if(isset($_POST['chngcolor'])){
	if(check_permission("Website","edit_site_colors")){
		$query="UPDATE `site_layout` SET 
			`menu_color` = '{$_POST['menu_color']}', `contentbg_color` = '{$_POST['contentbg_color']}', `sitebg_color` = '{$_POST['sitebg_color']}', `accent_color` = '{$_POST['accent_color']}', `text_color` = '{$_POST['text_color']}'";
		$result=mysqli_query($connection, $query);
		confirm_query($result);
		$success = "Site colors have been updated!";
	}else{
		$error="You do not have permission to edit colors.";
	}
}
/*
if(isset($_POST['delbanners'])){
	if(check_permission("Website","upload_favicon_banner")){
		function del_file($file){
			global $output_dir_banner;
			if(file_exists($output_dir_banner.$file)){
				unlink($output_dir_banner.$file);
				return "File \"".$file."\" was deleted.";
			}else{
				return "File \"".$file."\" was not found. Perhaps it was already deleted?";
			}
		}
		if(!empty($_POST['files'])){
			$i = 0;
			foreach($_POST['files'] as $file){
				$success=del_file($file);
				$i++;
				if($i > 1){
					$success=$i." files were deleted.";
				}
			}
		}else{
			$error="No files selected.";
		}
	}else{
		$error="You do not have permission to delete banners!";
	}
}*/
if(isset($_POST['uploadbanner'])){
	foreach (scandir($output_dir_banner) as $item) {
		if ($item == '.' || $item == '..') continue;
		unlink($output_dir_banner.DIRECTORY_SEPARATOR.$item);
	}
	$message = upload($_FILES, $output_dir_banner, 2097152, array('.jpeg','.jpg','.gif','.png','.ico'));
}
if(isset($_POST['uploadfavicon'])){
	foreach (scandir($output_dir_icon) as $item) {
		if ($item == '.' || $item == '..') continue;
		unlink($output_dir_icon.DIRECTORY_SEPARATOR.$item);
	}
	$message = upload($_FILES, $output_dir_icon, 128000, array('.jpeg','.jpg','.gif','.png','.ico'));
}
if(isset($_POST['chngganalytics'])){
	if(check_permission("Website","edit_google_analytics")){
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
	}else{
		$error="You do not have permission to edit Google Analytics settings!";
	}
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
<script type="text/javascript" src="../tinymce/js/tinymce/tinymce.min.js"></script>
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
	
	tinymce.init({
		selector: "#foot_content",
		theme: "modern",
		skin: 'light',
		width: '100%',
		plugins: [
			"advlist autolink lists link image charmap print preview anchor",
			"searchreplace wordcount visualblocks visualchars code fullscreen",
			"insertdatetime media save directionality",
			"emoticons template paste textcolor"
		],
		toolbar1: "insertfile undo redo | bold italic | bullist numlist outdent indent | link image",
		image_advtab: true,
		templates: [
			{title: 'Footer Template', content: '<b>Phone: </b>(208)-555-5555<br><b>Address: </b>1234 Secondgen Lane, Coeur d\' Alene, ID 83814'},
		]
	});
	
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
<div id="TabbedPanels1" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
            	<?php if(check_permission("Website","edit_site_settings")){ ?>
                	<li class="TabbedPanelsTab" tabindex="0">Settings</li>
                <?php } ?>
                <?php if(check_permission("Website","edit_site_colors")){ ?>
                <li class="TabbedPanelsTab" tabindex="0">Colors</li>
                <?php } ?>
                <?php if(check_permission("Website","upload_favicon_banner")){ ?>
                <li class="TabbedPanelsTab" tabindex="0">Banner / Favicon</li>
                <?php } ?>
                <?php if(check_permission("Website","edit_google_analytics")){ ?>
                <li class="TabbedPanelsTab" tabindex="0">Google Analytics</li>
                <?php } ?>
            </ul>
            <div class="TabbedPanelsContentGroup">
                
<?php if(check_permission("Website","edit_site_settings")){ ?>
<div class="TabbedPanelsContent">
<h1>Site Data</h1>
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
  	<td>
        <h2>Site URL (ex: http://www.example.com)</h2>
        <input name="url" type="text" value="<?php echo $site['base_url']; ?>" maxlength="256" />
    </td>
  	<td>

    </td>
  </tr>
  <tr>
  	<td colspan="2">
    	<h2>Metadata</h2>
        <textarea name="metadata" id="metadata" rows="15" cols="80"><?php if($site['meta_tags']!=""){echo $site['meta_tags'];}else{echo "<meta name=\"description\" content=\"A description of the page\" />";} ?></textarea><br>
    </td>
  </tr>
  <tr>
  	<td colspan="2">
    	<h2>Custom CSS and Javascript</h2>
        <textarea name="css_js" id="css_js" rows="15" cols="80"><?php echo $site['style_js_link_tags']; ?></textarea><br>
    </td>
  </tr>
  <tr>
  	<td colspan="2">
    	<h2>Website Footer Content</h2>
        <textarea name="foot_content" id="foot_content" rows="15" cols="80"><?php echo $site['footer_content']; ?></textarea><br>
    </td>
  </tr>
  <tr>
  	<td colspan="2"><input name="chng_info" type="submit" value="Change Website Info" /></td>
  </tr>
</table>
</form>
</div>           
<?php } ?>
<?php if(check_permission("Website","edit_site_colors")){ ?>
<div class="TabbedPanelsContent">
<h1>Website Colors</h1>
<select onchange="chngcolor(this)">
	<option>Red (Dark)</option>
   	<option>Red (Light)</option>
	<option>Blue (Dark)</option>
   	<option>Blue (Light)</option>
	<option>Green (Dark)</option>
   	<option>Green (Light)</option>
</select><br><br>
<form method="post" action="site-settings.php">
    <table width="75%" border="0" style="margin-left:auto; margin-right:auto;">
      <tr>
        <td style="text-align:right;"><label for="menucolor">Menu:</label></td><td style="text-align:left;"><input id="menucolor" name="menu_color" type="text" value="<?php echo $layout['menu_color']; ?>" maxlength="7" class="color {hash:true}" /></td>
        <td style="text-align:right;"><label for="contentbg">Content BG:</label></td><td style="text-align:left;"><input id="contentbg" name="contentbg_color" type="text" value="<?php echo $layout['contentbg_color']; ?>" maxlength="7" class="color {hash:true}" /></td>
      </tr>
      <tr>
        <td style="text-align:right;"><label for="sitebg">Site BG:</label></td><td style="text-align:left;"><input id="sitebg" name="sitebg_color" type="text" value="<?php echo $layout['sitebg_color']; ?>" maxlength="7" class="color {hash:true}" /></td>
        <td style="text-align:right;"><label for="accent">Accent:</label></td><td style="text-align:left;"><input id="accent" name="accent_color" type="text" value="<?php echo $layout['accent_color']; ?>" maxlength="7" class="color {hash:true}" /> </td>
      </tr>
      <tr>
        <td style="text-align:right;"><label for="text">Text:</label></td><td style="text-align:left;"><input id="text" name="text_color" type="text" value="<?php echo $layout['text_color']; ?>" maxlength="7" class="color {hash:true}" /></td>
      </tr>
      <tr>
        <td colspan="4"><input name="chngcolor" type="submit" value="Change Colors" /></td>
      </tr>
    </table>
</form>
</div>
<?php } ?>             
<?php if(check_permission("Website","upload_favicon_banner")){ ?>
<div class="TabbedPanelsContent">
<h1>Upload Banner</h1>
<form method="post" enctype="multipart/form-data">
	<input type="file" name="file" id="file" />
	<input name="uploadbanner" type="submit" value="Upload a banner (2MB max)" />
</form>
<h1>Upload Favicon</h1>
<form method="post" enctype="multipart/form-data">
	<input type="file" name="file" id="file" />
	<input name="uploadfavicon" type="submit" value="Upload a favicon (128KB max)" />
</form>
</div>
<?php } ?>             
  <?php if(check_permission("Website","edit_google_analytics")){ ?>
  <div class="TabbedPanelsContent">
  <h1>Google Analytics</h1>
  <form method="post" action="site-settings.php">
    Enabled: <input name="analyticsenabled" type="checkbox"<?php if($site['g_analytics_enabled']){echo  "checked";} ?> /><br>
  	Google Analytics Code:<br>
	<textarea name="analyticscode" id="analytics" rows="15" cols="80"><?php echo $site['g_analytics_code']; ?></textarea><br>
    <input name="chngganalytics" type="submit" value="Change Google Analytics Settings" />
  </form>
  </div>
  <?php } ?>
<?php require_once("includes/end_cpanel.php"); ?>