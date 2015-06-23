<?php
require_once("includes/functions.php");
if($site_info['published']==1){
	redirect_to($GLOBALS['HOST']."/index.php");
}
?>
<!doctype html>

<html>

<head>

<meta charset="utf-8">

<title>Coming Soon</title>

<style type="text/css">

body{

	background-color:#666;

}

h1{

	width:100%;

	color:#CCC;

	text-align:center;

	font-size:64px;

	font-family:Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;

}

</style>



</head>



<body>

<h1>Coming Soon</h1>

<div style="width:75%; margin-left:auto; margin-right:auto; min-height:200px; min-width:600px; max-height:600px; max-width:1800px;">

<img src="images/prj_illuminate.png" alt="Logo" style="height:100%; width:100%;" />

</div>

</body>

</html>

