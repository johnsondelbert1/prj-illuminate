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
	  background-image: url(images/blue-polygon.jpg);
	  color: fff;
	  font-family: 'Roboto';
	}
	.content {
	  height: 50px;
	  width: 100%;
	  margin:0 auto 25px;
	  text-align:center;
	  padding-top:10%;
	  
	}
	.flat-form {
	background-color:#f7f7f7;
	-webkit-border-radius:2px;
	border-radius:2px;
	  padding: 40px 40px;
	  width: 400px;
	  height: 400px;
	  margin:0 auto 25px;
	  font-size:13px;
	  
	}
	.form-action {
	  padding: 0 20px;
	  position: relative;
	}
	
	.flat-form h1 {
	  font-size: 40px;
	  padding-bottom: 10px;
	}
	@media all and (max-width: 660px){
 .flat-form h1 {
	  font-size: 30px;
	}
}
	@media all and (max-width: 470px){
 .flat-form h1 {
	  font-size: 20px;
	}
}
	.flat-form p {
	  padding: 5px;
	}
	form {
	  padding-right: 20px !important;
	}

	form input[type=text],
	form input[type=password] {
	  width: 100%;
	  height: 40px;
	  margin-bottom: 10px;
	  padding-left: 15px;
	  background: #fff;
	border-top:1px solid #a0a0a0;
	border:2px solid #426FC5;
	  color: #426FC5;
	  outline:none;
	  font-size:15px;
	  font-weight:bold;
	}
	::-webkit-input-placeholder {
   color: #426FC5;
}

:-moz-placeholder { /* Firefox 18- */
   color: #426FC5;  
}

::-moz-placeholder {  /* Firefox 19+ */
   color: #426FC5;  
}

:-ms-input-placeholder {  
   color: #426FC5;  
}
	
	.button {
		font-size:15px;
		border: none;
		display: block;
		background: #426FC5;
		height: 40px;
		width: 100%;
		color: #ffffff;
		text-align: center;
		border-radius: 2px;
		/*box-shadow: 0px 3px 1px #2075aa;*/
		-webkit-transition: all 0.15s linear;
		  -moz-transition: all 0.15s linear;
		  transition: all 0.15s linear;
		  right:auto;
		  left:auto;
	}
	
	.button:hover {
	  background: #2980b9;
	  /*box-shadow: 0 3px 1px #237bb2;*/
	}
	
	.button:active {
	  background: #136899;
	  /*box-shadow: 0 3px 1px #0f608c;*/
	}
	
</style>
</head>
<body>
<div class="content">
</div>
    <div class="container">
        <div class="flat-form">
            <div id="login" class="form-action show">
            	<?php if(!empty($message)){echo $message;} ?>
                <h1><?php echo $site_info['name']; ?></h1>
				<form method="post" action="../login/login.php">
                <input type="hidden" name="redirect_to" value="administrator/index.php"/>
                <input type="hidden" name="redirect_from" value="<?=($_SERVER['PHP_SELF'])?>"/>
            		<ul>
                        <li>
                            <input type="text" name="username" placeholder="Username" tabindex="1"/>
                        </li>
                        <li>
                            <input type="password" name="password" placeholder="Password" tabindex="2"/>
                        </li>
                        <li>
                            <input type="submit" name="submit" value="Sign in" class="button" tabindex="4"/>
                        </li>
                        <p><li>
                            <input name="remember" id="remember" type="checkbox" value="" tabindex="3"/><label for="remember">Remember Me</label><br><br>
                        </li></p>
                        
                    </ul>
                </form>
            </div>
            <!--/#register.form-action-->
        </div>
    </div>
</body>
</html>