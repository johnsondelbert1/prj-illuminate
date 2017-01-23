<?php
$redirect_mismatch=false;
require_once("../../includes/functions.php");

if($GLOBALS['site_info']['version'] == '1.5.1' && $site_version == '1.8'){
	echo '<h1>Updating Database Schema</h1>';
	require_once("sql_parse.php");

	$dbms_schema = '1.5.1-1.6.sql';

	$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema)) or die('problem ');
	$sql_query = remove_remarks($sql_query);
	$sql_query = split_sql_file($sql_query, ';');

	$i=1;
	foreach($sql_query as $sql){
		mysqli_query($connection, $sql) or die('error in query'.mysqli_error($connection).$sql);
	}
	$query="SELECT * FROM `users`";
	$result = mysqli_query( $connection, $query);

	//put default in newly added users column
	if(mysqli_num_rows($result)!=0){
		while($user=mysqli_fetch_array($result)){
			$query = "UPDATE `users` SET `subscriptions` = 'a:3:{s:4:\"blog\";a:0:{}s:5:\"forum\";a:0:{}s:6:\"thread\";a:0:{}}' WHERE `id` = {$user['id']}";
			mysqli_query($connection, $query);
		}
	}
	echo '<h3>...Done</h3>';

	//Create and move existing data to user folder
	echo '<h1>Building User Data Folder</h1>';
	if(!file_exists('../../'.USER_DIR)){
		mkdir('../../'.USER_DIR);
	}
	echo 'Create user dir<br/>';
	mkdir('../../'.USER_DIR.'site-img');
	echo 'Create image dir<br/>';
	mkdir('../../'.USER_DIR.'custom');
	echo 'Create custom dir<br/>';
	if(!file_exists('../../'.USER_DIR.'site-galleries')){
		rename('../../galleries', '../../'.USER_DIR.'site-galleries');
	}else{
		rrmdir('../../'.USER_DIR.'site-galleries');
		rename('../../galleries', '../../'.USER_DIR.'site-galleries');
	}
	echo 'Move site galleries<br/>';
	if(!file_exists('../../'.USER_DIR.'blog-galleries')){
		rename('../../blog_galleries', '../../'.USER_DIR.'blog-galleries');
	}else{
		rrmdir('../../'.USER_DIR.'blog-galleries');
		rename('../../blog_galleries', '../../'.USER_DIR.'blog-galleries');
	}
	echo 'Move blog galleries<br/>';
	rename('../../images/banner', '../../'.USER_DIR.'site-img/banner');
	echo 'Move site banner<br/>';
	rename('../../images/bg', '../../'.USER_DIR.'site-img/bg');
	echo 'Move site bg<br/>';
	rename('../../images/favicon', '../../'.USER_DIR.'site-img/favicon');
	echo 'Move site favicon<br/>';
	rename('../../images/logo', '../../'.USER_DIR.'site-img/logo');
	echo 'Move site logo<br/>';
	rename('../../images/slider', '../../'.USER_DIR.'site-sliders');
	echo 'Move site sliders<br/>';
	if(file_exists('../../images/staff')){
		rename('../../images/staff', '../../'.USER_DIR.'staff');
	}else{
		mkdir('../../'.USER_DIR.'staff');
	}
	echo 'Move site staff<br/>';
	rename('../../thumbs', '../../'.USER_DIR.'thumbs');
	echo 'Move thumbs<br/>';
	rename('../../uploads', '../../'.USER_DIR.'uploads');
	echo 'Move uploads<br/>';
	echo 'Create user profile dir<br/>';
	mkdir('../../'.USER_DIR.'user-assets');	
	echo 'Add folders for each user<br/>';
	//Add folders for each user for profile pictures
	$query="SELECT * FROM `users`";
	$result=mysqli_query( $connection, $query);
	confirm_query($result);
	
    while($user=mysqli_fetch_array($result)){
    	mkdir('../../'.USER_DIR.'user-assets/'.$user["id"]);
    	mkdir('../../'.USER_DIR.'user-assets/'.$user["id"].'/profile');
    	echo 'user '.$user["id"].'<br/>';
    }

	echo '<h3>...Done</h3>';

	//Add galleries for each existing blog post
	echo '<h1>Updating Blog Galleries</h1>';
	$query="SELECT * FROM `blog`";
	$result = mysqli_query( $connection, $query);

	if(mysqli_num_rows($result)!=0){
		while($blog=mysqli_fetch_array($result)){
			$query="SELECT * FROM `galleries` WHERE `type` = 'blog' AND `name` = '{$blog['id']}'";
			$galleryResult = mysqli_query( $connection, $query);
			if(mysqli_num_rows($galleryResult)==0){
				$query="INSERT INTO `galleries` (
					`name`, `date_created`, `creator`, `type`, `dir`
				) VALUES (
					'{$blog['id']}','{$date}',{$blog['poster']}, 'blog', 'blog-galleries/')";
				mysqli_query($connection, $query);

				$newGalleryID=mysqli_insert_id($connection);

				//Update blog with new gallery ID
				$query = "UPDATE `blog` SET `gallery_id` = {$newGalleryID} WHERE `id` = {$blog['id']}";
				mysqli_query($connection, $query);

				echo 'Added blog ID '.$blog['id'].'<br/>';
			}
		}
	}
	echo '<h3>...Done</h3>';

	//Delete unused folders
	echo '<h1>Deleting Unused Folders</h1>';
	//Delete old upgrade folder
	rrmdir("../upgradeSQL/");
	echo 'upgradeSQL/<br/>';
	//Delete unused prettyphoto folder
	rrmdir("../../prettyphoto/");
	echo 'prettyphoto/<br/>';
	echo '<h3>...Done</h3>';

	echo '<h1>IlluminateCMS 1.8 Upgrade Complete</h1>';
}else{
	echo 'Upgrade requires DB version 1.5.1 and Web version 1.8</br>';
	echo 'Current DB:'.$GLOBALS['site_info']['version'].'<br/>Current Web:'.$site_version;
}
?>