<?php
require_once("../../includes/connection.php");
require_once("../../includes/session.php");
require_once("../../includes/globals.php");

$date=date("Y/m/d H:i:s", time());
if(check_permission(array("Galleries;edit_gallery","Blog;edit_blog"))){
    //Make sure all data is here
    if(isset($_POST['galleryItemID'])&&isset($_POST['galleryItemData'])){
        $query="SELECT * FROM `site_gallery_items` WHERE `id` = ".$_POST['galleryItemID'];
        $result=mysqli_query($connection, $query);

        $itemDBInfo = mysqli_fetch_array($result);

        switch ($itemDBInfo['type']) {
            case 'image':
                $query="UPDATE `site_gallery_items` SET `description` = '{$_POST['galleryItemData']['description']}' WHERE `id` = ".$_POST['galleryItemID'];
                $resultUpdate=mysqli_query($connection, $query);
                break;
            case 'embed':
                $query="UPDATE `site_gallery_items` SET `description` = '{$_POST['galleryItemData']['description']}', `url` = '{$_POST['galleryItemData']['url']}' WHERE `id` = ".$_POST['galleryItemID'];
                $resultUpdate=mysqli_query($connection, $query);
                break;
            default:
                # code...
                break;
        }
        echo 'success';
    }else{
        echo 'Missing POST data';
    }
}else{
    echo "You don't have permission to do that!";
}
?>