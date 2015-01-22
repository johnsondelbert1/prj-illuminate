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
	  /*background-image: url(images/blue-polygon.jpg);*/
	  background-color:#3f4749;
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

	form input[type=text],
	form input[type=password] {
	  margin-bottom: 10px;
	  padding-left: 15px;
	  color: #e7e7e7;
	  outline:none;
	  font-size:21px;
	  border: 2px solid #e7e7e7;
	  font-weight:bold;
	  -webkit-appearance: none; 
    -moz-appearance: none; 
	  
	  
	  border-radius: 3px;
  width: 281px;
  height: 64px;
	}
	::-webkit-input-placeholder {
   color: #e7e7e7;
}

:-moz-placeholder { /* Firefox 18- */
   color: #e7e7e7;  
}

::-moz-placeholder {  /* Firefox 19+ */
   color: #e7e7e7;  
}

:-ms-input-placeholder {  
   color: #e7e7e7;  
}
	
	.button {
		font-size:21px;
		  border-style: solid;
  border-width: 3px;
  border-color: rgb(34, 174, 112);
  border-radius: 3px;
  background-image: -moz-linear-gradient( 90deg, rgba(255,255,255,0.6) 0%, rgba(255,255,255,0) 100%);
  background-image: -webkit-linear-gradient( 90deg, rgba(255,255,255,0.6) 0%, rgba(255,255,255,0) 100%);
  background-image: -ms-linear-gradient( 90deg, rgba(255,255,255,0.6) 0%, rgba(255,255,255,0) 100%);
  width: 300px;
  height: 68px;
  background: #22ae70;
		-webkit-transition: all 0.15s linear;
		  -moz-transition: all 0.15s linear;
		  transition: all 0.15s linear;
		  right:auto;
		  left:auto;
		  color:#FFF;
	}
	
	.button:hover {
	  background: #007842;
	  border-color: #007842;
	  /*box-shadow: 0 3px 1px #237bb2;*/
	}
	
	.button:active {
	  background: #069B57;
	  border-color: #069B57;
	  /*box-shadow: 0 3px 1px #0f608c;*/
	}
	
</style>
</head>
<body>
    <div class="container">
        <div class="flat-form">
        <div class="content">
 <img src="images/logo.png" width="180" height="180"  alt=""/></div>
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
                        <p><li style="text-align:left;">
                            <input name="remember" id="remember" type="radio" value="" tabindex="3"/><label for="remember">keep me logged in</label><br><br>
                        </li></p>
                        
                    </ul>
                </form>
            </div>
            <!--/#register.form-action-->
        </div>
    </div>
</body>
</html>