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

$banner = scandir("images/banner/");
$favicon = scandir("images/favicon/");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <title><?php echo $pgsettings['title']; ?></title>
    
    <META NAME="description" CONTENT="">
    <META NAME="robot" CONTENT="index,follow">
    <META NAME="copyright" CONTENT="Source Code Property of Second Generation Design, Copyright © 2011-<?php echo date("Y"); ?>">
    <META NAME="author" CONTENT="2GD - Secondgenerationdesign.com">
    <META NAME="language" CONTENT="English">
    <META NAME="revisit-after" CONTENT="7">
    <!-- Start custom meta tags -->
    <?php if($site_info['meta_tags']!=""){
		echo $site_info['meta_tags'];
	}?>
    
    <!-- End custom meta tags -->
    <link href="administrator/styles/fonts.css" rel="stylesheet" type="text/css" />
    <?php if(count($favicon)==3){ ?><link rel="shortcut icon" href="images/favicon/<?php echo $favicon[2]; ?>" /><?php } ?>
    <link href="styles/uploadfilemulti.css" rel="stylesheet" />
    <link href="styles/main.css" rel="stylesheet" type="text/css" />
    <link href="styles/animate.css" rel="stylesheet" type="text/css" />
    <link href="prettyphoto/css/prettyPhoto.css" rel="stylesheet" type="text/css" media="screen" charset="utf-8" />
    <script src="jscripts/SpryTabbedPanels.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="prettyphoto/js/jquery.prettyPhoto.js" charset="utf-8"></script>
    <script src="jscripts/jquery.fileuploadmulti.min.js"></script>
    <style type="text/css">
		.forumtitle, tr.heading,td.heading, th.heading, .blogtitle{
			background-color:<?php echo $site_layout['sitebg_color'] ?> !important;
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
		body, #contentwrap{
			background-color:<?php echo $site_layout['contentbg_color'] ?> !important;
		}
		#banner{
			background-color:<?php echo $site_layout['text_color'] ?> !important;
		}
		#horiz-menu, #horiz-menu ul, #horiz-menu ul li, #vert-menu, #vert-menu li, #vert-menu ul li, #footerwrap, h1, h2, .photo-link, .nav{
			background-color:<?php echo $site_layout['menu_color'] ?> !important;
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
		#horiz-menu li:hover > a, #horiz-menu li:hover > ul, #horiz-menu ul a:hover, #vert-menu li:hover > a, #vert-menu li:hover > ul, #vert-menu ul a:hover, .blog, .TabbedPanelsTab, .accent, .forum tr:hover, .forumsuser, ul.MenuBarHorizontal a:hover, ul.MenuBarHorizontal a:focus, .forumfooter{
			background-color:<?php echo $site_layout['accent_color'] ?> !important;
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
		*html #horiz-menu li a:hover, #vert-menu li a:hover { /* IE6 only */
			color: <?php echo $site_layout['text_color'] ?>;
		}
		#horiz-menu a, #horiz-menu ul a, #vert-menu a, #vert-menu ul a, .blogtitle a, .forum tr a:hover{
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
		.photo-link:hover{
			background-color:<?php echo $site_layout['text_color'] ?> !important;
		}
		#content, .forum tr, .forumbody{
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
			/*background-color:<?php echo $site_layout['accent_color'] ?> !important;
			color:<?php echo $site_layout['text_color'] ?> !important;*/
		}
		#banner{
			<?php if(count($banner)==3){?>background-image:url(images/banner/<?php echo $banner[2];?>);<?php } ?>
			
		}
	</style>
    <!-- Start custom CSS and JS -->
    <?php if($site_info['style_js_link_tags']!=""){
		echo $site_info['style_js_link_tags'];
	}?>
    
    <!-- End custom CSS and JS -->
</head>


<body>
	<?php
    if(isset($pgsettings['use_google_analytics'])&&$pgsettings['use_google_analytics'] == 1){
		if($site_info['g_analytics_enabled']==1){
			echo $site_info['g_analytics_code'];
		}
	}?>
    
	<div id="wrapper">
        <?php
        if(!isset($pgsettings['horiz_menu_visible'])||$pgsettings['horiz_menu_visible'] == true){
			?><div class="nav" style="background-color:<?php echo $site_layout['menu_color'] ?>;"><?php
        		$num_horiz_pages = nav("horiz", $pgsettings['pageselection']);
			?></div><?php
		}
		?>
        <div id="contentwrap">
			<?php if(isset($pgsettings['banner'])&&$pgsettings['banner'] == 1){ ?>
    		<?php if(isset($banner[2])){ ?><div style="width:100%; text-align:center; margin-bottom:20px;"><img src="images/banner/<?php echo $banner[2]; ?>" width="80%" style="background-color:#C9C9C9;" /></div><?php } ?>
			<?php
			}
			?>
			<?php if(isset($pgsettings['slider'])&&$pgsettings['slider'] == 1){
				slider();
			}
			?>
            <table border="0" cellspacing="0" cellpadding="0" style="width:100%;">
                <tr>
                    <?php
                    if(!isset($pgsettings['vert_menu_visible'])||$pgsettings['vert_menu_visible'] == true){
                        ?><?php
                            $num_vert_pages = nav("vert", $pgsettings['pageselection']);
                        ?><?php
                    }
                    ?>
                	<td style="padding-right:5px; padding-left:5px;">
						<div id="content" style="background-color:#525252;">
						<?php if(!empty($error)){echo "<h3 class=\"error\">".$error."</h3>";} ?>
                        <?php if(!empty($success)){echo "<h3 class=\"success\">".$success."</h3>";} ?>
                        <?php if(!empty($message)){echo "<h3 class=\"message\">".$message."</h3>";} ?>