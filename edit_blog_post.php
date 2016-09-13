<?php
require_once("includes/functions.php");
?>
<?php

$query="SELECT * FROM `pages` WHERE `type` = 'Blog' ";
$result_page_prop=mysqli_query( $connection, $query);
$page_properties = mysqli_fetch_array($result_page_prop);

if(!isset($_GET['post'])){
	redirect_to($GLOBALS['HOST']);
}

$query="SELECT * FROM `blog` WHERE `id`={$_GET['post']}";
$result=mysqli_query($connection, $query);
confirm_query($result);
//Redirect to blog page if nothing returned from DB
if(mysqli_num_rows($result) == 0){
	redirect_to($GLOBALS['HOST']);
}else{
	$blog=mysqli_fetch_array($result);
}

$query="SELECT * FROM `galleries` WHERE `id`={$blog['gallery_id']}";
$result=mysqli_query($connection, $query);
confirm_query($result);
$gallery=mysqli_fetch_array($result);

if(!check_permission("Blog","edit_blog")&&$blog['poster']!=$_SESSION['user_id']){
	redirect_to($GLOBALS['HOST']."/blog?error=".urlencode("You do not have permission to edit a blog!"));
}

/** settings **/
$images_dir = USER_DIR."blog-galleries/".$gallery['name']."/gallery/";
$thumbs_dir = USER_DIR."blog-galleries/".$gallery['name']."/gallery-thumbs/";

if(!file_exists(USER_DIR."blog-galleries/".$blog['gallery_id'])){
	mkdir(USER_DIR."blog-galleries/".$blog['gallery_id']);
}
if(!file_exists($images_dir)){
	mkdir($images_dir);
}
if(!file_exists($thumbs_dir)){
	mkdir($thumbs_dir);
}

if(isset($_GET['resetthumbs'])&&$_GET['resetthumbs'] == 'true'){
	$files = glob($output_thumbs.'*'); // get all file names
	foreach($files as $file){ // iterate files
	  if(is_file($file))
	    unlink($file); // delete file
	}
}

if(isset($_POST['submit'])){
	$id=$_GET['post'];
	
	$content=strip_tags(nl2br(mysqli_real_escape_string($connection, $_POST['content'])), "<b><a><p><img><br><hr><ul><ol><li><sup><sub><video><source>");
	$title=strip_tags(nl2br(mysqli_real_escape_string($connection, $_POST['title'])));
	date_default_timezone_set($site_info['timezone']);
	$date=date("Y/m/d H:i:s", time());

	if(isset($_POST['allowComments'])){
		$allowComments = 1;
	}else{
		$allowComments = 0;
	}
	
	$query = "UPDATE `blog` SET `content` = '{$content}', `title`='{$title}', `comments_allowed`={$allowComments}, `lastedited`='{$date}' WHERE `id` = {$id}";
	$result = mysqli_query($connection, $query);
	confirm_query($result);
	if (mysqli_affected_rows($connection) == 1) {
		$success = "blog post was updated.";
	} else {
		$error = "blog post couldn't update.";
		$error .= "<br />" . mysqli_error($connection);
	}
}

if(isset($_POST['delfiles'])){
	function del_file($id){
		global $connection;
		global $images_dir;
		global $thumbs_dir;

		$query="SELECT `id`, `name`, `type` FROM `site_gallery_items` WHERE `id` = ".$id;
		$result=mysqli_query( $connection, $query);
		$galleryItem = mysqli_fetch_array($result);

		$file = $galleryItem['name'];

		if ($galleryItem['type'] == 'image'){
			if(file_exists($images_dir.$file)){
				if(file_exists($thumbs_dir.$file)){
					unlink($thumbs_dir.$file);
				}
				unlink($images_dir.$file);
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

if(isset($_POST['uploadsingle'])){
	$message = upload($_FILES, $images_dir, 8388608, array('.jpeg','.jpg','.gif','.png','.JPEG','.JPG','.GIF','.PNG'));
}

if(isset($_FILES["myfile"])){
	multi_upload($_FILES, $images_dir);
}

//Add/Delete DB items based on files in galley folder
CheckGalleryFiles(urldecode($gallery['id']));
?>

<?php
$pgsettings = array(
	"title" => "Editing: ".$blog['title'],
	"pageselection" => "blog",
	"nav" => true,
	"banner" => 0,
	"use_google_analytics" => 1,
);
require_once("includes/begin_html.php");

?><!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>-->
<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: "textarea",
		theme: "modern",
		skin: 'light',
		width: '100%',
		height: '400px',
		resize: false,
		menubar : false,
		statusbar : false,
		plugins: [
			"advlist autolink lists link image charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars fullscreen",
			"insertdatetime media nonbreaking save contextmenu directionality",
			"emoticons paste textcolor code table colorpicker"
		],
		contextmenu: "link image print code fullscreen",
		toolbar1: "insertfile | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image | forecolor backcolor emoticons | undo redo | colorpicker",
		image_advtab: true,
		templates: [
			{title: 'Test template 1', content: 'Test 1'},
			{title: 'Test template 2', content: 'Test 2'}
		]
	});
	
	$(document).ready(function() {
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

		$.get( "administrator/post/gallery_rotate_image.php",
		{
			src: encodeURIComponent("<?php echo '../'.$images_dir; ?>"+$("#galleryItems input[name='galleryItemData["+itemID+"][name]']").val()),
			thumb: encodeURIComponent("<?php echo '../'.$thumbs_dir; ?>"+$("#galleryItems input[name='galleryItemData["+itemID+"][name]']").val()),
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
            		Materialize.toast('Sorry, a data error occured.'+data, 8000, 'red');
            		dfd.resolve();
            	}
            }else{
                Materialize.toast('Sorry, a status error occured.', 8000, 'red');
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
        $.post("administrator/post/gallery_edit_post.php",
        {
            galleryData: $('#galleryItems').serialize(),
        },
        function(data, status){
            if(status == 'success'){
                if(data!=''){
                    //$("#testoutput").html(data);
                }
                Materialize.toast('Gallery updated!', 8000, 'green');
                $('#saveGalleryItems').html('Save');
            }else{
                Materialize.toast('Sorry, an error occured.', 8000, 'red');
                $('#saveGalleryItems').html('Save');
            }
        });
    }

    function updateItem(galleryItemID, galleryItemData){
        $.post("administrator/post/gallery_update_item.php",
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
		$.get( "administrator/post/gallery_add_item.php",
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
<h1 id="editheader">Editing: "<?php echo $blog['title']; ?>"</h1>
<form action="edit_blog_post.php?post=<?php echo $_GET['post'];?>" method="post" name="editpage">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#ffffff">
  <td>
  <input type="text" name="title" class="text" maxlength="1024" value="<?php echo $blog['title']; ?>" /> <input type="checkbox" name="allowComments" id="allowComments" <?php if($blog['comments_allowed']==1){echo 'checked';} ?>><label for="allowComments">Allow Comments</label>
  </td>
  </tr>
  <tr>
    <td style="overflow:hidden;"><textarea name="content" id="blogcontent"><?php echo $blog['content']; ?></textarea></td>
  </tr>
  <tr bgcolor="#ffffff">
  <td>
  <table width="200" border="0">
  <tr>
    <td><input class="btn green" type="submit" name="submit" value="Update Blog Post" /></td>
    <td><a class="btn red" href="page/<?php echo $GLOBALS['blog_page']; ?>&delpost=<?php echo $_GET['post']; ?>">Delete</a></td>
    <td><a class="btn blue" href="<?php echo $GLOBALS['HOST']."/page/".urlencode($page_properties['name']);?>">Cancel</a></td>
  </tr>
</table>
  </td>
</table>
</form>
<br><br>
<p style="text-align:left;">Select all: <input type="checkbox" id="imgall" /><label for="imgall"></label></p>
<form method="post" id="galleryItems">
    <div id="img" style="width:100%;">
    <ul id="sortable" class="GallerySort">
<?php

    /** generate photo gallery **/
    $query="SELECT * FROM  `site_gallery_items` WHERE  `gallery_id` = {$blog['gallery_id']} ORDER BY `position` ASC";
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
  ?><p>(There are no images in this gallery)</p><?php
}
?>
    </ul>
    <div style="clear:both;"></div>
    </div>
	<div class="clear"></div>
    <div onclick="sendPost();" name="saveGalleryItems" id="saveGalleryItems" class="btn green">Save Gallery</div>&nbsp;&nbsp;&nbsp;
    <div onclick="addEmbed();" name="addEmbed" id="addEmbed" class="btn blue">Add YouTube/Vimeo</div>&nbsp;&nbsp;&nbsp;
    <input name="delfiles" type="submit" value="Delete Selected Items" class="btn red" />
</form>

<table border="0" width="100%" border="0" style="margin-right:auto; margin-left:auto;">
  <tr>
    <td></td>
        </tr>
        <tr>
    <td>
    <table>
  <tr>
    <td width="50%">
    	<h2>Upload Multiple photos</h2>
	    <div id="testoutput"></div>
    	<?php print_multi_upload($images_dir,"8mb","jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG"); ?>
	    <script type="text/javascript">
			uploader.bind('FileUploaded', function(upldr, file, object) {
			    var obj = JSON.parse(object.response);
			   //Get and put uploaded image into editor
				$.get( "administrator/post/gallery_add_item.php",
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
    </td>
    <td width="50%"><h2>Upload Single photo</h2><br />
<br />
<form method="post" enctype="multipart/form-data">
<input type="file" name="file" id="file" />
<input name="uploadsingle" type="submit" value="Upload Image" class = "btn blue" />
</form></td>
  </tr>
</table>
</td>
  </tr>
</table>
<!-- Image editor popup -->
<div id="modalImage" class="modal">
	<div class="modal-content" style="text-align:center;">
		<h4>Edit Image</h4>
		<img id="editorImage" height="200" src="" />
		<br/>
		<div id="button-rotate-clockwise" class="waves-effect waves-blue btn blue" style="margin:10px;"><i class="material-icons">rotate_right</i></div><div id="button-rotate-counterclockwise" class="waves-effect waves-blue btn blue" style="margin:10px;"><i class="material-icons">rotate_left</i></div>
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
require_once("includes/end_html.php");
?>