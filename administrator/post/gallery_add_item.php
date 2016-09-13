<?php
require_once("../../includes/functions.php");

$date=date("Y/m/d H:i:s", time());

if(check_permission(array("Galleries;edit_gallery","Blog;edit_blog"))){
    //Make sure required values are set
    if(isset($_GET['itemType'])&&isset($_GET['itemName'])&&isset($_GET['gallery'])){
        $itemType = urldecode($_GET['itemType']);
        $itemName = urldecode($_GET['itemName']);
        $galleryID = urldecode($_GET['gallery']);

        //Get assigned gallery properties
        $query="SELECT * FROM `galleries` WHERE `id` = ".intval($galleryID);
        $result=mysqli_query($connection, $query);
        $galleryProperties = mysqli_fetch_array($result);

        $images_dir = USER_DIR.$galleryProperties['dir'].$galleryProperties['name']."/gallery/";
        $thumbs_dir = USER_DIR.$galleryProperties['dir'].$galleryProperties['name']."/gallery-thumbs/";

        //Get number of existing gallery items
        $query="SELECT * FROM `site_gallery_items` WHERE `gallery_id` = ".intval($galleryID);
        $result=mysqli_query($connection, $query);
        $numItems = mysqli_num_rows($result);
        $newPos = $numItems + 1;

        //Check for existing item by this name
        $query="SELECT * FROM `site_gallery_items` WHERE `name` = '{$itemName}' AND `gallery_id` = ".intval($galleryID);
        $result=mysqli_query($connection, $query);

        if(mysqli_num_rows($result) == 0){
            $query="INSERT INTO `site_gallery_items` (`name`,`gallery_id`,`type`,`position`,`date_added`) VALUES ('{$itemName}',{$galleryID},'{$itemType}',{$newPos},'{$date}')";
            $result=mysqli_query($connection, $query);
            $lastId = mysqli_insert_id($connection);
            switch ($itemType) {
                case 'image':

                make_thumb('../../'.$images_dir.$itemName, '../../'.$thumbs_dir.$itemName, $galleryProperties['thumb_width'], $galleryProperties['thumb_height'], $galleryProperties['thumb_scale_type']);?>
    <li style="float:left;" class="ui-state-default">
        <div class="photo-link">
            <span class="galleryImage" name="<?php echo $lastId; ?>">
                <div class="galleryItemDrag"></div>
                <img src="../<?php echo $thumbs_dir.$itemName; ?>" class="gall-img" style="width:150px; height:150px;" />
                <input type="checkbox" name="toBeDeleted[]" value="<?php echo $lastId; ?>" id="<?php echo $itemName; ?>" />
                <input name="delete_CheckBox" type="hidden" value="false" />
                <input name="galleryItemData[<?php echo $lastId; ?>][name]" type="hidden" value="<?php echo $itemName ?>" />
                <input name="galleryItemData[<?php echo $lastId; ?>][type]" type="hidden" value="<?php echo $itemType ?>" />
                <input name="galleryItemData[<?php echo $lastId; ?>][description]" type="hidden" value="" />
                <div class="galleryEditBtn"><a class="btn-click-action" href="#modalImage" name="<?php echo $lastId; ?>"><i class="material-icons" style="height:30px;width:30px;font-size:30px;">mode_edit</i></a></div>
            </span>
        </div>
    </li>
    <?php
                    break;
                
                case 'embed':?>
    <li style="float:left;" class="ui-state-default">
        <div class="photo-link">
            <span class="galleryImage" name="<?php echo $lastId; ?>">
                <div class="galleryItemDrag"></div>
                <img src="<?php echo $GLOBALS['HOST']; ?>administrator/images/video-thumb.png" class="gall-img" style="width:150px; height:150px;" />
                <input type="checkbox" name="toBeDeleted[]" value="<?php echo $lastId; ?>" id="<?php echo $itemName; ?>" />
                <input name="delete_CheckBox" type="hidden" value="false" />
                <input name="galleryItemData[<?php echo $lastId; ?>][name]" type="hidden" value="<?php echo $itemName ?>" />
                <input name="galleryItemData[<?php echo $lastId; ?>][type]" type="hidden" value="<?php echo $itemType ?>" />
                <input name="galleryItemData[<?php echo $lastId; ?>][description]" type="hidden" value="" />
                <input name="galleryItemData[<?php echo $lastId; ?>][url]" type="hidden" value="" />
                <div class="galleryEditBtn"><a class="btn-click-action" href="#modalEmbed" name="<?php echo $lastId; ?>"><i class="material-icons" style="height:30px;width:30px;font-size:30px;">mode_edit</i></a></div>
            </span>
        </div>
    </li>
    <?php
                    break;

                default:
                    echo 'err';
                    break;
            }
        }
    }else{
        echo 'Missing POST data';
    }
}else{
    echo "You don't have permission to do that!";
}


?>