<?php
require("constants.php");
	$connection = @mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	if(DB_SERVER==""||DB_USER==""||DB_NAME==""){
		header("Location: setup/setuplogin.php");
	}
	if(!$connection){
		//die("Database connection failed: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		header("Location: setup/setuplogin.php");
	}
	/*$db_connect=((bool)mysqli_query($connection, "USE " . constant('DB_NAME')));
	if(!$db_connect){
		//die("Database selection failed: ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		header("Location : ".dirname(__FILE__)."\administrator/dbconnect.php");
	}*/
?>