<?php
if($site_info['published']==0){
	redirect_to($site_info['base_url']."/under_construction.php");
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
	if(!file_exists($folder)){
		mkdir($folder);
	}
}

$banner = scandir("images/banner/");
if(isset($banner[2])){
	$banner = $banner[2];
}else{
	$banner = false;
}
$favicon = scandir("images/favicon/");
if(isset($favicon[2])){
	$favicon = $favicon[2];
}else{
	$favicon = false;
}
$logo = scandir("images/logo/");
if(isset($logo[2])){
	$logo = $logo[2];
}else{
	$logo = false;
}
$bg = scandir("images/bg/");
if(isset($bg[2])){
	$bg = $bg[2];
}else{
	$bg = false;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="theme-color" content="<?php echo $site_layout['accent_color'] ?>">

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    
    <!-- Start custom meta tags -->
    <?php if($site_info['meta_tags']!=""){
		echo $site_info['meta_tags'];
	}?>
    
    <!-- End custom meta tags -->
    <link href="<?php echo $site_info['base_url']; ?>/administrator/styles/fonts.css" rel="stylesheet" type="text/css" />
    <?php if($favicon!=false){ ?><link rel="shortcut icon" href="<?php echo $site_info['base_url']; ?>/images/favicon/<?php echo $favicon; ?>" /><?php } ?>
    <link href="<?php echo $site_info['base_url']; ?>/styles/uploadfilemulti.css" rel="stylesheet" />
    <link href="<?php echo $site_info['base_url']; ?>/styles/main.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $site_info['base_url']; ?>/styles/animate.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $site_info['base_url']; ?>/styles/materialize.css" rel="stylesheet" type="text/css" media="screen,projection"/>
    <link href="<?php echo $site_info['base_url']; ?>/prettyphoto/css/prettyPhoto.css" rel="stylesheet" type="text/css" media="screen" charset="utf-8" />
    <script src="<?php echo $site_info['base_url']; ?>/jscripts/SpryTabbedPanels.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo $site_info['base_url']; ?>/jscripts/materialize.min.js"></script>
    <script type="text/javascript" src="<?php echo $site_info['base_url']; ?>/prettyphoto/js/jquery.prettyPhoto.js" charset="utf-8"></script>
    <script src="<?php echo $site_info['base_url']; ?>/jscripts/jquery.fileuploadmulti.min.js"></script>
    <style type="text/css">
.forumtitle, tr.heading,td.heading, th.heading, .blogtitle{
			background-color:<?php echo $site_layout['sitebg_color'] ?> !important;
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
		.mobile-logo{
			background-color:<?php echo $site_layout['sitebg_color'] ?> !important;
		}
		body{
			<?php if($site_layout['use_bg_color']==1){ ?>
			background-color:<?php echo $site_layout['sitebg_color'] ?> !important;
			<?php }else{ ?>
			background-color:#FFFFFF; ?> !important;
			<?php }
			if($bg!=false){?>
			background-image:url(/images/bg/<?php echo $bg; ?>);
			<?php }
			if($site_layout['bg_repeat']!=''){ ?>
			background-repeat:<?php echo $site_layout['bg_repeat'] ?> !important;
			<?php }
			if($site_layout['bg_position']!=''){ ?>
			background-position:<?php echo $site_layout['bg_position'] ?> !important;
			<?php }
			if($site_layout['bg_size']!=''){ ?>
			background-size:<?php echo $site_layout['bg_size'] ?> !important;
			<?php }
			if($site_layout['bg_fixed']==1){ ?>
			background-attachment:fixed !important;
			<?php }else{ ?>
			background-attachment:scroll !important;
			<?php } ?>
		}
		/*#contentwrap{
			background-color:<?php echo $site_layout['contentbg_color'] ?> !important;
		}*/
		.mobile, ul.side-nav, slide-out{
			background-color:<?php echo $site_layout['accent_color'] ?> !important;
		}
		.mobile i{
			color:<?php echo $site_layout['contentbg_color'] ?> !important;
		}
		#banner{
			background-color:<?php echo $site_layout['text_color'] ?> !important;
		}
		#horiz-menu, #horiz-menu ul, #horiz-menu ul li, #vert-menu, #vert-menu li, #vert-menu ul li, .photo-link, .nav{
			background-color:<?php echo $site_layout['menu_color'] ?> !important;
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
		#horiz-menu li:hover > a, #horiz-menu li:hover > ul, #horiz-menu ul a:hover, #vert-menu li:hover > a, #vert-menu li:hover > ul, #vert-menu ul a:hover,  .TabbedPanelsTab, .accent, .forum tr:hover, .forumsuser, ul.MenuBarHorizontal a:hover, ul.MenuBarHorizontal a:focus, .forumfooter, ul.side-nav li{
			background-color:<?php echo $site_layout['accent_color'] ?> !important;
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
		*html #horiz-menu li a:hover, #vert-menu li a:hover { /* IE6 only */
			color: <?php echo $site_layout['text_color'] ?>;
		}
		#content a, #footer a, #horiz-menu a, #horiz-menu ul a, #vert-menu a, #vert-menu ul a, .blogtitle a, .forum tr a:hover{
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
		.photo-link:hover{
			background-color:<?php echo $site_layout['text_color'] ?> !important;
		}
		#content, .forum tr, .forumbody, #footerwrap, .blog{
			background-color:<?php echo $site_layout['contentbg_color'] ?> !important;
			color:<?php echo $site_layout['text_color'] ?> !important;
		}
		.forum tr a{
			color:<?php echo $site_layout['contentbg_color'] ?> !important;
		}
		.forumtitle{
			border-bottom:2px <?php echo $site_layout['text_color'] ?> dashed;
		}
		.icon{
			color:<?php echo $site_layout['menu_color'] ?> ;
		}
		 ::-webkit-scrollbar-thumb{
			/*background-color:<?php echo $site_layout['accent_color'] ?> !important;
			color:<?php echo $site_layout['text_color'] ?> !important;*/
		}
		::selection {
			background: <?php echo $site_layout['accent_color'] ?>; /* WebKit/Blink Browsers */
			}
		::-moz-selection {
			background: <?php echo $site_layout['accent_color'] ?>; /* WebKit/Blink Browsers */
			}
		#banner{
			<?php if(count($banner)==3){?>background-image:url(images/banner/<?php echo $banner[2];?>);<?php } ?>
			
		}
		/* Start custom CSS */
		<?php if($site_layout['custom_css']!=""){
			echo $site_layout['custom_css'];
		}?>
		/* End custom CSS */
	</style>
    <!-- Start custom JS -->
    <?php if($site_info['style_js_link_tags']!=""){
		echo $site_info['style_js_link_tags'];
	}?>
    
    <!-- End custom JS -->
</head>


<body>
	<?php
    if(isset($pgsettings['use_google_analytics'])&&$pgsettings['use_google_analytics'] == 1){
		if($site_info['g_analytics_enabled']==1){
			echo $site_info['g_analytics_code'];
		}
	}?>
    
	<div id="wrapper">
    	<?php nav("mobile", $pgsettings['pageselection']); ?>
        <div class="mobile">
            <a href="#" data-activates="slide-out" class="button-collapse" style="color:#6C6C6C;"><i class="mdi-navigation-menu"></i></a>
            <div style="float:right; font-size:32px; padding:10px;">
				<?php
                    foreach($enabled_soc_networks as $network){?>
                        <a href="<?php echo $site_info[$network.'_url']; ?>" style="text-decoration:none;" target="_blank"><span class="icon icon-<?php echo $soc_networks_icons[array_search($network, $soc_networks)]; ?>"></span></a>
                    <?php 
                    }
                ?>
            </div>
        </div>
    	<?php if($logo!=false||!empty($enabled_soc_networks)){ ?>
    	<div style="max-width:1880px; width:100%; height:100px; margin-left:auto; margin-right:auto;">
        	<?php if($logo!=false){ ?>
            	<div style="float:left; position:absolute;" id="logo">
                	<?php if($site_info['logo_url']!=''){?>
            		<a href="<?php echo $site_info['logo_url']; ?>"><img src="<?php echo $site_info['base_url']; ?>/images/logo/<?php echo $logo; ?>" alt="<?php echo $site_info['name']; ?> Logo" height="100" /></a>
                    <?php }else{ ?>
                    <img src="<?php echo $site_info['base_url']; ?>/images/logo/<?php echo $logo; ?>" height="100" />
                    <?php } ?>
                </div>
			<?php } ?>
            <div style="float:right; font-size:32px; padding:10px;" id="socnet">
            	<?php
					foreach($enabled_soc_networks as $network){?>
                    	<a href="<?php echo $site_info[$network.'_url']; ?>" style="text-decoration:none;" target="_blank"><span class="icon icon-<?php echo $soc_networks_icons[array_search($network, $soc_networks)]; ?>"></span></a>
					<?php 
					}
				?>
            </div>
        </div>
        <?php } ?>
        <?php
        if(!isset($pgsettings['horiz_menu_visible'])||$pgsettings['horiz_menu_visible'] == true){
        	$num_horiz_pages = nav("horiz", $pgsettings['pageselection']);
		}
		?>
        <div id="contentwrap">
			<?php if(isset($pgsettings['banner'])&&$pgsettings['banner'] == 1){ ?>
    		<?php if($banner!=false){ ?><div style="width:100%; text-align:center; margin-bottom:20px;"><img src="<?php echo $site_info['base_url']; ?>/images/banner/<?php echo $banner; ?>" width="80%" style="background-color:#C9C9C9;" /></div><?php } ?>
			<?php
			}
			?>
			<?php if(isset($pgsettings['slider'])&&$pgsettings['slider'] >= 1){
				slider($pgsettings['slider']);
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
                	<td style="padding-right:5px; padding-left:5px; vertical-align:top;">
						<div id="content" style="background-color:#525252;" class="card">
						<?php if(!empty($error)){echo "<script type=\"text/javascript\">Materialize.toast('".$error."', 8000, 'red')</script>";} ?>
                        <?php if(!empty($success)){echo "<script type=\"text/javascript\">Materialize.toast('".$success."', 8000, 'green')</script>";} ?>
                        <?php if(!empty($message)){echo "<script type=\"text/javascript\">Materialize.toast('".$message."', 8000, 'yellow')</script>";} ?>
