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

$banner = scandir("../images/banner/");
if(isset($banner[2])){
	$banner = $banner[2];
}else{
	$banner = false;
}
$favicon = scandir("../images/favicon/");
if(isset($favicon[2])){
	$favicon = $favicon[2];
}else{
	$favicon = false;
}
$logo = scandir("../images/logo/");
if(isset($logo[2])){
	$logo = $logo[2];
}else{
	$logo = false;
}
$bg = scandir("../images/bg/");
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
    
    <META NAME="description" CONTENT="">
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
    <script src="jscripts/SpryTabbedPanels.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    
    <link href="../materialize/css/materialize.css" rel="stylesheet" type="text/css" media="screen,projection"/>
    <link href="styles/materialize-override.css" rel="stylesheet" type="text/css" media="screen,projection"/>
    <link href="styles/main.css" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href="styles/fonts.css" rel="stylesheet" type="text/css" />
    <link href="../styles/animate.css" rel="stylesheet" type="text/css" />
    <!--<link type="text/css" rel="stylesheet" href="styles/materialize.css"  media="screen,projection"/>-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
</head>


<body>
    <div class="wrap">
    	<div class="nav">
        	<div style="background-color:#F0F0F0;">
           <!-- <span style="float:left; line-height:30px; padding-left:10px;"><b>Logged in as: <?php echo $_SESSION['username']; ?></b></span> -->
                <ul id="horiz-menu" style="display:block;">
                    <li>
                    	<a href="../" target="_blank">Back</a>
                    </li>
                    <li>
                    	<a href="index.php">Dashboard</a>
                    </li>
                    <?php if(check_permission(array("Pages;add_pages","Pages;edit_pages","Pages;delete_pages","Galleries;add_gallery","Galleries;edit_gallery","Galleries;delete_gallery","Galleries;rename_gallery","Uploading;upload_files","Uploading;delete_files","Uploading;create_folders","Uploading;rename_folders","Uploading;delete_folders","Sliders;add_slider","Sliders;edit_slider","Sliders;delete_slider","Sliders;rename_slider",))){?>
                        <li>
                            <a href="#">Content<span class="mdi-navigation-arrow-drop-down"></a>
                            <ul>
                            <li>
                                    <a href="#">Pages<span class="mdi-navigation-arrow-drop-down"></span></a>
                                    <ul>
                                    <li><a href="page_list.php">Edit Pages</a></li>
                                        <?php if(check_permission("Pages","add_pages")){?><li><a href="edit_page.php?action=newpage">Create Page</a></li><?php } ?>
                                        
                                       
                                    </ul>
                                </li>
                                <li>
                            <a href="#">Images<span class="mdi-navigation-arrow-drop-down"></span></a>
                            <ul>
								<?php if(check_permission(array("Galleries;add_gallery","Galleries;edit_gallery","Galleries;delete_gallery","Galleries;rename_gallery"))){?>
                                <li>
                                    <a href="gallery-list.php">Galleries</a>
                                </li>
                                <?php } ?>
                                 <?php if(check_permission(array("Sliders;add_slider","Sliders;edit_slider","Sliders;delete_slider","Sliders;rename_slider",))){?><li><a href="slider-list.php">Slider</a></li><?php } ?>
                                 <li>
                    	<a href="site-settings.php?tab=2#">Settings</a>
                    </li>
                                </ul>
                                </li>
                                <?php if(check_permission(array("Uploading;upload_files","Uploading;delete_files","Uploading;create_folders","Uploading;rename_folders","Uploading;delete_folders",))){?>
                                <li>
                                    <a href="upload-files.php">Upload</a>
                                </li>
                                <?php } ?>
                                <?php if(check_permission(array("Forms;create_form","Forms;edit_form","Forms;delete_form",))){?>
                    <li>
                    	<a href="form-list.php">Forms</a>
                    </li>
                    <?php } ?>
                    
                                
                        	</ul>
                        </li>
                    <?php } ?>
                    <?php if(check_permission(array("Users;add_users","Users;delete_users","Users;create_rank","Users;edit_rank","Users;delete_rank","Users;approve_deny_new_users"))){?>
                    <li>
                    	<a href="#">Users<span class="mdi-navigation-arrow-drop-down"></a>
                        <ul>
                        <?php if(check_permission(array("Users;add_users","Users;delete_users"))){?><li><a href="accounts.php">Edit Accounts</a></li><?php } ?>
                        <?php if(check_permission("Users","approve_deny_new_users") && $GLOBALS['site_info']['user_creation'] == 'approval'){?><li><a href="approval-list">Approve/Deny (<?php echo $pending_users; ?>)</a></li><?php } ?>
                        <?php if(check_permission(array("Users;create_rank","Users;edit_rank","Users;delete_rank",))){?>
                        <li>
                        	<a href="ranks.php">Permissions</a>
                        </li>
                        <?php } ?>
                        <?php if(check_permission("Pages","edit_pages")){?><li><a href="staff-list.php">Staff</a></li><?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if(check_permission("Website","edit_site_colors")){?>
                    <li style="width:55px;">
                        <a href="user-settings"><span class="icon-users"></span></a>
                    </li>
                    <?php } ?>
                    <?php if(check_permission("Website","edit_site_colors")){?>
                    <li style="width:55px;">
                        <a href="edit-colors"><span class="icon-palette"></span></a>
                    </li>
                    <?php } ?>
                    <?php if(check_permission(array("Website;edit_site_settings","Website;upload_favicon_banner","Website;edit_google_analytics",))){?>
                    <li style="width:55px;">
                    	<a href="site-settings.php"><span class="icon-cog"></span></a>
                    </li>
                    <?php } ?>
                    <li style="width:55px;">
                    	<a href="logout.php"><span class="icon-exit"></span></a>
                    </li>
                </ul>
            </div>
            </div>
            <!--
            <ul id="menu">
                <li class="menuitem">
                    <a href="index.php"><span class="icon-checkmark"></span></a>
                </li>
                <li class="menuitem">
                    <a href="index.php"><span class="icon-blocked"></span></a>
                </li>
                <li class="menuitem">
                    <a href="index.php"><span class="icon-remove"></span></a>
                </li>
                <li class="menuitem">
                    <a href="index.php"><span class="icon-cog"></span></a>
                </li>
                    </ul>
                    -->
        <div class="contentwrap">
            <div class="title">
                <h1><span class="<?php echo $pgsettings['icon']; ?>"></span>   <?php echo $pgsettings['title']; ?></h1>
            </div>
            <div class="content" id="contentarea">
            
            <!--<script src="jscripts/jquery.sortable.js"></script>-->
            <!--<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
            <script type="text/javascript">
            	 <!-- jQuery Sticky Menu -->

			</script>