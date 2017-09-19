<?php
if(!check_permission("Website","cpanel_access")){
	redirect_to("login.php");
}
if(isset($_GET['error'])){
	$error=urldecode($_GET['error']);
}
if(isset($_GET['success'])){
	$success=urldecode($_GET['success']);
}
if(isset($_GET['message'])){
	$message=urldecode($_GET['message']);
}
//Re-create folders
foreach ($folders as $folder){
	if(!file_exists('../'.$folder)){
		mkdir('../'.$folder);
	}
}

$banner = scandir("../".USER_DIR."site-img/banner/");
if(isset($banner[2])){
    $banner = $banner[2];
}else{
    $banner = false;
}
$favicon = scandir("../".USER_DIR."site-img/favicon/");
if(isset($favicon[2])){
    $favicon = $favicon[2];
}else{
    $favicon = false;
}
$logo = scandir("../".USER_DIR."site-img/logo/");
if(isset($logo[2])){
    $logo = $logo[2];
}else{
    $logo = false;
}
$bg = scandir("../".USER_DIR."site-img/bg/");
if(isset($bg[2])){
    $bg = $bg[2];
}else{
    $bg = false;
}

//Number of pending users for approval

if($GLOBALS['site_info']['user_creation'] == 'approval'){
    $query="SELECT * FROM `users` WHERE `approved_admin` = 0 ORDER BY `id` ASC";
    $result=mysqli_query( $connection, $query);
    confirm_query($result);
    $pending_users = mysqli_num_rows($result);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title><?php echo $pgsettings['title']; ?></title>

    <META NAME="description" CONTENT="IlluminateCMS Control Panel">
    <META NAME="robot" CONTENT="index,follow">
    <META NAME="copyright" CONTENT="All Images, Video, and Source Code Property of Second Generation Design, Copyright © 2011-<?php echo date("Y"); ?>">
    <META NAME="author" CONTENT="2GD - Secondgenerationdesign.com">
    <META NAME="language" CONTENT="English">
    <META NAME="revisit-after" CONTENT="7">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--Android 5.0+ -->
    <meta name="theme-color" content="#C0392B">

    <link rel="shortcut icon" href="images/logo16.png" />
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>-->
    <script type="text/javascript" src="../jscolor/jscolor.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <!-- Primary -->
    <!--<link href="../materialize/css/materialize.css" rel="stylesheet" type="text/css" media="screen,projection"/>-->
    <!-- Beta -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.99.0/css/materialize.min.css">
    <!-- Override -->
    <link href="styles/materialize-override.css" rel="stylesheet" type="text/css" media="screen,projection"/>


    <link href="styles/main.css" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href="styles/fonts.css" rel="stylesheet" type="text/css" />
    <link href="../styles/animate.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--<link type="text/css" rel="stylesheet" href="styles/materialize.css"  media="screen,projection"/>-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

</head>


<body>
    <div class="wrap">
			<div class="navbar-fixed">
<nav class="nav-extended">
	<div class="nav-wrapper">

				<a href="#" data-activates="slide-out" class="button-collapse show-on-large"><i class="material-icons">menu</i></a>
				<ul class="right hide-on-med-and-down">
<?php if(check_permission(array("Uploading;upload_files","Uploading;delete_files","Uploading;create_folders","Uploading;rename_folders","Uploading;delete_folders"))){?>
				<li><a href="upload-files.php"><i class="material-icons">&#xE2C6;</i></a></li>
				<?php } ?>
				<?php if(check_permission(array("Website;edit_site_settings","Website;upload_favicon_banner","Website;edit_google_analytics","Website;edit_socnet"))){?>
				<li><a href="site-settings.php"><i class="material-icons">&#xE8B8;</i></a></li>
				<?php } ?>
      </ul>
			</div>
			<div class="nav-content">
      <span class="nav-title"><?= $pgsettings['title']; ?></span>
			<?php

			if (in_array("btn", $pgsettings))
			  {
			  echo '<a href="'; ?> <?= $pgsettings["btn"]; ?> <?= '" class="btn-floating btn-large halfway-fab waves-effect waves-light teal">
			    <i class="material-icons">add</i>
			  </a>';
			  }
			else
			  {
			  echo "Match not found";
			  }
			?>
    </div>
<ul id="slide-out" class="side-nav">
    <li><a href="index.php"><i class="material-icons">&#xE871;</i>Dashboard</a></li>
    <?php if(check_permission("Users","approve_deny_new_users") && $GLOBALS['site_info']['user_creation'] == 'approval'){?>
    <li><a href="approval-list">Approve/Deny<?php if($pending_users > 0){echo '<span class="red new badge">'.$pending_users.'</span>';} ?></a></li>
    <?php } ?>
    <?php if(check_permission(array("Pages;add_pages","Pages;edit_pages","Pages;delete_pages"))){?>
      <li class="no-padding">
        <ul class="collapsible collapsible-accordion">
          <li>
            <a class="collapsible-header">Pages<i class="material-icons">arrow_drop_down</i></a>
            <div class="collapsible-body">
              <ul>
              <li><a href="edit_page.php?action=newpage"><i class="material-icons">people</i>New</a></li>
                <li><a href="page_list.php"><i class="material-icons">settings</i>Edit</a></li>
                <li><a href="staff-list.php"><i class="material-icons">people</i>Staff</a></li>

              </ul>
            </div>
          </li>
        </ul>
      </li>
    <?php } ?>
    <?php if(check_permission(array("Galleries;add_gallery","Galleries;edit_gallery","Galleries;delete_gallery","Galleries;rename_gallery","Sliders;add_slider","Sliders;edit_slider","Sliders;delete_slider","Sliders;rename_slider",))){?>
    <li class="no-padding">
        <ul class="collapsible collapsible-accordion">
          <li>
            <a class="collapsible-header">Images<i class="material-icons">arrow_drop_down</i></a>
            <div class="collapsible-body">
              <ul>
                <?php if(check_permission(array("Galleries;add_gallery","Galleries;edit_gallery","Galleries;delete_gallery","Galleries;rename_gallery"))){?>
                <li><a href="gallery-list.php"><i class="material-icons">&#xE3B6;</i>Gallery</a></li>
                <?php } ?>
                <?php if(check_permission(array("Sliders;add_slider","Sliders;edit_slider","Sliders;delete_slider","Sliders;rename_slider"))){?>
                <li><a href="slider-list.php"><i class="material-icons">slideshow</i>Slider</a></li>
                <?php } ?>
                <?php if(check_permission("Website","upload_favicon_banner")){ ?>
                <li><a href="site-settings.php?tab=2#"><i class="material-icons">settings</i>Settings</a></li>
                <?php } ?>
              </ul>
            </div>
          </li>
        </ul>
      </li>
    <?php } ?>
    <?php if(check_permission(array("Forms;create_form","Forms;edit_form","Forms;delete_form"))){?>
    <li><a href="form-list.php"><i class="material-icons">chrome_reader_mode</i>Forms</a></li>
    <?php } ?>
    <!--<?php if(check_permission(array("Uploading;upload_files","Uploading;delete_files","Uploading;create_folders","Uploading;rename_folders","Uploading;delete_folders"))){?>
    <li><a href="upload-files.php"><i class="material-icons">&#xE2C6;</i>Upload</a></li>
	<?php } ?>-->
    <?php if(check_permission(array("Forum;add_delete_forum","Forum;edit_forum"))){?>
    <li><a href="list-forums"><i class="material-icons">&#xE0BF;</i>Forums</a></li>
    <?php } ?>
    <?php if(check_permission(array("Calendars;add_delete_calendar","Calendars;add_event","Calendars;delete_event"))){?>
    <li><a href="list-calendars"><i class="material-icons">today</i>Calendars</a></li>
    <?php } ?>
    <?php if(check_permission("Website","edit_site_colors")){ ?>
    <li><a href="edit-colors">Theme<i class="material-icons">&#xE40A;</i></a></li>
    <?php } ?>
    <?php if(check_permission(array("Users;add_users","Users;delete_users","Users;create_rank","Users;edit_rank","Users;delete_rank","Users;approve_deny_new_users"))){?>
      <li class="no-padding">
        <ul class="collapsible collapsible-accordion">
          <li>
            <a class="collapsible-header">Users<i class="material-icons">arrow_drop_down</i><i class="material-icons">&#xE853;</i></a>
            <div class="collapsible-body">
              <ul>
                <?php if(check_permission(array("Users;add_users","Users;delete_users"))){?>
                <li><a href="accounts.php">Edit</a></li>
                <?php } ?>
                <?php if(check_permission(array("Users;create_rank","Users;edit_rank","Users;delete_rank"))){?>
                <li><a href="ranks.php">Permissions</a></li>
                <?php } ?>
                <?php if(check_permission("Website","edit_site_colors")){ ?>
                <li><a href="user-settings">User Settings</a></li>
                <?php } ?>
              </ul>
            </div>
          </li>
        </ul>
      </li>
    <?php } ?>
    <!--<?php if(check_permission(array("Website;edit_site_settings","Website;upload_favicon_banner","Website;edit_google_analytics","Website;edit_socnet"))){?>
    <li><a href="site-settings.php"><i class="material-icons">&#xE8B8;</i>Settings</a></li>
	<?php } ?>-->
    <li><a href="../"><i class="material-icons">&#xE5C4;</i>Back to site</a></li>
    <li><a href="logout.php"><i class="material-icons">&#xE879;</i>Logout</a></li>
    </ul>

    </nav>
	</div>

        <div class="contentwrap container">

            <div class="content" id="contentarea">
