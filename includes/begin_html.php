<?php
if($GLOBALS['site_info']['published']==0&&!check_permission("Website","unpublished_access")){
	redirect_to($GLOBALS['HOST']."/under_construction.php");
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

$banner = scandir(USER_DIR."site-img/banner/");
if(isset($banner[2])){
	$banner = $banner[2];
}else{
	$banner = false;
}
$favicon = scandir(USER_DIR."site-img/favicon/");
if(isset($favicon[2])){
	$favicon = $favicon[2];
}else{
	$favicon = false;
}
$logo = scandir(USER_DIR."site-img/logo/");
if(isset($logo[2])){
	$logo = $logo[2];
}else{
	$logo = false;
}
$bg = scandir(USER_DIR."site-img/bg/");
if(isset($bg[2])){
	$bg = $bg[2];
}else{
	$bg = false;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <title><?php echo $pgsettings['title']; ?></title>
    
    <META NAME="description" CONTENT="<?php echo $GLOBALS['site_info']['site_description']; ?>">
    <META NAME="robot" CONTENT="index,follow">
    <META NAME="copyright" CONTENT="Source Code Property of Second Generation Design, Copyright © 2011-<?php echo date("Y"); ?>">
    <META NAME="author" CONTENT="2GD - Secondgenerationdesign.com">
    <META NAME="language" CONTENT="English">
    <META NAME="revisit-after" CONTENT="7">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="theme-color" content="<?php echo get_color('android_tab'); ?>">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    
    <!-- Start custom meta tags -->
    <?php if($GLOBALS['site_info']['meta_tags']!=""){
		echo $GLOBALS['site_info']['meta_tags'];
	}?>
    <!-- End custom meta tags -->

    <link href="<?php echo $GLOBALS['HOST']; ?>/materialize/css/materialize.min.css" rel="stylesheet" type="text/css" media="screen,projection"/>
    <link href="<?php echo $GLOBALS['HOST']; ?>/styles/materialize-override.css" rel="stylesheet" type="text/css" media="screen,projection"/>
    <link href="<?php echo $GLOBALS['HOST']; ?>/administrator/styles/fonts.css" rel="stylesheet" type="text/css" />
    <?php if($favicon!=false){ ?><link rel="shortcut icon" href="<?php echo USER_DIR_URL; ?>site-img/favicon/<?php echo $favicon; ?>" /><?php } ?>
    <link href="<?php echo $GLOBALS['HOST']; ?>/styles/lightgallery.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $GLOBALS['HOST']; ?>/styles/main.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $GLOBALS['HOST']; ?>/styles/animate.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['HOST']; ?>/lib/cropper-master/dist/cropper.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['HOST']; ?>/SpryAssets/SpryTabbedPanels.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo $GLOBALS['HOST']; ?>/js/lightgallery.js" type="text/javascript"></script>
    <script src="<?php echo $GLOBALS['HOST']; ?>/js/lg-video.min.js" type="text/javascript"></script>
    <script src="<?php echo $GLOBALS['HOST']; ?>/js/lg-thumbnail.min.js" type="text/javascript"></script>
    <script src="<?php echo $GLOBALS['HOST']; ?>/js/lg-autoplay.min.js" type="text/javascript"></script>
    <script src="<?php echo $GLOBALS['HOST']; ?>/materialize/js/materialize.min.js"></script>
	<script src="<?php echo $GLOBALS['HOST']; ?>/lib/cropper-master/dist/cropper.min.js" type="text/javascript"></script>
    <script src="<?php echo $GLOBALS['HOST']; ?>/jscripts/autosize.js"></script>
    <script src="<?php echo $GLOBALS['HOST']; ?>/SpryAssets/SpryTabbedPanels.js"></script>


    <style type="text/css">
		body{
			<?php if($GLOBALS['site_layout']['use_bg_color']!=1){ ?>
			background-color:#FFFFFF; !important;
			<?php }
			if($bg!=false){?>
			background-image:url(<?php echo USER_DIR_URL.'site-img/bg/'.$bg; ?>);
			<?php }
			if($GLOBALS['site_layout']['bg_repeat']!=''){ ?>
			background-repeat:<?php echo $GLOBALS['site_layout']['bg_repeat'] ?> !important;
			<?php }
			if($GLOBALS['site_layout']['bg_position']!=''){ ?>
			background-position:<?php echo $GLOBALS['site_layout']['bg_position'] ?> !important;
			<?php }
			if($GLOBALS['site_layout']['bg_size']!=''){ ?>
			background-size:<?php echo $GLOBALS['site_layout']['bg_size'] ?> !important;
			<?php }
			if($GLOBALS['site_layout']['bg_fixed']==1){ ?>
			background-attachment:fixed !important;
			<?php }else{ ?>
			background-attachment:scroll !important;
			<?php } ?>
		}
		<?php
		//Custom Colors

		$query="SELECT DISTINCT `style_color_id` FROM `css_selectors` ORDER BY `style_color_id` ASC";
		$result=mysqli_query( $connection, $query);

		while ($unique_id = mysqli_fetch_array($result)) {
			$css_query="SELECT * FROM `css_selectors` WHERE `style_color_id` = {$unique_id['style_color_id']}";
			$css_result=mysqli_query( $connection, $css_query);

			$color_query="SELECT `color_hex` FROM `style_colors` WHERE `cid` = {$unique_id['style_color_id']}";
			$color_result=mysqli_query( $connection, $color_query);
			$color = mysqli_fetch_array($color_result);

			$bg = array();
			$text = array();

			while ($css_selector = mysqli_fetch_array($css_result)){
				switch ($GLOBALS['color_styles'][$css_selector['s_name']]['type']) {
					case 'bg':
						array_push($bg, $css_selector['s_name']);
						break;
					case 'text':
						array_push($text, $css_selector['s_name']);
						break;
				}
			}

			if(!empty($bg)){
				$items = count($bg);
				$i = 1;
				foreach ($bg as $value) {
					echo $GLOBALS['color_styles'][$value]['selector'];
					if($i < $items){echo ', ';}else{echo "{\r\n";}
					$i++;
				}
				echo "\t".'background-color: '.$color['color_hex'].';'."\r\n}\r\n";
			}
			if(!empty($text)){
				$items = count($text);
				$i = 1;
				foreach ($text as $value) {
					echo $GLOBALS['color_styles'][$value]['selector'];
					if($i < $items){echo ', ';}else{echo "{\r\n";}
					$i++;
				}
				echo "\t".'color: '.$color['color_hex'].';'."\r\n}\r\n";
			}
		}

		?>
		::selection{
			background-color: <?php echo get_color('selection'); ?>;
		}
		::-moz-selection{
			background-color: <?php echo get_color('selection'); ?>;
		}
		::-webkit-input-placeholder {
		   color: <?php echo get_color('input_placeholder'); ?>;
		}
		:-moz-placeholder { /* Firefox 18- */
		   color: <?php echo get_color('input_placeholder'); ?>;  
		}
		::-moz-placeholder {  /* Firefox 19+ */
		   color: <?php echo get_color('input_placeholder'); ?>;  
		}
		:-ms-input-placeholder {  
		   color: <?php echo get_color('input_placeholder'); ?>;  
		}
		#banner{
			<?php if(count($banner)==3){?>background-image:url(images/banner/<?php echo $banner[2];?>);<?php } ?>
			
		}
		/* Start custom CSS */
		<?php if($GLOBALS['site_layout']['custom_css']!=""){
			echo $GLOBALS['site_layout']['custom_css'];
		}?>
		/* End custom CSS */
	</style>
    <!-- Start custom JS -->
    <?php if($GLOBALS['site_info']['custom_js']!=""){
		echo $GLOBALS['site_info']['custom_js'];
	}?>
    
    <!-- End custom JS -->
    								
				
</head>


<body>
	<?php
    if(isset($pgsettings['use_google_analytics'])&&$pgsettings['use_google_analytics'] == 1){
		if($GLOBALS['site_info']['g_analytics_enabled']==1){
			echo $GLOBALS['site_info']['g_analytics_code'];
		}
	}?>
    
	<div id="wrapper">
    	<?php nav("mobile", $pgsettings['pageselection']); ?>
        <div class="mobile">
            <a href="#" data-activates="slide-out" class="button-collapse" style="color:#6C6C6C;"><i class="mdi-navigation-menu"></i></a>
            
            <?php if($logo!=false){ ?>
                <?php if($GLOBALS['site_info']['logo_url']!=''){?>
                <a href="<?php echo $GLOBALS['HOST']; ?>" class="brand-logo"><img src="<?php echo USER_DIR_URL; ?>site-img/logo/<?php echo $logo; ?>" alt="<?php echo $GLOBALS['site_info']['name']; ?> Logo" /></a>
                <?php }else{ ?>
                <img src="<?php echo USER_DIR_URL; ?>site-img/logo/<?php echo $logo; ?>" />
            <?php } 
            } ?>

            <!--<div style="float:right; font-size:32px; padding:10px;">
				<?php
                    foreach($GLOBALS['enabled_soc_networks'] as $network){?>
                        <a href="<?php echo $GLOBALS['site_info'][$network.'_url']; ?>" style="text-decoration:none;" target="_blank"><span class="icon icon-<?php echo $GLOBALS['soc_networks_icons'][array_search($network, $GLOBALS['soc_networks'])]; ?>"></span></a>
                    <?php 
                    }
                ?>
            </div> -->
        </div>
    	<!-- <?php if($logo!=false||!empty($GLOBALS['enabled_soc_networks'])){ ?>
    	 <div class="header"><span style="font-size:47px; margin-left:15px;" class="icon icon-facebook2 headericon"></span>
        	<?php if($logo!=false){ ?>
            	<div style="float:left; position:absolute;" id="logo">
                	<?php if($GLOBALS['site_info']['logo_url']!=''){?>
            		<a href="<?php echo $GLOBALS['HOST']; ?>"><img src="<?php echo USER_DIR_URL; ?>site-img/logo/<?php echo $logo; ?>" alt="<?php echo $GLOBALS['site_info']['name']; ?> Logo" height="100" /></a>
                    <?php }else{ ?>
                    <img src="<?php echo $GLOBALS['HOST'].'/'.USER_DIR; ?>site-img/logo/<?php echo $logo; ?>" height="100" />
                    <?php } ?>
                </div>
			<?php } ?>
            <div style="float:right; font-size:32px; padding:10px;" id="socnet">
            	<?php
					foreach($GLOBALS['enabled_soc_networks'] as $network){?>
                    	<a href="<?php echo $GLOBALS['site_info'][$network.'_url']; ?>" style="text-decoration:none;" target="_blank"><span class="icon icon-<?php echo $GLOBALS['soc_networks_icons'][array_search($network, $GLOBALS['soc_networks'])]; ?>"></span></a>
					<?php 
					}
				?>
            </div>
        </div>
        <?php } ?> -->
        <?php
        if(!isset($page['horiz_menu_visible'])||$page['horiz_menu_visible'] == true){
        	$num_horiz_pages = nav("horiz", $pgsettings['pageselection']);
		}
		?>
        <div id="contentwrap">
        
			<?php if(isset($pgsettings['banner'])&&$pgsettings['banner'] == 1){ ?>
    		<?php if($banner!=false){ ?><div class="row"><div class="col l12 s12" style="padding:0 0 !important;"><img src="<?php echo USER_DIR_URL; ?>site-img/banner/<?php echo $banner; ?>" width="100%" /></div></div><?php } ?>
			<?php
			}
			?>
			<?php if(isset($pgsettings['slider'])&&$pgsettings['slider'] >= 1){
				slider($pgsettings['slider']);
			}
			?>
            <div class="">
                <div class="row">
                    <?php
                    if(!isset($page['vert_menu_visible'])||$page['vert_menu_visible'] == true){
                        ?><?php
                            $num_vert_pages = nav("vert", $pgsettings['pageselection']);
                        ?><?php
                    }else{
						$num_vert_pages = 0;
					}
                    ?>
                 
                	<div class="col <?php
					if($num_vert_pages == 0){
									echo 'l12';	
									}
						else{
									echo 'l10';	
									}
					 ?> s12" >
                    <div id="content">
						<div class="card custom">
