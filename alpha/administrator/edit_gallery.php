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
		
		$query="UPDATE `galleries` SET 
			`name` = '{$galname}', `subgalleries` = '{$subgalleries}' 
			WHERE `id` = {$_GET['gallid']}";
		$result=mysqli_query($connection, $query);
		confirm_query($result);
	
		if(rename("../galleries/".$gall['name'], "../galleries/".$_POST['galname'])){
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
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script type="text/javascript">
	function confirmdelete(file){
		var ans = confirm("Are you sure you want to delete \""+file+"\" ?");
		if (ans==true){
			window.location = "edit_gallery.php?gallid=<?php echo $_GET['gallid']; ?>&action=del&img=" + file;
		}
	}
	<!-- jQuery for seven sets of "Select All" checkboxes -->
	$(document).ready(function() {
			 $('input[id="gallall"]').click(function() {
			 $("#gall :checkbox").attr('checked', $(this).attr('checked'));
		});  
	 });
	</script>
<table width="100%" border="0" style="margin-right:auto; margin-left:auto;">
<tr>
<td width="50%">
<h2>Edit Gallery</h2>
<form method="post" action="edit_gallery.php?gallid=<?php echo $_GET['gallid']; ?>">
Name: <input name="galname" type="text" value="<?php echo $gallery['name']; ?>" maxlength="100" />
        <br>
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
        <input name="submit" type="submit" value="Update Gallery" />
</form>
</td>
</tr>
<tr>
<td width="100%">
<form method="post">
    <div align="center" style="text-align:center; width:100%; padding:28px;">
    
    <?php
    /** settings **/
    $images_dir = "../galleries/".$gallery['name']."/gallery/";
    $thumbs_dir = "../galleries/".$gallery['name']."/gallery-thumbs/";
    $thumbs_width = 100;
    $images_per_row = 12;
    
    /** generate photo gallery **/
    $image_files = get_files($images_dir);
    if(count($image_files)) {
      $index = 0;
      foreach($image_files as $index=>$file) {
        $index++;
        $thumbnail_image = $thumbs_dir.$file;
        if(!file_exists($thumbnail_image)) {
          $extension = get_file_extension($thumbnail_image);
          if($extension) {
            make_thumb($images_dir.$file,$thumbnail_image,$thumbs_width,$extension);
          }
        }?>
        <div class="photo-link"><img src="<?php echo $thumbnail_image; ?>"  width="50" /><input type="checkbox" name="files[]" value="<?php echo $file; ?>" /></div><?php
        if($index % $images_per_row == 0) { ?><div class="clear"></div><?php }
      }
      ?> <div class="clear"></div><?php
    }
    else {
      ?><p>(There are no images in this gallery)</p><?php
    }
    ?>
    <input name="delfiles" type="submit" value="Delete Selected Photos" class="red" />
    </div>
</form>
</td>
</tr>
</table>
<table border="0" width="100%" border="0" style="margin-right:auto; margin-left:auto;">
  <tr>
    <td></td>
        </tr>
        <tr>
    <td>
    <table>
  <tr>
    <td width="50%"><h2>Upload Multiple photos</h2>
    	<?php print_multi_upload($output_dir,"4mb","jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG", true); ?>
    </td>
    <td width="50%"><h2>Upload Single photo</h2><br />
<br />
<form action="edit_gallery.php?gallid=<?php echo $_GET['gallid']; ?>" method="post" enctype="multipart/form-data">
<input type="file" name="file" id="file" />
<input name="upload" type="submit" value="Upload Image" />
</form></td>
  </tr>
</table>
</td>
  </tr>
</table>
<?php
	require_once("includes/end_cpanel.php");
?>