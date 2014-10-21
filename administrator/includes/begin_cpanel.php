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
    <!-- Loading Bootstrap -->
    <!--<link href="../styles/drop/bootstrap.css" rel="stylesheet">-->

    <!-- Loading Flat UI -->
    <!--<link href="../styles/drop/flat-ui.css" rel="stylesheet">-->
    
    <link rel="shortcut icon" href="../images/favicon.png">
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>-->
    <script type="text/javascript" src="../jscolor/jscolor.js"></script>
    <script src="jscripts/SpryMenuBar.js" type="text/javascript"></script>
    <link href="../styles/uploadfilemulti.css" rel="stylesheet">
    <link href="styles/main.css" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
    <link href="styles/fonts.css" rel="stylesheet" type="text/css" />
    <link href="../styles/animate.css" rel="stylesheet" type="text/css" />

</head>


<body>

    <div class="wrap">
    	<div class="nav">
        	<div style="background-color:#F0F0F0;">
            <span style="float:left;"><?php check_login(true); ?></span>
                <ul id="horiz-menu">
                    <li class="menuitem">
                    	<a href="index.php">Control Panel</a>
                    </li>
                    <?php if(check_permission(array("Pages;add_pages","Pages;edit_pages","Pages;delete_pages",))){?>
                    <li class="menuitem">
                    	<a href="page_list.php">Pages</a>
                        <?php if(check_permission("Pages","add_pages")){?>
                        <ul>
                        	<a href="edit_page.php?action=newpage">Add Pages</a>
                        </ul>
                        <?php } ?>
                    </li>
                    <?php } ?>
                    <?php if(check_permission(array("Galleries;add_gallery","Galleries;edit_gallery","Galleries;delete_gallery","Galleries;rename_gallery"))){?>
                    <li class="menuitem">
                    	<a href="gallery-list.php">Galleries</a>
                    </li>
                    <?php } ?>
                    <?php if(check_permission(array("Uploading;upload_files","Uploading;delete_files","Uploading;create_folders","Uploading;rename_folders","Uploading;delete_folders",))){?>
                    <li class="menuitem">
                    	<a href="upload-files.php">Upload</a>
                    </li>
                    <?php } ?>
                    <?php if(check_permission(array("Users;add_users","Users;delete_users",))){?>
                    <li class="menuitem">
                    	<a href="accounts.php">Accounts</a>
                    </li>
                    <?php } ?>
                    <?php if(check_permission(array("Users;create_rank","Users;edit_rank","Users;delete_rank",))){?>
                    <li class="menuitem">
                    	<a href="ranks.php">Ranks</a>
                    </li>
                    <?php } ?>
                    <?php if(check_permission(array("Forms;create_form","Forms;edit_form","Forms;delete_form",))){?>
                    <li class="menuitem">
                    	<a href="form-list.php">Forms</a>
                    </li>
                    <?php } ?>
                    <?php if(check_permission(array("Website;edit_site_settings","Website;edit_site_colors","Website;upload_favicon_banner","Website;edit_google_analytics",))){?>
                    <li class="menuitem">
                    	<a href="site-settings.php">Site Settings</a>
                    </li>
                    <?php } ?>
                    <li class="menuitem">
                    	<a href="logout.php" style="color: #F85050;">Logout</a>
                    </li>
                </ul>
            </div>
            <!--<ul id="menu">
                <li class="menuitemnohover" style="height:auto; font-size:12px; line-height:30px; text-align:center; background-color:#ccc;">
                <img src="images/logo2GD.png" width="50" height="50" style="margin-top:10px;" /><br />
                    <?php //check_login(true); ?>
                <!--</li>
                <li class="menuitem">
                    <a href="index.php"><span class="icon-dashboard"></span><br /><h3>Control Panel</h3></a>
                </li>
                <li class="menuitem">
                    <a href="page_list.php"><span class="icon-newspaper"></span><br /><h3>Pages</h3></a>
                </li>
                <li class="menuitem">
                    <a href="gallery-list.php"><span class="icon-images"></span><br /><h3>Galleries</h3></a>
                </li>
                <li class="menuitem">
                    <a href="upload-files.php"><span class="icon-upload"></span><br /><h3>Upload</h3></a>
                </li>
                <li class="menuitem">
                    <a href="accounts.php"><span class="icon-user3"></span><br /><h3>Accounts</h3></a>
                </li>
                <li class="menuitem">
                    <a href="ranks.php"><span class="icon-flag"></span><br /><h3>Ranks</h3></a>
                </li>
                <li class="menuitem">
                    <a href="site-settings.php"><span class="icon-code"></span><br /><h3>Site Settings</h3></a>
                </li>
                <li class="menuitem">
                    <a href="logout.php"><span class="icon-switch"></span><h3>Logout</h3></a>
                </li>
                <li class="menuitemnohover" style="height:auto; font-size:8px; text-align:center;">
                    <img src="images/logo.png" width="50" style="margin-top:10px;" /><br /><br />
                    illuminateCMS<br />© 2011-<?php echo date("Y") ?>. V.1.0
                </li>
            </ul>-->
            <div style="display:table-row; height:100%;"></div>
        </div>
        <div class="contentwrap">
            <div class="title">
                <h1><span class="<?php echo $pgsettings['icon']; ?>"></span>   <?php echo $pgsettings['title']; ?></h1>
            </div>
            <div class="content" id="contentarea">
            <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
            <script type="text/javascript">
            	 <!-- jQuery Sticky Menu -->
                $(document).ready(function() {
                    var s = $("#sticker");
                    var pos = s.position();                    
                    $(window).scroll(function() {
                        var windowpos = $(window).scrollTop();
                        if (windowpos >= pos.top) {
                            s.addClass("stick");
                            $('.content').css("padding-top","58px");
                        } else {
                            s.removeClass("stick");
                            $('.content').css("padding-top","5px");
                        }
                    });
                });
			</script>
            <?php if(!empty($error)){echo "<h3 class=\"error\">".$error."</h3>";} ?>
			<?php if(!empty($success)){echo "<h3 class=\"success\">".$success."</h3>";} ?>
			<?php if(!empty($message)){echo "<h3 class=\"message\">".$message."</h3>";} ?>