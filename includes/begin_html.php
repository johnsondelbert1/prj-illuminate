<?php
if($site_info['published']==0){
	redirect_to("under_construction.php");
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
    <link href="administrator/styles/fonts.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="images/favicon.png" />
    <link href="styles/uploadfilemulti.css" rel="stylesheet" />
    <link href="styles/main.css" rel="stylesheet" type="text/css" />
    <link href="styles/animate.css" rel="stylesheet" type="text/css" />
    <link href="prettyphoto/css/prettyPhoto.css" rel="stylesheet" type="text/css" media="screen" charset="utf-8" />
    <script src="jscripts/SpryTabbedPanels.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="prettyphoto/js/jquery.prettyPhoto.js" charset="utf-8"></script>
    <script src="jscripts/jquery.fileuploadmulti.min.js"></script>
    <style type="text/css">
		body, .forumtitle, tr.heading,td.heading, th.heading, .blogtitle{
			background-color:<?php echo $site_layout['sitebg_color'] ?> !important;
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
		#contentwrap{
			background-color:<?php echo $site_layout['contentbg_color'] ?> !important;
		}
		#banner{
			background-color:<?php echo $site_layout['text_color'] ?> !important;
		}
		#horiz-menu, #horiz-menu ul, #horiz-menu ul li, #footerwrap, h1, h2, .photo-link, .nav{
			background-color:<?php echo $site_layout['menu_color'] ?> !important;
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
		#horiz-menu li:hover > a, #horiz-menu li:hover > ul, #horiz-menu ul a:hover, .blog, .TabbedPanelsTab, .accent, .forum tr:hover, .forumsuser, ul.MenuBarHorizontal a:hover, ul.MenuBarHorizontal a:focus, .forumfooter{
			background-color:<?php echo $site_layout['accent_color'] ?> !important;
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
		*html #horiz-menu li a:hover { /* IE6 only */
			color: <?php echo $site_layout['text_color'] ?>;
		}
		#horiz-menu a, #horiz-menu ul a, .blogtitle a, .forum tr a:hover{
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
		.photo-link:hover{
			background-color:<?php echo $site_layout['text_color'] ?> !important;
		}
		.forum tr, .forumbody{
			background-color:<?php echo $site_layout['menu_color'] ?> !important;
			color:<?php echo $site_layout['contentbg_color'] ?> !important;
		}
		.forum tr a{
			color:<?php echo $site_layout['contentbg_color'] ?> !important;
		}
		.forumtitle{
			border-bottom:2px <?php echo $site_layout['text_color'] ?> dashed;
		}
		 ::-webkit-scrollbar-thumb{
			background-color:<?php echo $site_layout['accent_color'] ?> !important;
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
	</style>
</head>


<body>
	<?php
    if(isset($pgsettings['use_google_analytics'])&&$pgsettings['use_google_analytics'] == 1){
		if($site_info['g_analytics_enabled']==1){
			echo $site_info['g_analytics_code'];
		}
	}?>
	<div id="wrapper"> 
    	
        <?php if($pgsettings['nav'] == true){ ?>
        	<div class="nav">
        <?php
        	nav("horiz", $pgsettings['pageselection']);
		}
		?>
		<?php if($pgsettings['banner'] == 1){ ?>
    		<div id="banner"><!--<img src="images/banner.png" />--></div>
        <?php } ?>
		<?php if(!empty($error)){echo "<h3 class=\"error\">".$error."</h3>";} ?>
        <?php if(!empty($success)){echo "<h3 class=\"success\">".$success."</h3>";} ?>
        <?php if(!empty($message)){echo "<h3 class=\"message\">".$message."</h3>";} ?>