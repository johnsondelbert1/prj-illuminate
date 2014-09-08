<?php 
	if(isset($_GET['error'])){
		header("Location: administrator/setuplogin.php?error=".$_GET['error']);
	}else{
		header("Location: administrator/setuplogin.php");
	}
?>