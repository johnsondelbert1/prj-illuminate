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
	  background: #fff;
	  color: #404040;
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
	box-shadow:0px 2px 2px rgba(0, 0, 0, 0.3);
	-webkit-border-radius:2px;
	border-radius:2px;
	  padding: 40px 40px;
	  width: 274px;
	  height: 300px;
	  margin:0 auto 25px;
	  font-size:13px;
	  
	}
	.flat-form img{
	-webkit-border-radius:50%;
	border-radius:50%;
	height:96px;
	width:96px;
	padding:10px;
	left: auto;
	right: auto;
	display: block;
	vertical-align:middle;
	margin:0 auto 10px;
	  
	}
	.form-action {
	  padding: 0 20px;
	  position: relative;
	}
	
	.content h1 {
	  font-size: 40px;
	  padding-bottom: 10px;
	}
	@media all and (max-width: 660px){
 .content h1 {
	  font-size: 30px;
	}
}
	@media all and (max-width: 470px){
 .content h1 {
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
	border:1px solid #b9b9b9;
	  color: #333;
	  outline:none;
	  font-size:15px;
	}
	
	.button {
		font-size:15px;
		border: none;
		display: block;
		background: #3498db;
		height: 40px;
		width: 231px;;
		color: #ffffff;
		text-align: center;
		border-radius: 5px;
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
	
	::-webkit-input-placeholder {
	  color: #333;
	}
	:-moz-placeholder {
	  /* Firefox 18- */
	  color: #333;
	}
	::-moz-placeholder {
	  /* Firefox 19+ */
	  color: #333;
	}
	:-ms-input-placeholder {
	  color: #333;
	}
</style>
</head>
<body>
<div class="content">
<h1>Login to <?php echo $site_info['name']; ?> CPanel</h1>
<p>

</p>
</div>
    <div class="container">
        <div class="flat-form">
            <div id="login" class="form-action show">
            	<?php if(!empty($message)){echo $message;} ?>
                <img src="images/avatar_2x.png" alt=""/>
				<form method="post" action="../login/login.php">
                <input type="hidden" name="redirect_to" value="administrator/index.php"/>
                <input type="hidden" name="redirect_from" value="<?=($_SERVER['PHP_SELF'])?>"/>
            		<ul>
                        <li>
                            <input type="text" name="username" placeholder="Username" />
                        </li>
                        <li>
                            <input type="password" name="password" placeholder="Password" />
                        </li>
                        <li>
                            <input type="submit" name="submit" value="Sign in" class="button" />
                        </li>
                        <p><li>
                            <input name="remember" type="checkbox" value="" />Remember Me<br><br>
                        </li></p>
                        
                    </ul>
                </form>
            </div>
            <!--/#register.form-action-->
        </div>
    </div>
</body>
</html>