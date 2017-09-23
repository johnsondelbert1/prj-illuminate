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

<!-- dropzone -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.1.1/dropzone.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.1.1/dropzone.css">


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
			<?php
			require_once("./includes/nav.php");

			?>

        <div class="contentwrap container">

            <div class="content" id="contentarea">
