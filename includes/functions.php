<?php
require_once("connection.php");
require_once("session.php");

//global variables
$query="SELECT * FROM `site_info` WHERE `id` = 1";
$result=mysqli_query( $connection, $query);
$site_info=mysqli_fetch_array($result);

$query="SELECT * FROM `site_layout` WHERE `id` = 1";
$result=mysqli_query( $connection, $query);
$site_layout=mysqli_fetch_array($result);

date_default_timezone_set($site_info['timezone']);
$date=date("Y/m/d H:i:s", time());

function get_rank_info(){
	//gets permissions for logged in user
	global $connection;
	$query="SELECT `rank` FROM `users` WHERE `id` = {$_SESSION['user_id']}";
	$result=mysqli_query( $connection, $query);
	$user_rank=mysqli_fetch_array($result);
	$query="SELECT * FROM `ranks` WHERE `id` = {$user_rank['rank']}";
	$result=mysqli_query( $connection, $query);
	$rank_permissions=mysqli_fetch_array($result);
	$user_permissions = unserialize($rank_permissions['permissions']);
	return $user_permissions;
}
$blank_permissions = array(
	"Pages" => array(
		"add_pages" => array("value" => 0, "disp_name" => "Add", "description" => "Enables members of this rank to add a new page to the website"),
		"edit_pages" => array("value" => 0, "disp_name" => "Edit", "description" => "Enables members of this rank to edit existing pages on the website"),
		"delete_pages" => array("value" => 0, "disp_name" => "Delete", "description" => "Enables members of this rank to delete pages on the website"),
	),
	"Blog" => array(
		"post_blog" => array("value" => 0, "disp_name" => "Post", "description" => "Enables members of this rank to post a blog."),
		"edit_blog" => array("value" => 0, "disp_name" => "Edit", "description" => "Enables members of this rank to edit existing blogs."),
		"delete_blog" => array("value" => 0, "disp_name" => "Delete", "description" => "Enables members of this rank to delete."),
	),
	"Forum" => array(
		"add_delete_forum" => array("value" => 0, "disp_name" => "Add & Delete Forums", "description" => "Enables members of this rank to add and delete forums."),
		"edit_forum" => array("value" => 0, "disp_name" => "Edit Forum", "description" => "Enables members of this rank to edit a forum's name and description."),
		"add_thread" => array("value" => 0, "disp_name" => "Post Thread", "description" => "Enables members of this rank to post a thread in the forums."),
		"reply_to_thread" => array("value" => 0, "disp_name" => "Reply to Thread", "description" => "Enables members of this rank to reply to a thread in the forums."),
		"edit_thread" => array("value" => 0, "disp_name" => "Edit Post", "description" => "Enables members of this rank to edit their own post in the forums."),
		"pin_unpin_thread" => array("value" => 0, "disp_name" => "Pin & Unpin Thread", "description" => "Enables members of this rank to pin and unpin threads."),
		"lock_unlock_thread" => array("value" => 0, "disp_name" => "Lock & Unlock Thread", "description" => "Enables members of this rank to lock and unlock threads."),
		"delete_thread" => array("value" => 0, "disp_name" => "Delete Thread", "description" => "Enables members of this rank to delete threads from the forums."),
	),
	"Users" => array(
		"add_users" => array("value" => 0, "disp_name" => "Add Users", "description" => "Enables members of this rank to add users to the website."),
		"delete_users" => array("value" => 0, "disp_name" => "Ban Users", "description" => "Enables members of this rank to ban users from the website."),
		"create_rank" => array("value" => 0, "disp_name" => "Create Ranks", "description" => "Enables members of this rank to create ranks on the website."),
		"edit_rank" => array("value" => 0, "disp_name" => "Edit Ranks", "description" => "Enables members of this rank to edit existing ranks on the website."),
		"delete_rank" => array("value" => 0, "disp_name" => "Delete Ranks", "description" => "Enables members of this rank to delete ranks on the website."),
	),
	"Uploading" => array(
		"upload_files" => array("value" => 0, "disp_name" => "Upload Files", "description" => "Enables members of this rank to upload files to the website."),
		"delete_files" => array("value" => 0, "disp_name" => "Delete Files", "description" => "Enables members of this rank to delete files from the website."),
		"create_folders" => array("value" => 0, "disp_name" => "Create Folders", "description" => "Enables members of this rank to create folers to put files in."),
		"rename_folders" => array("value" => 0, "disp_name" => "Rename Folders", "description" => "Enables members of this rank to rename folders."),
		"delete_folders" => array("value" => 0, "disp_name" => "Delete Folders", "description" => "Enables members of this rank to delete folders."),
	),
	"Galleries" => array(
		"add_gallery" => array("value" => 0, "disp_name" => "Add Galleries", "description" => "Enables members of this rank to add galleries to the website."),
		"edit_gallery" => array("value" => 0, "disp_name" => "Edit Galleries", "description" => "Enables members of this rank to edit existing galleries."),
		"delete_gallery" => array("value" => 0, "disp_name" => "Delete Galleries", "description" => "Enables members of this rank to delete galleries from the website."),
		"rename_gallery" => array("value" => 0, "disp_name" => "Rename Galleries", "description" => "Enables members of this rank to rename galleries."),
	),
	"Forms" => array(
		"add_form" => array("value" => 0, "disp_name" => "Add Forms", "description" => "Enables members of this rank to add forms."),
		"edit_form" => array("value" => 0, "disp_name" => "Edit Forms", "description" => "Enables members of this rank to edit existing forms."),
		"delete_form" => array("value" => 0, "disp_name" => "Delete Forms", "description" => "Enables members of this rank to delete forms."),
	),
	"Website" => array(
		"cpanel_access" => array("value" => 0, "disp_name" => "CPanel Access", "description" => "Enables members of this rank to access the admin CPanel."),
		"edit_site_settings" => array("value" => 0, "disp_name" => "Edit Website Information", "description" => "Enables members of this rank to edit the website information."),
		"edit_site_colors" => array("value" => 0, "disp_name" => "Edit Website Colors", "description" => "Enables members of this rank to modify the website theme colors."),
		"upload_favicon_banner" => array("value" => 0, "disp_name" => "Upload Favicon and Banner", "description" => "Enables members of this rank to upload a favicon and banner to the website."),
		"edit_google_analytics" => array("value" => 0, "disp_name" => "Edit Google Analytics", "description" => "Enables members of this rank to edit the Google Analytics information."),
	)
);

if(logged_in()){
	$permissions = array_replace_recursive($blank_permissions, get_rank_info());
}else{
	$permissions = "";
}

if(isset($_SESSION['user_id'])){
	$query="SELECT * FROM `users` WHERE `id` = {$_SESSION['user_id']}";
	$result=mysqli_query($connection, $query);
	$user_info=mysqli_fetch_array($result);
}

function check_permission($perm_group, $perm = false){
	global $connection;
	global $permissions;
	
	if(logged_in()){
		if (is_array($perm_group)&&$perm==false){
			$return = false;
			foreach ($perm_group as $permission){
				$explode = explode(";", $permission);
				if(isset($permissions[$explode[0]][$explode[1]]['value'])&&$permissions[$explode[0]][$explode[1]]['value']==1){
					$return=true;
				}
				
			}
			return $return;
		}else{
			if(isset($permissions[$perm_group][$perm]['value'])&&$permissions[$perm_group][$perm]['value']==1){
				return true;
			}else{
				return false;
			}
		}
	}else{
		return false;
	}
}

function randstring() {
    $length = 16;
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    $string = "";    
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters)-1)];
    }
    return $string;
}

function del_acc($userid){
	global $connection;
	
	$query="SELECT * FROM `users` WHERE `id` = {$userid}";
	$userquery=mysqli_query( $connection, $query);
	confirm_query($userquery);
	$user = mysqli_fetch_array($userquery);
	
	if($user['deletable']!=0){
		$query="DELETE FROM `users` 
				WHERE `id` =  '{$userid}'";
		$result=mysqli_query($connection, $query);
		confirm_query($result);
		return true;
	}else{
		return false;	
	}
}

function del_rank($rankid){
	global $connection;
	
	$query="SELECT * FROM `ranks` WHERE `id` = {$rankid}";
	$rankquery=mysqli_query( $connection, $query);
	confirm_query($rankquery);
	$rank= mysqli_fetch_array($rankquery);
	
	if($rank['deletable']!=0){
		$query="DELETE FROM `ranks` 
				WHERE `id` =  '{$rankid}'";
		$result=mysqli_query($connection, $query);
		confirm_query($result);
		return true;
	}else{
		return false;	
	}
}

function get_user($id){
	global $connection;
	$query="SELECT * FROM `users` WHERE `id` = {$id}";
	$result=mysqli_query( $connection, $query);
	return $user_info=mysqli_fetch_array($result);
}

function mysql_prep( $value ) {
	global $connection;
	$magic_quotes_active = get_magic_quotes_gpc();
	$new_enough_php = function_exists( "mysqli_real_escape_string" ); // i.e. PHP >= v4.3.0
	if( $new_enough_php ) { // PHP v4.3.0 or higher
		// undo any magic quote effects so mysql_real_escape_string can do the work
		if( $magic_quotes_active ) { $value = stripslashes( $value ); }
		$value = mysqli_real_escape_string($connection,  $value );
	} else { // before PHP v4.3.0
		// if magic quotes aren't already on then add slashes manually
		if( !$magic_quotes_active ) { $value = addslashes( $value ); }
		// if magic quotes are active, then the slashes already exist
	}
	return $value;
}
function check_login($cpanel = false){
	
	?><?php
	if(logged_in()){?>
    		<p><?php 
			echo "<!-- Logged in as: --> <b>".$_SESSION['username']."</b>";
            if($cpanel == false){?>
             | <a href="logout.php">Logout</a> | <a href="account-settings.php">Account Settings</a>
      <?php } 
	}else{ ?>
			<a href="index.php">Register</a> | <a href="login.php">Login</a>
	<?php }?></p>
<?php }

function gallery($images_dir, $thumbs_dir, $thumbs_width, $thumbs_height, $gallname = "gall", $num_images = false){?>
<div align="center" style="text-align:center; width:100%; padding:4px; margin-bottom:10px;">
    <?php
    /** generate photo gallery **/
	if (file_exists($images_dir)&&file_exists($thumbs_dir)){
		$image_files = get_files($images_dir);
		$count = 0;
		if(count($image_files)) {
			$index = 0;
			foreach($image_files as $index=>$file) {
				if($count === $num_images){
					break;
				}
				$count++;
				$index++;
				$thumbnail_image = $thumbs_dir.$file;
				if(!file_exists($thumbnail_image)) {
					$extension = get_file_extension($thumbnail_image);
					$extension = strtolower($extension);
					if($extension) {
						make_thumb($images_dir.$file,$thumbnail_image,$thumbs_width,$thumbs_height,$extension);
					}
				}?>
				<a href="<?php echo $images_dir.$file; ?>" rel="prettyPhoto[<?php echo $gallname; ?>]" title="<?php echo $file; ?>" class="photo-link"><img src="<?php echo $thumbnail_image;?>" style="width:<?php echo $thumbs_width; ?>px; height:<?php echo $thumbs_height; ?>px;" /></a>
			<?php
            }
			?><div class="clear"></div><?php
		}else{?>
			[No images in this gallery]
		<?php
		}
	}else{?>
		<p>Gallery does not exist!</p>
	<?php
    }
	?>
</div>
<?php
}

function upload($files, $directory ,$maxfilesize, $allowed_file_types = false){
	$filename = $files["file"]["name"];
	$file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
	$file_ext = substr($filename, strripos($filename, '.')); // get file name
	$filesize = $files["file"]["size"];
 
	if (!empty($file_basename)) {
 		// rename file
		$newfilename = $files["file"]["name"];
		if (((is_array($allowed_file_types)&&in_array($file_ext,$allowed_file_types))||$allowed_file_types == false)) {
			if ($filesize < $maxfilesize) {	
				if (file_exists($directory . $newfilename)) {		
					// file already exists error
					$message = "You have already uploaded this file.</h2>";			
				} else {		
					if(move_uploaded_file($files["file"]["tmp_name"], $directory.$newfilename)){
						$message = "\"".$newfilename."\" was uploaded successfully.";
					}else{
						$message = "\"".$newfilename."\" was not uploaded successfully.";
					}
				}
			}else{
				// file size error
				$mb = ($maxfilesize / 1024)/1024;
				$message = "The file you are trying to upload is too large. (".$mb." MB max)";
			}
		}else{
			// file type error
			$message = "Only these file types are allowed for upload: " . implode(', ',$allowed_file_types);
			unlink($files["file"]["tmp_name"]);
		}
	} else {	
		// file selection error
		$message = "Please select a file to upload.";	
	}
	return $message;
}

function multi_upload($files, $output_dir){
	
	$ret = array();

	$error =$files["myfile"]["error"];
   {
	
		if(!is_array($files["myfile"]['name'])) //single file
		{
			$RandomNum   = time();
			
			$ImageName      = str_replace(' ','-',strtolower($files['myfile']['name']));
			$ImageType      = $files['myfile']['type']; //"image/png", image/jpeg etc.
		 
			$ImageExt = substr($ImageName, strrpos($ImageName, '.'));
			$ImageExt       = str_replace('.','',$ImageExt);
			$ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
			$NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;

			move_uploaded_file($files["myfile"]["tmp_name"],$output_dir. $NewImageName);
			 //echo "<br> Error: ".$files["myfile"]["error"];
			 
				 $ret[$fileName]= $output_dir.$NewImageName;
		}
		else
		{
			$fileCount = count($files["myfile"]['name']);
			for($i=0; $i < $fileCount; $i++)
			{
				$RandomNum   = time();
			
				$ImageName      = str_replace(' ','-',strtolower($files['myfile']['name'][$i]));
				$ImageType      = $files['myfile']['type'][$i]; //"image/png", image/jpeg etc.
			 
				$ImageExt = substr($ImageName, strrpos($ImageName, '.'));
				$ImageExt       = str_replace('.','',$ImageExt);
				$ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
				$NewImageName = $ImageName.'-'.$RandomNum.'.'.$ImageExt;
				
				$ret[$NewImageName]= $output_dir.$NewImageName;
				move_uploaded_file($files["myfile"]["tmp_name"][$i],$output_dir.$NewImageName );
			}
		}
	}
	echo json_encode($ret);
}

function print_multi_upload($output_dir, $maxsize, $filetypes, $cpanel = false){
?>
<script type="text/javascript" src="<?php if($cpanel == true){echo "../";} ?>jscripts/plupload.full.min.js"></script>
<p>(If using Internet Explorer, you must have version 10 or above.)</p>
<div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
<br />

<div id="container">
    <a class="blue" id="pickfiles" href="javascript:;">Select files</a> 
    <a class="green" id="uploadfiles" href="javascript:;">Upload</a>
</div>

<br />
<pre id="console"></pre>


<script type="text/javascript">
// Custom example logic

var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles', // you can pass in id...
	container: document.getElementById('container'), // ... or DOM Element itself
	url : '<?php if($cpanel == true){echo "../";} ?>upload.php?dir=<?php if($cpanel==true){echo urlencode(substr($output_dir, 3));}else{echo urlencode($output_dir);} ?>',
	flash_swf_url : '<?php if($cpanel == true){echo "../";} ?>jscripts/Moxie.swf',
	silverlight_xap_url : '<?php if($cpanel == true){echo "../";} ?>jscripts/Moxie.xap',
	
	filters : {
		max_file_size : '<?php echo $maxsize; ?>',
		<?php if($filetypes!=false){?>
		mime_types: [
			{title : "Image files", extensions : "<?php echo $filetypes;?>"},
			{title : "Zip files", extensions : "zip"}
		]
		<?php } ?>
	},

	init: {
		PostInit: function() {
			document.getElementById('filelist').innerHTML = '';

			document.getElementById('uploadfiles').onclick = function() {
				uploader.start();
				return false;
			};
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
			});
		},

		UploadProgress: function(up, file) {
			document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
		},

		Error: function(up, err) {
			document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
		}
	}
});

uploader.init();

</script>

<?php
}

function redirect_to($location=NULL){
	if($location!=NULL){
		header("Location: {$location}");
		exit;
	}
}

function confirm_query($result){
	global $connection;
	if(!$result){
		die("Query failed: ".mysqli_error($connection));	
	}else{
		return true;
	}
}

function nav($position, $pgselection){
	global $connection;
	global $site_layout;
	if($position=="horiz"){
		$query="SELECT * FROM `pages` WHERE `horiz_menu` = 1 AND `issubpage` = 0 AND `published` = 1 ORDER BY `position` ASC";
	}elseif($position=="vert"){
		$query="SELECT * FROM `pages` WHERE `vert_menu` = 1 AND `issubpage` = 0 AND `published` = 1 ORDER BY `position` ASC";
	}
	$result=mysqli_query( $connection, $query);
	$numpages=mysqli_num_rows($result);
	if($numpages!=0){
		if($position=="horiz"){ ?>
    		<ul id="horiz-menu">
    	<?php }elseif($position=="vert"){ ?>
        	<td style="vertical-align:top; min-width:150px; padding-right:5px; background-color:<?php echo $site_layout['menu_color'] ?>;"><div style="width:100%; background-color:<?php echo $site_layout['menu_color'] ?>;">
    		<ul id="vert-menu">
    	<?php }
		
            //$buttonwidth = $numpages;
            //$buttonwidth = 900 - $buttonwidth;
            //$buttonwidth = $buttonwidth / $numpages + 1;
            if($position=="horiz"){
                $buttonwidth = round(100 / $numpages, 4);
            }elseif($position=="vert"){
                $buttonwidth = 100;
            }
            
            $pageorder = 0;
                
            while($page=mysqli_fetch_array($result)){
                if($page['issubpage']==0){ $lastmainpage=$page['id'];?>
                <li style="min-width:<?php echo $buttonwidth; ?>%;"<?php if($pgselection=="true"){if(urlencode($page['name'])==$_GET['page']){echo " class=\"selected\"";}} ?>><a style="min-width:<?php echo $buttonwidth; ?>%;" href="<?php
                        if($page['type']=='Custom' || $page['type']=='Staff'){
                            ?>index.php?page=<?php echo urlencode($page['name']);
                        }elseif($page['type']=='Blog'){
                            ?>blog.php<?php
                        }elseif($page['type']=='Forum'){
                            ?>forums.php<?php
                        }elseif($page['type']=='Link'){
                            echo $page['url'];
                        }
                    ?>" <?php if($page['target']!="_self"){echo "target=\"".$page['target']."\"";} ?>><?php echo $page['name'];?></a><?php 
                    $query="SELECT * FROM `pages` WHERE `horiz_menu` = 1 AND `issubpage` = 1 AND `published` = 1 AND `parent`={$page['id']} ORDER BY `position` ASC";
                    $subpgresult=mysqli_query( $connection, $query);
                    confirm_query($subpgresult);
                    if(mysqli_num_rows($subpgresult)!=0){?>
                        <ul style="min-width:100%;">
                        <?php while($subpage=mysqli_fetch_array($subpgresult)){?>
                            <li style="width:100%;"<?php if($pgselection=="true"){if(urlencode($subpage['name'])==$_GET['page']){echo " class=\"selected\"";}} ?>>
                                <a href="<?php
                                    if($subpage['type']=='Custom' || $page['type']=='Staff'){
                                        ?>index.php?page=<?php echo urlencode($subpage['name']);
                                    }elseif($subpage['type']=='Blog'){
                                        ?>blog.php<?php
                                    }elseif($subpage['type']=='Forum'){
                                        ?>forums.php<?php
                                    }elseif($subpage['type']=='Link'){
                                        echo $subpage['url'];
                                    }
                    ?>" <?php if($subpage['target']!="_self"){echo "target=\"".$subpage['target']."\"";} ?>><?php echo $subpage['name'];?></a>
                            </li>
                        <?php } ?>
                        </ul>
                    <?php } ?>
                    </li>
                <?php
                }
            }
		?>
		</ul>
        <?php if($position=="vert"){?>
        </div></td>
        <?php }?>
	<?php
	}
    $query="SELECT * FROM  `features` WHERE  `id` =  1";
    $result=mysqli_query($connection, $query);
    $feature=mysqli_fetch_array($result);
    if($feature['twitterfeed']==1){
    ?>
    
    <!--<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
    <script>
    new TWTR.Widget({
      version: 2,
      type: 'profile',
      rpp: 4,
      interval: 30000,
      width: 135,
      height: 300,
      theme: {
        shell: {
          background: '#FFFFFF',
          color: '#000000'
        },
        tweets: {
          background: '#000000',
          color: '#ffffff',
          links: '#4aed05'
        }
      },
      features: {
        scrollbar: false,
        loop: false,
        live: false,
        behavior: 'all'
      }
    }).render().setUser('<?php //echo $feature['twitteruser']; ?>').start();
    </script>-->
    
    <?php
    }
	return $numpages;
}

function echo_page($num_pages, $current_page, $url){
    echo "<p>Page ".$current_page." of ".$num_pages."</p>";
	
	if($current_page>1){ ?>
    	<a href="<?php echo $url; ?>&page=<?php echo $current_page - 1; ?>">&#60; Prev</a>
    <?php } ?>
	<?php if($num_pages>1&&$current_page<$num_pages){ ?>
    	<a href="<?php echo $url; ?>&page=<?php echo $current_page + 1; ?>"> | Next &#62;</a>
    <?php } ?>
    <br/>
    <?php
}

/* function:  generates thumbnail */
function make_thumb($src,$dest,$desired_width,$desired_height,$extention) {
  /* read the source image */
  if($extention=="jpg" || $extention=="jpeg"){
  	$source_image = imagecreatefromjpeg($src);
  }elseif($extention=="gif"){
  	$source_image = imagecreatefromgif($src);
  }elseif($extention=="png"){
  	$source_image = imagecreatefrompng($src);
  }
  $width = imagesx($source_image);
  $height = imagesy($source_image);
  /* find the "desired height" of this thumbnail, relative to the desired width  */
  $desired_height = $desired_height;
  /* create a new, "virtual" image */
  $virtual_image = imagecreatetruecolor($desired_width,$desired_height);
  /* copy source image at a resized size */
  imagecopyresampled($virtual_image,$source_image,0,0,0,0,$desired_width,$desired_height,$width,$height);
  /* create the physical thumbnail image to its destination */
  if($extention=="jpg" || $extention=="jpeg"){
  	imagejpeg($virtual_image,$dest);
  }elseif($extention=="gif"){
  	imagegif($virtual_image,$dest);
  }elseif($extention=="png"){
  	imagepng($virtual_image,$dest);
  }
}

/* function:  returns files from dir */
function get_files($images_dir,$exts = array('jpg','jpeg','gif','png','JPG','JPEG','GIF','PNG')) {
  $files = array();
  if($handle = opendir($images_dir)) {
    while(false !== ($file = readdir($handle))) {
      $extension = strtolower(get_file_extension($file));
      if($extension && in_array($extension,$exts)) {
        $files[] = $file;
      }
    }
    closedir($handle);
  }
  return $files;
}

/* function:  returns a file's extension */
function get_file_extension($file_name) {
  return substr(strrchr($file_name,'.'),1);
}
?>