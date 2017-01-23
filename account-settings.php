<?php
require_once("includes/session.php");
require_once("includes/functions.php");
confirm_logged_in();
$noval="--";
?>
<?php
	if(isset($_POST['chngpass'])){
		if(!empty($_POST['cpass']) && !empty($_POST['npass']) && !empty($_POST['cnpass'])){
			$cpass=$_POST['cpass'];
			$npass=$_POST['npass'];
			$cnpass=$_POST['cnpass'];
			$hashedcpass=sha1($cpass);
			$hashednpass=password_hash($npass, PASSWORD_DEFAULT);
			if($npass==$cnpass){
				$query="SELECT hashed_pass, old_pass FROM users
						WHERE `id`={$_SESSION['user_id']}";
				$result=mysqli_query($connection, $query);
				confirm_query($result);
				$pass=mysqli_fetch_array($result);
				if($pass['old_pass'] == 0){
					if(password_verify($cpass, $pass['hashed_pass'])){
						$query="UPDATE `users` SET 
								`hashed_pass` = '{$hashednpass}' 
								WHERE `id` = {$_SESSION['user_id']}";
						$result=mysqli_query($connection, $query);
						confirm_query($result);
						$success="Your password has been changed.";
					}else{
						$error="The current password you entered does not match your password.";
					}
				}else{
					if($pass['hashed_pass']==$hashedcpass){
						$query="UPDATE `users` SET 
								`hashed_pass` = '{$hashednpass}' 
								WHERE `id` = {$_SESSION['user_id']}";
						$result=mysqli_query($connection, $query);
						confirm_query($result);
						$success="Your password has been changed.";
					}else{
						$error="The current password you entered does not match your password.";
					}
				}
			}else{
				$error="Confirm new password does not match New password.";
			}
		}else{
			$error="Required field left blank.";
		}
	}
	if(isset($_POST['uploadprofile'])){
		if($site_info['user_profile_pictures'] == 1){
			$output_dir = USER_DIR.'user-assets/'.$_SESSION['user_id'].'/profile/';
			foreach (scandir($output_dir) as $item) {
				if ($item == '.' || $item == '..') continue;
				unlink($output_dir.DIRECTORY_SEPARATOR.$item);
			}
			$newFileName = randstring();
			$message = upload($_FILES, $output_dir, 8388608, array('.jpeg','.jpg','.gif','.png','.JPEG','.JPG','.GIF','.PNG'), $newFileName);
			$extension = substr($_FILES["file"]["name"], strripos($_FILES["file"]["name"], '.')); // Get extension of uploaded file for use in making thumbnail

			if(strpos($message, ' successfully.')){
				make_thumb($output_dir.$newFileName.$extension, $output_dir.$newFileName."_thumb".$extension, 100, 100, 'fixed');
			}
		}
	}
	if(isset($_GET['deleteprofilepic'])&&$_GET['deleteprofilepic'] == 'true'){
		$img_dir = USER_DIR.'user-assets/'.$_SESSION['user_id'].'/profile/';
		foreach (scandir($img_dir) as $item) {
			if ($item == '.' || $item == '..') continue;
			unlink($img_dir.DIRECTORY_SEPARATOR.$item);
		}
		$success='Deleted Profile Picture';
	}
	if(isset($_POST['chnguser'])){
		$newuser=$_POST['newuser'];
		if($newuser != "--"){
			if(!empty($newuser)&&$newuser!=" "){
				$query="SELECT username FROM `users`
							WHERE username='{$newuser}'";
				$result=mysqli_query($connection, $query);
				confirm_query($result);
				if($newuser=="" || mysqli_num_rows($result)<1){
					$query="UPDATE `users` SET 
							`username` = '{$newuser}' 
							WHERE `id` = {$_SESSION['user_id']}";
					$result=mysqli_query($connection, $query);
					confirm_query($result);
					$success="Your username has been changed.";
				}else{
					$error="An account already has this username.";
				}
			}else{
				$error="Cannot have blank username.";
			}
		}else{
			$error="\"--\" not allowed.";
		}
	}

	if(isset($_POST['chngemail'])){
		$newemail=$_POST['newemail'];
		if(filter_var($newemail,FILTER_VALIDATE_EMAIL)){
			if(!empty($newemail)&&$newemail!=" "){
				$query="UPDATE `users` SET 
						`email` = '{$newemail}' 
						WHERE `id` = {$_SESSION['user_id']}";
				$result=mysqli_query($connection, $query);
				confirm_query($result);
				$success="Your email has been changed.";
			}else{
				$error="Cannot have blank email.";
			}
		}else{
			$error="Invalid Email.";
		}
	}

	if(isset($_POST['clearsubs'])){
		$clearsubs = serialize(array('blog' => array(), 'forum' => array(), 'thread' => array()));
		$query="UPDATE `users` SET 
				`subscriptions` = '{$clearsubs}' 
				WHERE `id` = {$_SESSION['user_id']}";
		$result=mysqli_query($connection, $query);
		confirm_query($result);
		$success="Your subscriptions have been cleared";
	}

	if(isset($_POST['save_other_data'])){
		$err_array = array();
		//Custom Field validations
		$query="SELECT * FROM `custom_field_users_properties`";
		$result=mysqli_query( $connection, $query);
	    if($num_cust_fields = mysqli_num_rows($result)!=0){
	    	while ($field = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				switch($field['validate']){
					case "none":
						
						break;
					case "email":
						if(filter_var($_POST['fields'][$field['name']],FILTER_VALIDATE_EMAIL)){

							break;
						}else{
							$err_array[$field['name']] = "Invalid Email.";
						}
						break;
					case "notempty":
						if($_POST['fields'][$field['name']]!=""){
							break;
						}else{
							$err_array[$field['name']] = "Cannot be empty.";
						}
						break;
					case "numbers":
						if(is_numeric($_POST['fields'][$field['name']])){
							break;
						}else{
							$err_array[$field['name']] = "Only numbers allowed.";
						}
						break;
					case "phone":
						if(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/" ,$_POST['fields'][$field['name']])){
							break;
						}else{
							$err_array[$field['name']] = "Invalid Phone.";
						}
						break;
				}
			}
		}
		if (empty($err_array)){
			$query="SELECT * FROM `users_custom_fields` WHERE `uid` = {$_SESSION['user_id']}";
			$result=mysqli_query($connection, $query);
			if(mysqli_num_rows($result) != 1){
				$query="INSERT INTO `users_custom_fields` (`uid`) VALUES ({$_SESSION['user_id']})";
				$result=mysqli_query($connection, $query);
			}
			$query="UPDATE `users_custom_fields` SET ";
			$num_fields = count($_POST['fields']);
			$i = 1;
			foreach ($_POST['fields'] as $key => $value) {
				if($i<$num_fields){
					$query.="`".$key."` = '".$value."', ";
				}else{
					$query.="`".$key."` = '".$value."'";
				}
				$i++;
			}
			$query.=" WHERE `uid` = {$_SESSION['user_id']}";
			$result=mysqli_query($connection, $query);
			confirm_query($result);
			$success="Your data has been saved.";
		}else{
			$error="Errors saving data. Please correct the errors.";
		}
	}

	if(isset($_POST['save_forum_signature'])){
		$forum_signature = strip_tags(mysqli_real_escape_string($connection, $_POST['forum_signature']),'<br><hr><b><i><u><sup><sup><strong><img><a><ul><ol><li><p><span>');
		if(strlen($forum_signature)<=500){
			$query="UPDATE `users` SET 
					`forum_signature` = '{$forum_signature}' 
					WHERE `id` = {$_SESSION['user_id']}";
			$result=mysqli_query($connection, $query);
			confirm_query($result);
			$success="Your forum signature has been updated.";
		}else{
			$error='Cannot have more than 500 characters in your signature.';
		}
	}

	if(isset($_POST['save_public_visibility'])){
		$fields = array('email', 'last_logged_in');

		$query="SELECT * FROM `custom_field_users_properties`";
		$result=mysqli_query( $connection, $query);
	    if(mysqli_num_rows($result)!=0){
	    	while ($field = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	    		array_push($fields, $field['id']);
	    	}
	    }
	    
		if(isset($_POST['visibility'])){
			$not_visible_data = array_diff($fields, $_POST['visibility']);
			if(!empty($not_visible_data)){
				$not_visible_data = serialize($not_visible_data);
			}else{
				$not_visible_data = '';
			}
		}else{
			$not_visible_data = serialize($fields);
		}
		$query="UPDATE `users` SET 
				`data_public_not_visible` = '{$not_visible_data}' 
				WHERE `id` = {$_SESSION['user_id']}";
		$result=mysqli_query($connection, $query);
		confirm_query($result);
		$success="Your data visibility settings has been changed.";
	}

	//Get logged in user data
	$query="SELECT * FROM `users`
			WHERE `username`='{$_SESSION['username']}'";
	$result=mysqli_query($connection, $query);
	confirm_query($result);
	$user=mysqli_fetch_array($result);
?>
<?php
$pgsettings = array(
	"title" => "Account Settings",
	"pageselection" => false,
	"nav" => true,
	"banner" => 0,
	"use_google_analytics" => 1,
);
require_once("includes/begin_html.php");

//Gets whether the user has a profile picture set or not
$existing_profile_pic = scandir(USER_DIR.'user-assets/'.$_SESSION['user_id'].'/profile/');
if(isset($existing_profile_pic[2])){
	$existing_profile_pic = true;
}else{
	$existing_profile_pic = false;
}
?>
<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	
	tinymce.init({
		selector: "#forum_signature",
		theme: "modern",
		skin: 'light',
		width: '100%',
		plugins: [
			"advlist autolink lists link image charmap print preview anchor",
			"searchreplace wordcount visualblocks visualchars code fullscreen",
			"insertdatetime save directionality",
			"emoticons template paste textcolor"
		],
		toolbar1: "insertfile undo redo | bold italic | bullist numlist outdent indent | link image",
		image_advtab: true
	});

	var cropper;
	
	<?php
	if(isset($message) && strpos($message, ' successfully.')){ ?>
	    $(document).ready(function(){
	    	$('#modalThumb').openModal({
	    		ready: function(modal, trigger){
					cropper = $('#profilethumb').cropper({
						aspectRatio: 1 / 1,
						viewMode: 2,
						movable: false,
						zoomable: false,
						scalable: false,
						autoCropArea: 0.8,
						minCropBoxWidth: 100,
						minCropBoxHeight: 100,
						strict: false,
					});
	    		}
	    	});
	    });
	<?php } ?>
	$(document).ready(function () {
		$('.thumbmodal-trigger').leanModal({
    		ready: function(modal, trigger){
				cropper = $('#profilethumb').cropper({
					aspectRatio: 1 / 1,
					viewMode: 2,
					movable: false,
					zoomable: false,
					scalable: false,
					autoCropArea: 0.8,
					minCropBoxWidth: 100,
					minCropBoxHeight: 100,
					strict: false,
				});
    		}
		});

		//AJAX for updating thumbnail
		$("#update-button-image").on("click", function(){
			$('#update-button-image').html('<img src="<?php echo $GLOBALS['HOST']; ?>/images/ajax-load.gif" style="margin-top:10px;padding-left:11px;padding-right:11px;"/>');
			var cropData = cropper.cropper('getData', true);
		    $.post("<?php echo $GLOBALS['HOST']; ?>/ajax_processing/profile_thumb.php",
		    {
		        off_x: cropData['x'],
		        off_y: cropData['y'],
		        width: cropData['width'],
		        height: cropData['height'],
		    },
		    function(data, status){
		        if(status == 'success'){
		        	if(data == 'success'){
		        		Materialize.toast('Successfully updated profile thumbnail', 8000, 'green');
		        		$('#currentThumb').attr("src", $('#currentThumb').attr("src") + "?r="+new Date().getTime());
		        	}else{
		        		Materialize.toast('An error with the image has occured. '+ data, 8000, 'red');
		        	}
		        }else{
		        	Materialize.toast('An error has occured. Try again later.', 8000, 'red');
		        }
		        $('#update-button-image').html('Update');
		    });
		});
	});

</script>
<div class="col s12">
	<h1>Account Settings</h1>
	</div>
</div>

<div class="card">
<div class="row title">
<div class="col s12"><h5>Change Password</h5>
</div>
</div>
<div class="container">
<form action="account-settings" method="post">
<div class="row">
    <div class="col s12 l3">Current Password:</div>
    <div class="col s12 l9"><input type="password" name="cpass" class="text"/></div>
  </div>
  <div class="row">
    <div class="col s12 l3">New Password:</div>
    <div class="col s12 l9"><input type="password" name="npass" class="text"/></div>
  </div>
  <div class="row">
    <div class="col s12 l3">Confirm New Password:</div>
    <div class="col s12 l9"><input type="password" name="cnpass" class="text"/></div>
    <div class="col col offset-s6 s6 l3"><input type="submit" name="chngpass" value="Save" class="green btn"/></div>
  </div>

</form>
</div>
</div>

<?php if($site_info['user_profile_pictures'] == 1){?>

<div class="card">
<div class="row title">
<div class="col s12"><h5>Profile Picture</h5></div>
</div>
<div class="container">
<form action="account-settings" method="post" enctype="multipart/form-data">
<div class="row">
    <div class="col s9">Choose picture (Max 8MB):<input type="file" name="file" id="file" accept="image/*" /></div>
    <div class="col s3"><img id="currentThumb" src="<?php echo get_user_profile_pic($_SESSION['user_id']); ?>" alt="Profile Picture" /><?php if($existing_profile_pic){ ?><br/><a class="thumbmodal-trigger" href="#modalThumb">[Edit Thumbnail]</a><br/><a href="<?php echo $GLOBALS['HOST'].'/account-settings?deleteprofilepic=true' ?>">[Delete Picture]</a><?php } ?></div>
    <div class="col s9"><input type="submit" name="uploadprofile" value="Upload" class="green btn"/></div>
  </div>
</form>
</div>
</div>
<?php } ?>

<div class="card">
<div class="row title">
<div class="col s12"><h5>Change Username</h5></div>
</div>
<div class="container">
<form action="account-settings" method="post">
<div class="row">
    <div class="col s12 l3">New Username:</div>
    <div class="col s12 l9"><input type="text" name="newuser" class="text" maxlength="50"/></div>
    <div class="col col offset-s6 s6 l3"><input type="submit" name="chnguser" value="Save" class="green btn"/></div>
  </div>
</form>
</div>
</div>

<div class="card">
<div class="row title">
<div class="col s12"><h5>Change Email</h5></div>
</div>
<div class="container">
<form action="account-settings" method="post">
<div class="row">
    <div class="col s12 l3">New Email:</div>
    <div class="col s12 l9"><input type="text" name="newemail" class="text" maxlength="128"/></div>
    <div class="col offset-s6 s6 l3"><input type="submit" name="chngemail" value="Save" class="green btn"/></div>
</div>
</form>
</div>
</div>

<div class="card">
<div class="row title">
<div class="col s12"><h5>Subscriptions</h5></div>
</div>
<div class="container">
<form action="account-settings" method="post">
<div class="row">
    <div class="col offset-s6 s6 l3"><input type="submit" name="clearsubs" value="Delete All Subscriptions" class="green btn"/></div>
</div>
</form>
</div>
</div>

<?php
	$query="SELECT * FROM `custom_field_users_properties`";
	$result=mysqli_query( $connection, $query);

    if(mysqli_num_rows($result)!=0){
?>
<div class="card">
<div class="row title">
<div class="col s12"><h5>Other Data</h5></div>
</div>
<div class="container">
<form action="account-settings" method="post">
<div class="row">
    <?php
  //Custom User Fields
    	while ($field = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    		$query="SELECT * FROM `users_custom_fields` WHERE `uid` = ".$_SESSION['user_id'];
			$cust_field_result=mysqli_query( $connection, $query);
			if(mysqli_num_rows($cust_field_result) != 1){
				$query="INSERT INTO `users_custom_fields` (`uid`) VALUES ({$_SESSION['user_id']})";
				mysqli_query($connection, $query);
			}
			$cust_user_data = mysqli_fetch_array($cust_field_result, MYSQLI_ASSOC);
    		?>
    <div class="col s12 l3"><?php echo $field['name']; ?>:</div>
    <div class="col s12 l12">
    	<?php if($field['type'] == 'text'){ ?>
    	<input type="text" name="fields[<?php echo $field['name']; ?>]" <?php if($field['maxchar']!=0){echo 'maxlength="'.$field['maxchar'].'"';} ?> value="<?php echo $cust_user_data[$field['name']]; ?>" />
    	<?php }elseif($field['type'] == 'textarea'){ ?>
    	<textarea name="fields[<?php echo $field['name']; ?>]" <?php if($field['maxchar']!=0){echo 'maxlength="'.$field['maxchar'].'"';} ?> data-autosize-on="false"><?php echo $cust_user_data[$field['name']]; ?></textarea>
    	<?php } ?>
    </div>
    <div class="col s12 l12"><?php if(isset($err_array[$field['name']])){echo '<span class="error-block">'.$err_array[$field['name']].'</span>';} ?></div>
    <?php
    	}
    ?>
    <div class="col offset-s6 s6 l3"><input type="submit" name="save_other_data" value="Save" class="green btn"/></div>
</div>
</form>
</div>
</div>
<?php
    }
?>

<div class="card">
<div class="row title">
<div class="col s12"><h5>Forum Signature</h5></div>
</div>
<div class="container">
	<form action="account-settings" method="post">
		<textarea name="forum_signature" id="forum_signature" rows="5" cols="80" class="materialize-textarea" maxlength="500"><?php if(isset($forum_signature)){echo $forum_signature;}else{echo $user['forum_signature'];} ?></textarea>
		<p>(Max 500 characters)</p><br/>
		<input type="submit" name="save_forum_signature" value="Save" class="green btn"/><br/><br/>
	</form>
</div>
</div>

<div class="card">
<div class="row title">
<div class="col s12"><h5>Data Visibility Settings</h5></div>
</div>
<div class="container">
<form action="account-settings" method="post">
<div class="row">
<?php
	if($user['data_public_not_visible']!= ''){
		$not_visible = unserialize($user['data_public_not_visible']);
	}else{
		$not_visible = array();
	}
?>
    <div class="col s12 l3">Email:</div>
    <div class="col s12 l9"><input type="checkbox" name="visibility[0]" id="email" value="email"<?php if(!in_array('email', $not_visible)){echo " checked";} ?> /><label for="email"></label></div>
    <div class="col s12 l3">Last Logged In:</div>
    <div class="col s12 l9"><input type="checkbox" name="visibility[1]" id="last_logged_in" value="last_logged_in"<?php if(!in_array('last_logged_in', $not_visible)){echo " checked";} ?> /><label for="last_logged_in"></label></div>
    <?php
  //Custom User Fields
	$query="SELECT * FROM `custom_field_users_properties`";
	$result=mysqli_query( $connection, $query);

    if(mysqli_num_rows($result)!=0){
    	$i = 2;
    	while ($field = mysqli_fetch_array($result, MYSQLI_ASSOC)) {?>
    <div class="col s12 l3"><?php echo $field['name']; ?>:</div>
    <div class="col s12 l9"><input type="checkbox" name="visibility[<?php echo $i; ?>]" id="<?php echo $field['name']; ?>" value="<?php echo $field['id']; ?>"<?php if(!in_array($field['id'], $not_visible)){echo " checked";} ?> /><label for="<?php echo $field['name']; ?>"></label></div>
    <?php
    	$i++;
    	}
    }
  ?>
    <div class="col offset-s6 s6 l3"><input type="submit" name="save_public_visibility" value="Save" class="green btn"/></div>
</div>
</form>
</div>
<?php if($existing_profile_pic && $site_info['user_profile_pictures']){
	$profile_dir = USER_DIR."user-assets/".$_SESSION['user_id']."/profile/";
	if(file_exists($profile_dir)){
		$profile_pic = scandir($profile_dir);
		if(isset($profile_pic[2])){
		    $profile_pic = $profile_pic[2];
		}
	}
	?>
<!-- Image editor popup -->
<div id="modalThumb" class="modal">
	<div class="modal-content" style="text-align:center;">
		<h4>Edit Thumbnail</h4>
		<div style="width: 100%; max-height: 500px;">
			<img id="profilethumb" width="400" style="max-width:100%;" src="<?php echo USER_DIR_URL.'user-assets/'.$_SESSION['user_id'].'/profile/'.$profile_pic; ?>" />
		</div>
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
<?php } ?>
<?php
	require_once("includes/end_html.php");
?>