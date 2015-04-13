<?php
require_once("../includes/functions.php");
?>
<?php
if(logged_in()&&!check_permission("Website","cpanel_access")){
	redirect_to("../index.php?error=".urlencode("You do not have permission to access that!"));
}elseif(logged_in()){
	redirect_to("index.php");
}
if(isset($_GET['error'])){
	$message="<h3 class=\"error animate shake\">".urldecode($_GET['error'])."</h3>";
}
if(isset($_GET['success'])){
	$message="<h3 class=\"success\">".urldecode($_GET['success'])."</h3>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Basic Page Needs
  ================================================== -->
    <meta charset="utf-8">
    <title>Login to <?php echo $site_info['name']; ?> CPanel</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="images/favicon.png" />
    <link href="../styles/animate.css" rel="stylesheet" type="text/css" />
    <link type="text/css" rel="stylesheet" href="./styles/materialize.css"  media="screen,projection"/>

    <!-- Mobile Specific Metas
  ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSS
  ================================================== -->

    <!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
<style type="text/css">
	@import url(http://fonts.googleapis.com/css?family=Roboto:300);
	@import url(http://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.css);
	
	body {
	  /*background-image: url(images/blue-polygon.jpg);*/
	  background-color:#333;
	  color: fff;
	  font-family: 'Roboto';
	}
	.content {
	  padding:25px;
	  width:300px;
	  
	}
	.flat-form {
		text-align: center;
	   width: 300px;
	   height: 627px;
	   margin: auto;
  position: absolute;
  top: 0; left: 0; bottom: 0; right: 0;
	  font-size:13px;
	  color:#FFF;
	  
	}
	.form-action {
	  padding: 0 20px;
	  position: relative;
	}
	
	.flat-form h1 {
	  font-size: 16px;
	  padding-bottom: 10px;
	}
	.flat-form p {
	  padding: 5px;
	}
</style>
</head>
<body>
    <div class="container">
        <div class="flat-form">
        <div class="content">
 <img src="images/logo.png"  alt=""/></div>
            <div id="login" class="form-action show">
            	<?php if(!empty($message)){echo $message;} ?>
                <h1><?php echo $site_info['name']; ?></h1>
                <div class="row">
				<form method="post" action="../login/login.php" class="col s12" >
                <input type="hidden" name="redirect_to" value="administrator/index.php"/>
                <input type="hidden" name="redirect_from" value="<?=($_SERVER['PHP_SELF'])?>"/>
                <div class="row">
                <div class="input-field col s12">
                            <input type="text" name="username" placeholder="Username" tabindex="1"/>
                            </div>
                            </div>
                            <div class="row">
                            <div class="input-field col s12">
                            <input type="password" name="password" placeholder="Password" tabindex="2"/>
                            </div>
                            </div>
                            <div class="row">
                            <div class="input-field col s6">
                            <input type="submit" name="submit" value="Sign in" class="btn red" tabindex="4"/>
                            </div>
                            <div class="input-field col s6">
                            <input name="remember" id="remember" type="radio" value="" tabindex="3"/><label for="remember">Remember</label>
                            </div>
                            </div>
                </form>
                </div>
            </div>
            <!--/#register.form-action-->
        </div>
    </div>
</body>
</html>