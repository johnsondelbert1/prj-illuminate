<?php 
if (version_compare(phpversion(), '5.5.0', '<')) {?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Old PHP Version</title>
<style type="text/css">
	body{
		background-color:#333;
		color:#E3E3E3;
		text-align:center;
		display:flex;
		justify-content:center;
		align-items:center;
		font-family:Arial, Helvetica, sans-serif;
	}
	div.content{
		background-color:#656565;
		border-radius:5px;
		padding:30px;
		width:500px;
		height:450px;
		margin-top:215px;
		top:50%;
		left:50%;
		-webkit-box-shadow: 3px 3px 5px 0px rgba(0,0,0,0.75);
		-moz-box-shadow: 3px 3px 5px 0px rgba(0,0,0,0.75);
		box-shadow: 3px 3px 5px 0px rgba(0,0,0,0.75);
	}
	div.content h2, div.content h3{
		color:#FFA3A4;
	}
	div.content span{
		position: absolute;
		right: 0;
		bottom: 0;
		padding: 5px;
	}
</style>
</head>

<body>
<div class="content">
    <br><img src="images/logo.png" alt="Logo" /><br>
    <h1>IlluminateCMS</h1><br>
    <h2>Error: Old PHP version</h2>
    <h3>PHP needs to be updated to at least version 5.5 for IlluminateCMS to function</h3>
    <h3>Current PHP version: <?php echo phpversion(); ?></h3>
</div>
</body>
</html>
<?php
}else{
	header("Location: index.php");
}
?>