<?php
include("../includes/functions.php");
ini_set ('gd.jpeg_ignore_warning', 1);
if(logged_in()){
	if(isset($_POST['off_x'])&&isset($_POST['off_y'])&&isset($_POST['width'])&&isset($_POST['height'])){
		$profile_dir = '../'.USER_DIR."user-assets/".$_SESSION['user_id']."/profile/";
		$profile_pic = scandir($profile_dir);

		$off_x = round($_POST['off_x']);
		$off_y = round($_POST['off_y']);
		$width = round($_POST['width']);
		$height = round($_POST['height']);
		$rotation = round($_POST['rotation']);

		//Cropper rotation is opposite of the PHP rotation
		$rotation = $rotation * -1;

		//Get file extension
		$extention = basename(substr(strrchr(strtolower($profile_dir.$profile_pic[2]),'.'),1));
		/* read the source image */
		switch ($extention) {
			case 'jpg': $source_image = imagecreatefromjpeg($profile_dir.$profile_pic[2]); break;
			case 'jpeg': $source_image = imagecreatefromjpeg($profile_dir.$profile_pic[2]); break;
			case 'gif': $source_image = imagecreatefromgif($profile_dir.$profile_pic[2]); break;
			case 'png': $source_image = imagecreatefrompng($profile_dir.$profile_pic[2]); break;
			default: echo "Unsupported Image Type"; break;
		}

		$source_image = imagerotate($source_image, $rotation, 0);

		//rotated original image for re-saving
		$rotated_image = $source_image;

		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor(100,100);

		//Transparency
		if($extention=="gif"||$extention=="png"){
			imagealphablending( $virtual_image, false );
			imagesavealpha( $virtual_image, true );
		}
		imagecopyresampled($virtual_image,$source_image,0,0,$off_x,$off_y,100,100,$width,$height);
		/* create the physical thumbnail image to its destination */
		switch ($extention) {
			case 'jpg': imagejpeg($virtual_image,$profile_dir.$profile_pic[3]); imagejpeg($rotated_image,$profile_dir.$profile_pic[2]); break;
			case 'jpeg': imagejpeg($virtual_image,$profile_dir.$profile_pic[3]); imagejpeg($rotated_image,$profile_dir.$profile_pic[2]); break;
			case 'gif': imagegif($virtual_image,$profile_dir.$profile_pic[3]); imagegif($rotated_image,$profile_dir.$profile_pic[2]); break;
			case 'png': imagepng($virtual_image,$profile_dir.$profile_pic[3]); imagepng($rotated_image,$profile_dir.$profile_pic[2]); break;
		}
		echo 'success';
	}else{
		echo 'Missing args';
	}
}else{
	echo 'Not Logged In';
}
?>