<?php
require_once("../../includes/functions.php");
if(check_permission(array("Galleries;edit_gallery","Blog;edit_blog"))){
    header('Content-Type: text/html; charset=utf-8');

    $srcPath = '../'.urldecode($_GET['src']);
    $degrees = urldecode($_GET['deg']);

    /* function:  returns a file's extension */
    function getExtension($src) {
      return basename(substr(strrchr($src,'.'),1));
    }

    /* function:  generates thumbnail */
    function rotateImage($src,$deg) {
        if(file_exists($src)){

            $extention = getExtension($src);

            /* read the source image */
            if($extention=="jpg" || $extention=="jpeg"){
                $source_image = imagecreatefromjpeg($src);
            }elseif($extention=="gif"){
                $source_image = imagecreatefromgif($src);
            }elseif($extention=="png"){
                $source_image = imagecreatefrompng($src);
            }

            /* create a new, "virtual" image */
            $rotate = imagerotate($source_image, $deg, 0);

            /* create the rotated image to its destination */
            if($extention=="jpg" || $extention=="jpeg"){
                imagejpeg($rotate,$src);
            }elseif($extention=="gif"){
                imagegif($rotate,$src);
            }elseif($extention=="png"){
                imagepng($rotate,$src);
            }

            // Free the memory
            imagedestroy($source_image);
            imagedestroy($rotate);

            $thumbDir = '../'.urldecode($_GET['thumb']);
            $thumbResizeType = urldecode($_GET['thumbResizeType']);

            make_thumb(''.$src,''.$thumbDir,$_GET['thumbWidth'],$_GET['thumbHeight'],$extention,$thumbResizeType);

            return 'success';
        }else{
            return 'failure'.$src;
        }
    }
    echo rotateImage($srcPath, $degrees);
}else{
    echo "You don't have permission to do that!";
}

?>