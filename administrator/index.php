<?php
require_once("../includes/functions.php");
confirm_logged_in();
if(!check_permission("Website","cpanel_access")){
	redirect_to("login.php?error=".urlencode('You do not have Cpanel Access'));
}
?>
<?php
$query="SELECT * FROM  `features` WHERE  `id` =  1";
$result=mysqli_query($connection, $query);
$feature=mysqli_fetch_array($result);

$query="SELECT * FROM  `galleries`";
$galleryresult=mysqli_query($connection, $query);
?>
<?php
	$pgsettings = array(
		"title" => $GLOBALS['site_info']['name']." CPanel",
		"icon" => "icon-dashboard"
	);
	require_once("includes/begin_cpanel.php");
	?>
<h2 class="center"><a href="../" target="_blank">Back to Website</a></h2>
<br />
</div>
 <div class="row">
 <?php if(check_permission(array("Pages;add_pages","Pages;edit_pages","Pages;delete_pages"))){?>
      <div class="col s12 l4"><div class="card blue small darken-1">
            <div class="card-content white-text"><span class="card-title">Pages</span>
              <p>This allows you to create and edit webpages, view page hit statistics, and access special page contents like blogs and forums.</p>
            </div>
            <div class="card-action">
              <a href="edit_page.php?action=newpage">New</a>
              <a href="page_list.php">Edit</a>
            </div>
          </div></div>
          <?php } ?>
          <?php if(check_permission(array("Galleries;add_gallery","Galleries;edit_gallery","Galleries;delete_gallery","Galleries;rename_gallery"))){?>
           <div class="col s12 l4"><div class="card small blue-grey darken-1">
            <div class="card-content white-text">
              <span class="card-title">Gallery</span>
              <p>Create new galleries and edit images.</p>
            </div>
            <div class="card-action">
              <a href="gallery-list.php">Edit</a>
            </div>
          </div></div>
          <?php } ?>
          <?php if(check_permission(array("Website;edit_site_settings","Website;upload_favicon_banner","Website;edit_google_analytics","Website;edit_socnet"))){?>
      <div class="col s12 l4"><div class="card small red darken-1">
            <div class="card-content white-text">
              <span class="card-title">Settings</span>
              <p>Website Settings.</p>
            </div>
            <div class="card-action">
              <a href="site-settings.php">Edit</a>
            </div>
          </div></div>
          <?php } ?>
</div>
<!--<h2>Social Networks</h2>
<br />
<form method="post" action="control_panel.php">
<table width="50%" border="0">
  <tr>
    <td colspan="2"><h3>Twitter</h3></td>
  </tr>
  <tr>
    <td>Use Twitter feed:</td>
    <td><input name="twitterfeed" type="checkbox" value="" <?php if($feature['twitterfeed']==1){echo "checked";} ?> /></td>
  </tr>
  <tr>
    <td>Twitter Username:</td>
    <td><input name="twitteruser" type="text" value="<?php echo $feature['twitteruser']; ?>" maxlength="100" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input name="updatesocnet" type="submit" value="Update Social Networks" class="submit"/></td>
  </tr>
</table>
</form><br />-->
<?php
	require_once("includes/end_cpanel.php");
?>
