<?php
require_once("../includes/functions.php");
?>

<?php
if(!check_permission(array("Forms;add_form","Forms;edit_form","Forms;delete_form",))){
	//redirect_to("index.php");
}
if(isset($_POST['save'])){
	if(check_permission("Forms","add_form")){
		if(isset($_POST['slide_id'])&&isset($_POST['slide_order'])&&isset($_POST['slide_cap'])){
			foreach($_POST['slide_id'] as $slide_id){
				$caption = strip_tags(mysqli_real_escape_string($connection, $_POST['slide_cap'][$slide_id]), "<a><i><b><u>");
				$order = intval($_POST['slide_order'][$slide_id]);
				$savequery="UPDATE `slider` SET `order` = ".$order.", `caption` = '".$caption."' WHERE `id` = ".$slide_id;
				
				$saveresult=mysqli_query($connection, $savequery);
				confirm_query($saveresult);
				$success="Slider updated!";
			}
		}
	}
	
}

if(isset($_POST['upload'])){
	$message = upload($_FILES, "../images/slider/", 2097152, array('.jpeg','.jpg','.gif','.png'));
}

if(isset($_POST['del'])){
	if(check_permission("Forms","delete_form")){
		function del($id){
			global $connection;
			
			$query="SELECT * FROM `slider` WHERE `id` = ".$id;
			$result=mysqli_query( $connection, $query);
			$slide = mysqli_fetch_array($result);
			
			if(mysqli_num_rows($result)!=0){
				// Specify the target directory and add forward slash
				$file = "../images/slider/".$slide['img_name'];
				if(file_exists($file)){
					unlink($file);
					$success="Slide was deleted!";
				}else{
					$error='File has already been deleted';
				}
			}else{
				$error='File has already been deleted';
			}
			
		}
		if(isset($_POST['slide'])){
			foreach($_POST['slide'] as $slide){
				del($slide);
			}
		}else{
			$error="No slides selected!";
		}
	}else{
		$error="You do not have permission to delete slides!";
	}
}
?>

<?php
$dir = '../images/slider/';

$query="SELECT * FROM `slider`";
$result=mysqli_query( $connection, $query);
$num_slides = mysqli_num_rows($result) + 1;

if(mysqli_num_rows($result)!=0){
	while($img = mysqli_fetch_array($result)){
		if(!file_exists($dir.$img['img_name'])){
			$imgquery="DELETE FROM `slider` WHERE `img_name` = '{$img['img_name']}'";
			mysqli_query( $connection, $imgquery);
		}
	}
}
foreach (scandir($dir) as $item) {
	if ($item == '.' || $item == '..' || $item == 'Thumbs.db') continue;
	$file_ext = substr($item, strripos($item, '.'));
	
	$imagesquery="SELECT * FROM `slider` WHERE `img_name` = '{$item}'";
	$imagesresult=mysqli_query( $connection, $imagesquery);
	if(mysqli_num_rows($imagesresult)==0){
		$imgquery="INSERT INTO `slider` (
			`img_name`, `order`
		) VALUES (
			'{$item}', {$num_slides})";
		mysqli_query($connection, $imgquery);
	}
}

$query="SELECT * FROM `slider` ORDER BY `order`";
$result=mysqli_query( $connection, $query);
?>

<?php
	$pgsettings = array(
		"title" => "Slider",
		"icon" => "icon-user3"
	);
	require_once("includes/begin_cpanel.php");
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript">
   <!-- jQuery for seven sets of "Select All" checkboxes -->
    $(document).ready(function() {
             $('input[id="staffall"]').click(function() {
             $("#staff :checkbox").attr('checked', $(this).attr('checked'));
        });  
     });
</script>
<?php if(check_permission("Forms","add_form")){?>
<h1>Add New Slide</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="file" id="file" />
        <input name="upload" type="submit" value="Upload Image" />
    </form>
<?php } ?>
<h1>List of Slides</h1>
<form method="post">
    <table width="90%" style="text-align:left;" class="list" border="0" cellspacing="0" id="staff">
        <tr>
            <th width="10%">
                Order
            </th>
            <th width="30%">
                Picture
            </th>
            <th>
                Name
            </th>
            <th>
                Caption
            </th>
            <?php if(check_permission("Forms","delete_form")){?>
            <th style="text-align:center;">
                <input type="checkbox" id="staffall">
            </th>
            <?php } ?>
        </tr>
    <?php
	if(mysqli_num_rows($result)!=0){
		while($slide=mysqli_fetch_array($result)){
			?>
			<tr style="height:125px;">
            	<td><input type="hidden" name="slide_id[<?php echo $slide['id']; ?>]" value="<?php echo $slide['id']; ?>" /><input type="text" name="slide_order[<?php echo $slide['id']; ?>]" value="<?php echo $slide['order']; ?>" maxlength="3" /></td>
				<td><img src="../images/slider/<?php echo $slide['img_name'] ?>" width="320" height="180" /></td>
                <td><?php echo $slide['img_name'] ?></td>
                <td><input type="text" name="slide_cap[<?php echo $slide['id']; ?>]" value="<?php echo htmlspecialchars($slide['caption']); ?>" /></td>
				<?php if(check_permission("Galleries","delete_gallery")){?>
				<td width="10%" style="text-align:center;"><input type="checkbox" name="slide[]" value="<?php echo $slide['id']; ?>" /></td>
				<?php } ?>
			</tr>
	<?php
        }
	}else{?>
			<tr>
				<td colspan="4" style="text-align:center; font-size:24px;">[No Slides!]</td>
			</tr>
    <?php
	}
	?>
    	<tr>
        	<td style="text-align:center;"><input name="save" type="submit" value="Save Slides" class="green" /></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align:center;"><?php if(check_permission("Forms","delete_form")){?><input name="del" type="submit" value="Delete" class="red" /><?php } ?></td>
        </tr>
    </table>
</form>
<?php
	require_once("includes/end_cpanel.php");
?>