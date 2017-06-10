<?php
require_once("../includes/functions.php");
confirm_logged_in();

?>
<?php

if(!isset($_GET['gallid'])){
	redirect_to("gallery-list");
}

$query="SELECT * FROM `galleries` WHERE `id` = ".urldecode($_GET['gallid']);
$result=mysqli_query( $connection, $query);
if(mysqli_num_rows($result)==1){
	$gallery=mysqli_fetch_array($result);
}else{
	redirect_to("gallery-list?error=".urlencode('No gallery found by id "'.$_GET['gallid'].'".'));
}

$output_dir="../".USER_DIR.$gallery['dir'].$gallery['name']."/gallery/";
$output_thumbs="../".USER_DIR.$gallery['dir'].$gallery['name']."/gallery-thumbs/";

if(!file_exists("../".USER_DIR.$gallery['dir'].$gallery['name'])){
	mkdir("../".USER_DIR.$gallery['dir'].$gallery['name']);
}
if(!file_exists("../".USER_DIR.$gallery['dir'].$gallery['name']."/gallery/")){
	mkdir("../".USER_DIR.$gallery['dir'].$gallery['name']."/gallery/");
}
if(!file_exists("../".USER_DIR.$gallery['dir'].$gallery['name']."/gallery-thumbs/")){
	mkdir("../".USER_DIR.$gallery['dir'].$gallery['name']."/gallery-thumbs/");
}

if(isset($_GET['resetthumbs'])&&$_GET['resetthumbs'] == 'true'){
	$files = glob($output_thumbs.'*'); // get all file names
	foreach($files as $file){ // iterate files
	  if(is_file($file))
	    unlink($file); // delete file
	}
}

if(isset($_POST['delfiles'])){
	function del_file($id){
		global $connection;
		global $output_dir;
		global $output_thumbs;

		$query="SELECT `id`, `name`, `type` FROM `site_gallery_items` WHERE `id` = ".$id;
		$result=mysqli_query( $connection, $query);
		$galleryItem = mysqli_fetch_array($result);

		$file = $galleryItem['name'];

		if ($galleryItem['type'] == 'image'){
			if(file_exists($output_dir.$file)){
				if(file_exists($output_thumbs.$file)){
					unlink($output_thumbs.$file);
				}
				unlink($output_dir.$file);
			}
		}

		$query="DELETE FROM `site_gallery_items` WHERE `id` = ".$id;
		$result=mysqli_query( $connection, $query);

		return "Item \"".$file."\" was deleted.";
	}
	if(!empty($_POST['toBeDeleted'])){
		$i = 0;
		foreach($_POST['toBeDeleted'] as $file){
			$success=del_file($file);
			$i++;
			if($i > 1){
				$success=$i." items were deleted.";
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
	
	
		if(rename("../".USER_DIR.$gallery['dir'].$gallery['name'], "../".USER_DIR.$gallery['dir'].$_POST['galname'])){
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

if(isset($_POST['uploadsingle'])){
	$message = upload($_FILES, $output_dir, 8388608, array('.jpeg','.jpg','.gif','.png','.JPEG','.JPG','.GIF','.PNG'));
}

if(isset($_FILES["myfile"])){
	multi_upload($_FILES, $output_dir);
}

$subquery="SELECT * FROM `galleries` WHERE `type` = 'page' ORDER BY `id` ASC";
$subgalleryquery=mysqli_query( $connection, $subquery);
confirm_query($subgalleryquery);

$images_dir = "../".USER_DIR.$gallery['dir'].$gallery['name']."/gallery/";
$thumbs_dir = "../".USER_DIR.$gallery['dir'].$gallery['name']."/gallery-thumbs/";

//Add/Delete DB items based on files in galley folder
CheckGalleryFiles(urldecode($_GET['gallid']), true);

?>
<?php
	$pgsettings = array(
		"title" => "Editing gallery: ".$gallery['name'],
		"icon" => "icon-images"
	);
	require_once("includes/begin_cpanel.php");
?>
    <script type="text/javascript">
   //jQuery for "Select All" checkboxes
    $(document).ready(function() {
		var $checkall = 'gallall';
        $('input[id="'+$checkall+'"]').change(function() {
			var $all_check_status = $('input[id="'+$checkall+'"]').is(':checked');
             $("#gall label").each(function(index, element) {
				var $target = $(this).attr("for");
				if($all_check_status!=$('input[id="'+$target+'"]').is(':checked')){
                	$(this).trigger('click');
				}
            });
        }); 

        //Select all images
        $('input[id="imgall"]:checkbox').change(function() {
             $("#img :checkbox").prop('checked', $(this).prop('checked'));
        });

        //check checkbox when clicked on image
		$('#sortable').on("click", ".gall-img", function(){
			var box = $(this).parent().find('input[type=checkbox]');
			box.prop("checked", !box.is(':checked'));
		});

		//Load editor elements based on edit button clicked
		$("#sortable").on("click", ".btn-click-action", function(){
			itemID = $(this).attr('name');
			$($(this).attr('href')).openModal();
			switch($("#galleryItems input[name='galleryItemData["+itemID+"][type]']").attr('value')) {
			    case 'image':
					$("#editorDescription").val($("#galleryItems input[name='galleryItemData["+itemID+"][description]']").attr('value'));
					$("#editorImage").attr("src", "<?php echo $thumbs_dir; ?>" + $("#galleryItems input[name='galleryItemData["+itemID+"][name]']").attr('value'));
					$("#editorItemID").val(itemID);
			        break;
			    case 'embed':
					$("#editorEmbedDescription").val($("#galleryItems input[name='galleryItemData["+itemID+"][description]']").attr('value'));
					$("#editorURL").val($("#galleryItems input[name='galleryItemData["+itemID+"][url]']").attr('value'));
					$("#editorItemID").val(itemID);
			        break;
			    default:
			        
			        break;
			}
		});

		//Update image in gallery when update button clicked
		$("#update-button-image").on("click", function(){
			editedItemID = $("#editorItemID").val();
			//Update item in DB
			updateItem(itemID, data = {description:$("#editorDescription").val()});
			$("#galleryItems input[name='galleryItemData["+itemID+"][description]']").val($("#editorDescription").val());
		});

		//Update embed in gallery when update button clicked
		$("#update-button-embed").on("click", function(){
			editedItemID = $("#editorItemID").val();
			//Update item in DB
			updateItem(itemID, data = {description:$("#editorDescription").val(),url:$("#editorURL").val()});
			$("#galleryItems input[name='galleryItemData["+itemID+"][description]']").val($("#editorEmbedDescription").val());
			$("#galleryItems input[name='galleryItemData["+itemID+"][url]']").val($("#editorURL").val());
		});

		$("#button-rotate-clockwise").on("click", function(){
			$('#button-rotate-clockwise').html('<img src="../images/ajax-load.gif" style="margin-top:10px;padding-left:11px;padding-right:11px;"/>');
			editedItemID = $("#editorItemID").val();
			rotateAjax(editedItemID, 270).done(function(){$('#button-rotate-clockwise').html('<i class="material-icons">rotate_right</i>');});
		});

		$("#button-rotate-counterclockwise").on("click", function(){
			$('#button-rotate-counterclockwise').html('<img src="../images/ajax-load.gif" style="margin-top:10px;padding-left:11px;padding-right:11px;"/>');
			editedItemID = $("#editorItemID").val();
			rotateAjax(editedItemID, 90).done(function(){$('#button-rotate-counterclockwise').html('<i class="material-icons">rotate_left</i>');});
   	 	});
   	});

    function rotateAjax(itemID, degrees){
    	//function timer
    	var dfd = $.Deferred();

		$.get( "post/gallery_rotate_image.php",
		{
			src: encodeURIComponent("<?php echo $images_dir; ?>"+$("#galleryItems input[name='galleryItemData["+itemID+"][name]']").val()),
			thumb: encodeURIComponent("<?php echo $thumbs_dir; ?>"+$("#galleryItems input[name='galleryItemData["+itemID+"][name]']").val()),
			thumbWidth: <?php echo $gallery['thumb_width']; ?>,
			thumbHeight: <?php echo $gallery['thumb_height']; ?>,
			thumbResizeType: encodeURIComponent("<?php echo $gallery['thumb_scale_type']; ?>"),
			deg: degrees
		},
        function(data, status){
            if(status == 'success'){
            	if(data == 'success'){
                	$("#editorImage").attr("src", "<?php echo $thumbs_dir; ?>" + $("#galleryItems input[name='galleryItemData["+itemID+"][name]']").val() + "?r="+new Date().getTime());
                	dfd.resolve();
            	}else{
            		Materialize.toast('Sorry, an error occured.', 8000, 'red');
            		dfd.resolve();
            	}
            }else{
                Materialize.toast('Sorry, an error occured.', 8000, 'red');
                dfd.resolve();
            }
        });
        return dfd;
    }

	$(function() {
		$( "#sortable" ).sortable({
			handle: '.galleryItemDrag'
		});
		$( "#sortable" ).disableSelection();
	});

    function sendPost(){
    	$('#saveGalleryItems').html('<img src="../images/ajax-load.gif" style="margin-top:10px;padding-left:11px;padding-right:11px;"/>');
        $.post("post/gallery_edit_post.php",
        {
            galleryData: $('#galleryItems').serialize(),
        },
        function(data, status){
            if(status == 'success'){
                Materialize.toast('Gallery updated!', 8000, 'green');
                $('#saveGalleryItems').html('Save');
            }else{
                Materialize.toast('Sorry, an error occured.', 8000, 'red');
                $('#saveGalleryItems').html('Save');
            }
        });
    }

    function updateItem(galleryItemID, galleryItemData){
        $.post("post/gallery_update_item.php",
        {
            galleryItemID: galleryItemID,
            galleryItemData: galleryItemData,
        },
        function(data, status){
            if(status == 'success'){
            	if(data == 'success'){
            		Materialize.toast('Item updated!', 8000, 'green');
            	}else{
            		Materialize.toast(data, 8000, 'red');
            	}
            }else{
                Materialize.toast('Sorry, an error occured.', 8000, 'red');
            }
        });
    }

    function addEmbed(){
	   //Add and put embed into editor
		$.get( "post/gallery_add_item.php",
		{
			itemType: encodeURIComponent('embed'),
			itemName: encodeURIComponent('embed-'+Math.random().toString(36).substr(2, 8)),
			gallery: encodeURIComponent(<?php echo $gallery['id']; ?>)
		},
        function(data, status){
            if(status == 'success'){
                $("#sortable").append(data);
            }else{
                Materialize.toast('Sorry, an error occured.', 8000, 'red');
            }
        });
    }

	</script>
<form method="post" action="edit_gallery.php?gallid=<?php echo $_GET['gallid']; ?>">
<table cellpadding="5" id="sticker">
  <tr><td>
<input name="submit" type="submit" value="Save" class="btn green"/>
<a class="btn red" href="gallery-list.php">Cancel</a>
  </td>
  </tr>
</table>
<h1>Gallery Info</h1>
Name: <input name="galname" type="text" value="<?php echo $gallery['name']; ?>" maxlength="100" /><br>
<h2>Include these galleries:</h2>
<ul id="gall">
    <th scope="col"><input type="checkbox" id="gallall">
    <label for="gallall"></label></th>
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
        <li>
        	<input type="checkbox" name="subgalleries[]" id="<?php echo $subgallery['id']; ?>" value="<?php echo $subgallery['id']; ?>" <?php if($checked == true){echo "checked";} ?> /><label for="<?php echo $subgallery['id']; ?>"><?php echo $subgallery['name']; ?></label>
        </li>
<?php }
} ?>
</ul>
</form>
<br>
<br>
<h1>Gallery Images</h1>
<p style="text-align:left;">Select all: <input type="checkbox" id="imgall" /><label for="imgall"></label></p>
<form method="post" id="galleryItems">
    <div id="img" style="width:100%;">
    <ul id="sortable" class="GallerySort">
    <?php
    
    /** generate photo gallery **/
    $query="SELECT * FROM  `site_gallery_items` WHERE  `gallery_id` = {$_GET['gallid']} ORDER BY `position` ASC";
	$result=mysqli_query( $connection, $query);

    if(mysqli_num_rows($result) != 0) {
    	while($item = mysqli_fetch_array($result)){
    		switch ($item['type']) {
    			case 'image':
			        $thumbnail_image = $thumbs_dir.$item['name'];
			        if(!file_exists($thumbnail_image)) {
			          $extension = get_file_extension($thumbnail_image);
					  $extension = strtolower($extension);
			          if($extension) {
			            make_thumb($images_dir.$item['name'],$thumbnail_image,$gallery['thumb_width'],$gallery['thumb_height'],$gallery['thumb_scale_type']);
			          }
			        }?>
			        <li style="float:left;" class="ui-state-default">
			        	<div class="photo-link">
			        		<span class="galleryImage" name="<?php echo $item['id']; ?>">
			        			<div class="galleryItemDrag"></div>
				        		<img src="<?php echo $thumbnail_image; ?>" class="gall-img" style="width:150px; height:150px;" />
				        		<input type="checkbox" name="toBeDeleted[]" value="<?php echo $item['id']; ?>" id="<?php echo $item['name']; ?>" />
				        		<input name="delete_CheckBox" type="hidden" value="false" />
				        		<input name="galleryItemData[<?php echo $item['id']; ?>][name]" type="hidden" value="<?php echo $item['name'] ?>" />
				        		<input name="galleryItemData[<?php echo $item['id']; ?>][type]" type="hidden" value="<?php echo $item['type'] ?>" />
				        		<input name="galleryItemData[<?php echo $item['id']; ?>][description]" type="hidden" value="<?php echo $item['description'] ?>" />
				        		<div class="galleryEditBtn"><a class="btn-click-action" href="#modalImage" name="<?php echo $item['id']; ?>"><i class="material-icons" style="height:30px;width:30px;font-size:30px;">mode_edit</i></a></div>
			        		</span>
			        	</div>
			        </li>
			        <?php
    				break;
    			case 'embed':
					?>
			        <li style="float:left;" class="ui-state-default">
			        	<div class="photo-link">
			        		<span class="galleryImage" name="<?php echo $item['id']; ?>">
			        			<div class="galleryItemDrag"></div>
				        		<img src="<?php echo getEmbedThumb($item['url']); ?>" class="gall-img" style="width:150px; height:150px;" />
				        		<input type="checkbox" name="toBeDeleted[]" value="<?php echo $item['id']; ?>" id="<?php echo $item['name']; ?>" />
				        		<input name="delete_CheckBox" type="hidden" value="false" />
				        		<input name="galleryItemData[<?php echo $item['id']; ?>][name]" type="hidden" value="<?php echo $item['name'] ?>" />
				        		<input name="galleryItemData[<?php echo $item['id']; ?>][type]" type="hidden" value="<?php echo $item['type'] ?>" />
				        		<input name="galleryItemData[<?php echo $item['id']; ?>][description]" type="hidden" value="<?php echo $item['description'] ?>" />
				        		<input name="galleryItemData[<?php echo $item['id']; ?>][url]" type="hidden" value="<?php echo $item['url'] ?>" />
				        		<div class="galleryEditBtn"><a class="btn-click-action" href="#modalEmbed" name="<?php echo $item['id']; ?>"><i class="material-icons" style="height:30px;width:30px;font-size:30px;">mode_edit</i></a></div>
			        		</span>
			        	</div>
			        </li>
			        <?php
    				break;
    			default:
    				# code...
    				break;
    		}
      }
    }
    else {
      ?><li>(There are no images in this gallery)</li><?php
    }
    ?>
    </ul>
    <div style="clear:both;"></div>
    </div>
    <div onclick="sendPost();" name="saveGalleryItems" id="saveGalleryItems" class="btn green">Save</div>&nbsp;&nbsp;&nbsp;
    <div onclick="addEmbed();" name="addEmbed" id="addEmbed" class="btn blue">Add YouTube/Vimeo</div>&nbsp;&nbsp;&nbsp;
    <input name="delfiles" type="submit" value="Delete Selected Items" class="btn red" />
</form>
<h1>Upload Images</h1>

    <h2>Upload Multiple photos</h2>
    <div id="testoutput"></div>
    <?php print_multi_upload($output_dir,"8mb","jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG", true); ?>
    <script type="text/javascript">
		uploader.bind('FileUploaded', function(upldr, file, object) {
		    var obj = JSON.parse(object.response);
		   //Get and put uploaded image into editor
			$.get( "post/gallery_add_item.php",
			{
				itemType: encodeURIComponent('image'),
				itemName: encodeURIComponent(obj.result.cleanFileName),
				gallery: encodeURIComponent(<?php echo $gallery['id']; ?>)
			},
	        function(data, status){
	            if(status == 'success'){
	                $("#sortable").append(data);
	            }else{
	                Materialize.toast('Sorry, an error occured.', 8000, 'red');
	            }
	        });
		});
    </script>
<h2>Upload Single photo</h2><br />
<br />
<form method="post" enctype="multipart/form-data">
<input type="file" name="file" id="file" accept="image/*" />
<input name="uploadsingle" type="submit" class="btn blue" value="Upload Image" />
</form>

<!-- Image editor popup -->
<div id="modalImage" class="modal">
	<div class="modal-content" style="text-align:center;">
		<h4>Edit Image</h4>
		<img id="editorImage" height="200" src="" />
		<br/>
		<div id="button-rotate-counterclockwise" class="waves-effect waves-blue btn blue tooltipped" style="margin:10px;" data-position="bottom" data-delay="50" data-tooltip="Rotate Counterclockwise"><i class="material-icons">rotate_left</i></div>
		<div id="button-rotate-clockwise" class="waves-effect waves-blue btn blue tooltipped" style="margin:10px;" data-position="bottom" data-delay="50" data-tooltip="Rotate Clockwise"><i class="material-icons">rotate_right</i></div>
		<br/><br/>
		<p>Brief description: <input id="editorDescription" type="text" value="desc" style="width:300px;" /></p>
		<input id="editorItemID" type="hidden" value="" />
	</div>
	<div class="modal-footer">
		<div class="row right">
			<div class="col l12 s12">
				<a href="#!" class="modal-close waves-effect waves-red btn red" style="margin:10px;">Cancel</a>
				<a href="#!" id="update-button-image" class="modal-close waves-effect waves-green btn green" style="margin:10px;">Update</a>
			</div>
		</div>
	</div>
</div>
<!-- Embed video editor popup -->
<div id="modalEmbed" class="modal">
	<div class="modal-content" style="text-align:center;">
		<h4>Edit Embedded Video</h4>
		<p>YouTube/Vimeo URL: <input id="editorURL" type="text" value="" style="width:300px;" /></p>
		<p>Brief description: <input id="editorEmbedDescription" type="text" value="" style="width:300px;" /></p>
		<input id="editorItemID" type="hidden" value="" />
	</div>
	<div class="modal-footer">
		<div class="row right">
			<div class="col l12 s12">
				<a href="#!" class="modal-close waves-effect waves-red btn red" style="margin:10px;">Cancel</a>
				<a href="#!" id="update-button-embed" class="modal-close waves-effect waves-green btn green" style="margin:10px;">Update</a>
			</div>
		</div>
	</div>
</div>
<?php
	require_once("includes/end_cpanel.php");
?>