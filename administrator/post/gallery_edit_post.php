<?php
require_once("../../includes/connection.php");
require_once("../../includes/session.php");
require_once("../../includes/globals.php");

$date=date("Y/m/d H:i:s", time());
if(check_permission(array("Galleries;edit_gallery","Blog;edit_blog"))){
    //Parse POST data from edit gallery page
    $params = array();
    parse_str($_POST['galleryData'], $params);

    $galleryItems = $params['galleryItemData'];

    $i=1;
    foreach ($galleryItems as $key => $value) {

        $query="SELECT * FROM `site_gallery_items` WHERE `id` = ".intval($key);
        $result=mysqli_query($connection, $query);

        $itemDBInfo = mysqli_fetch_array($result);

        switch ($itemDBInfo['type']) {
            case 'image':
                $query="UPDATE `site_gallery_items` SET `position` = {$i}, `description` = '{$galleryItems[intval($key)]['description']}' WHERE `id` = ".intval($key);
                $resultUpdate=mysqli_query($connection, $query);
                break;
            case 'embed':
                $query="UPDATE `site_gallery_items` SET `position` = {$i}, `description` = '{$galleryItems[intval($key)]['description']}', `url` = '{$galleryItems[intval($key)]['url']}' WHERE `id` = ".intval($key);
                $resultUpdate=mysqli_query($connection, $query);
                break;
            default:
                # code...
                break;
        }
        $i++;
    }
}else{
    echo "You don't have permission to do that!";
}

?>