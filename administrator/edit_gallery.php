<?php
require_once("../includes/functions.php");
confirm_logged_in();

?>
<?php 
$query="SELECT * FROM `galleries` WHERE `id` = ".urldecode($_GET['gallid']);
$result=mysqli_query( $connection, $query);
$gall = mysqli_fetch_array($result);

$output_dir="../galleries/".$gall['name']."/gallery/";
$output_thumbs="../galleries/".$gall['name']."/gallery-thumbs/";

if(!file_exists("../galleries/".$gall['name'])){
	mkdir("../galleries/".$gall['name']);
}
if(!file_exists("../galleries/".$gall['name']."/gallery/")){
	mkdir("../galleries/".$gall['name']."/gallery/");
}
if(!file_exists("../galleries/".$gall['name']."/gallery-thumbs/")){
	mkdir("../galleries/".$gall['name']."/gallery-thumbs/");
}

if(isset($_POST['delfiles'])){
	function del_file($file){
		global $output_dir;
		global $output_thumbs;
		if(file_exists($output_dir.$file)){
			if(file_exists($output_thumbs.$file)){
				unlink($output_thumbs.$file);
			}
			unlink($output_dir.$file);
			return "Photo \"".$file."\" was deleted.";
		}else{
			return "Photo \"".$file."\" was not found. Perhaps it was already deleted?";
		}
	}
	if(!empty($_POST['files'])){
		$i = 0;
		foreach($_POST['files'] as $file){
			$success=del_file($file);
			$i++;
			if($i > 1){
				$success=$i." photos were deleted.";
			}
		}
	}else{
		$error="No photos selected.";
	}
}
if(isset($_POST['submit'])){
	if (strpbrk($_POST['galname'], "\\/?%*:|\"<>") === FALSE) {
		
		$galname=mysql_prep($_POST['galname']);
		
		if(isset($_POST['subgalleries'])&&$_POST['subgalleries']!=""){
			$subgalleries=serialize($_POST['subgalleries']);
		}else{
			$subgalleries="";
		}
	
	
		if(rename("../galleries/".$gall['name'], "../galleries/".$_POST['galname'])){
			$query="UPDATE `galleries` SET 
				`name` = '{$galname}', `subgalleries` = '{$subgalleries}' 
				WHERE `id` = {$_GET['gallid']}";
			$result=mysqli_query($connection, $query);
			confirm_query($result);
			$success = "Gallery has been updated!";
		}else{
			$error="There was a problem with renaming the gallery.";
		}
	}else{
		$error="Gallery name cannot contain any of these characters: \\/?%*:|\"<>\"";
	}
}

if(isset($_POST['upload'])){
	$message = upload($_FILES, $output_dir, 2097152, array('.jpeg','.jpg','.gif','.png'));
}

if(isset($_FILES["myfile"])){
	multi_upload($_FILES, $output_dir);
}

$query="SELECT * FROM  `galleries` WHERE  `id` =  {$_GET['gallid']}";
$result=mysqli_query($connection, $query);
$gallery=mysqli_fetch_array($result);

$subquery="SELECT * FROM `galleries` ORDER BY `id` ASC";
$subgalleryquery=mysqli_query( $connection, $subquery);
confirm_query($subgalleryquery);

?>
<?php
	$pgsettings = array(
		"title" => "Editing gallery: ".$gallery['name'],
		"icon" => "icon-images"
	);
	require_once("includes/begin_cpanel.php");
?>
    <script type="text/javascript">
	<!-- jQuery for seven sets of "Select All" checkboxes -->
	$(document).ready(function() {
			 $('input[id="gallall"]').click(function() {
			 $("#gall :checkbox").attr('checked', $(this).attr('checked'));
			 });
			 
			 $('input[id="imgall"]').click(function() {
			 $("#img :checkbox").attr('checked', $(this).attr('checked'));
			 });
		  
	 });
	 
	var cbcfn = function(e) {
		if (e.target.tagName.toUpperCase() != "INPUT") {
			var $tc = $(this).parent().find('input:checkbox:first'),
				tv = $tc.attr('checked');
			$tc.attr('checked', !tv);
		}
	};
	
	$('.galleryImage').live('click', cbcfn);

	</script>
<form method="post" action="edit_gallery.php?gallid=<?php echo $_GET['gallid']; ?>">
<table cellpadding="5" id="sticker">
  <tr>
    <td width="110px"><input name="submit" type="submit" value="Update Gallery" class="green"/></td>
    <td width="110px"><a class="red" href="gallery-list.php">Cancel</a></td>
  <td></td>
  </tr>
</table>
<h1>Gallery Info</h1>
Name: <input name="galname" type="text" value="<?php echo $gallery['name']; ?>" maxlength="100" /><br>
<table id="gall">
  <tr>
  <th><h2>Include these galleries</h2></th>
  </tr>
  <tr>
    <th scope="col">Name</th>
    <th scope="col"><input type="checkbox" id="gallall"></th>
  </tr>
<?php
while($subgallery=mysqli_fetch_array($subgalleryquery)){
    $checked = false;
    if(isset($gallery['subgalleries'])&&$gallery['subgalleries']!=""){
        $subgalleries=unserialize($gallery['subgalleries']);
        foreach($subgalleries as $pagegalleryid){
            if($pagegalleryid == $subgallery['id']){
                $checked = true;
            }
        }
    }
    if($subgallery['id']!=$gallery['id']){
    ?>
        <tr>
            <td width="90%"><a href="edit_gallery.php?gallid=<?php echo urlencode($subgallery['id']); ?>"><?php echo $subgallery['name']; ?></a></td>
            <td width="10%" style="text-align:center;"><input type="checkbox" name="subgalleries[]" value="<?php echo $subgallery['id']; ?>" <?php if($checked == true){echo "checked";} ?> /></td>
        </tr>
<?php }
} ?>
</table>
</form>
<br>
<br>
<h1>Gallery Images</h1>
<p style="text-align:left;">Select all: <input type="checkbox" id="imgall" /></p>
<form method="post">
    <div align="center" id="img" style="text-align:center; width:100%; margin-left:auto; margin-right:auto;">
    
    <?php
    /** settings **/
    $images_dir = "../galleries/".$gallery['name']."/gallery/";
    $thumbs_dir = "../galleries/".$gallery['name']."/gallery-thumbs/";
    $thumbs_width = 200;
	$thumbs_height = $thumbs_width;
    $images_per_row = 15;
    
    /** generate photo gallery **/
    $image_files = get_files($images_dir);
    if(count($image_files)) {
      $index = 0;
      foreach($image_files as $index=>$file) {
        $index++;
        $thumbnail_image = $thumbs_dir.$file;
        if(!file_exists($thumbnail_image)) {
          $extension = get_file_extension($thumbnail_image);
		  $extension = strtolower($extension);
          if($extension) {
            make_thumb($images_dir.$file,$thumbnail_image,$thumbs_width,$thumbs_height,$extension);
          }
        }?>
        <div class="photo-link"><span class="galleryImage"><img src="<?php echo $thumbnail_image; ?>" style="width:150px; height:150px;" /><input type="checkbox" name="files[]" value="<?php echo $file; ?>" /><input name="delete_CheckBox" type="hidden" value="false" /></span></div>
        <?php
      }
      ?><div class="clear"></div><?php
    }
    else {
      ?><p>(There are no images in this gallery)</p><?php
    }
    ?>
    <input name="delfiles" type="submit" value="Delete Selected Photos" class="red" />
    </div>
</form>
<h1>Upload Images</h1>

    <h2>Upload Multiple photos</h2>
    	<?php print_multi_upload($output_dir,"4mb","jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG", true); ?>
<h2>Upload Single photo</h2><br />
<br />
<form action="edit_gallery.php?gallid=<?php echo $_GET['gallid']; ?>" method="post" enctype="multipart/form-data">
<input type="file" name="file" id="file" accept="image/*" />
<input name="upload" type="submit" value="Upload Image" />
</form>
<?php
	require_once("includes/end_cpanel.php");
?>