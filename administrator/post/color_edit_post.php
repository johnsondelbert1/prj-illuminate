<?php
require_once("../../includes/connection.php");
require_once("../../includes/session.php");
require_once("../../includes/globals.php");

$date=date("Y/m/d H:i:s", time());

$params = array();
parse_str($_POST['colorData'], $params);
foreach ($params['color_name'] as $key => $value) {
	$query="UPDATE `style_colors` SET `c_name` = '{$value}', `color_hex` = '{$params['color_field'][$key]}', `date_edited` = '{$date}', `editor` = '{$_SESSION['user_id']}' WHERE `cid` = '{$key}'";
	$result=mysqli_query($connection, $query);
}

$css = explode(';', $_POST['cssSelectors']);
foreach ($css as $value) {
	$ids = explode('|', $value);
	$query="UPDATE `css_selectors` SET 
		`style_color_id` = '{$ids[0]}' WHERE `sid` = '{$ids[1]}'";
	$result=mysqli_query($connection, $query);
}
?>