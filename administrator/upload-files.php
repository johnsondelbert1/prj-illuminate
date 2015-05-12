<?php
require_once("../includes/functions.php");
?>
<?php
if(!check_permission(array("Uploading;upload_files","Uploading;delete_files","Uploading;create_folders","Uploading;rename_folders","Uploading;delete_folders"))){
	redirect_to("index.php");
}

function handleError($errno, $errstr, $errfile, $errline, array $errcontext)
{
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler('handleError');

if(isset($_POST['newfolder'])){
	if(check_permission("Uploading","create_folders")){
		if($_POST['name']!=""){
			$exists = false;
			$dirs = array_filter(glob('../uploads/*'), 'is_dir');
			foreach($dirs as $dir){
				if($_POST['name']==substr($dir, 11)){
					echo substr($dir, 11);
					$exists =  true;
				}
			}
			if($exists == false){
				if (strpbrk($_POST['name'], "\\/?%*:|\"<>") === FALSE) {
					try{
						if(mkdir("../uploads/".$_POST['name'])){
							redirect_to("upload-to-folder.php?folder=".urlencode($_POST['name']."/"));
						}else{
							$error="There was a problem with creating the folder.";
						}
					} catch(ErrorException $errstr){
						$error="There was a problem with creating the folder. ".$errstr;
					}
				}else{
					$error="Folder name cannot contain any of these characters: \\/?%*:|\"<>\"";
				}
			}else{
				$error="There is already a folder named ".$_POST['name'];
			}
		}else{
			$error="Folder name cannot be blank!";
		}
	}else{
		$error="You do not have permission to create folders!";
	}
}

if(isset($_POST['deletefolder'])){
	if(check_permission("Uploading","delete_folders")){
		function del_folder($folder){
			// Specify the target directory and add forward slash
			$dir = "../uploads/".$folder; 
			foreach (scandir($dir) as $item) {
				if ($item == '.' || $item == '..') continue;
				unlink($dir.DIRECTORY_SEPARATOR.$item);
			}
			rmdir($dir);
			
			$success="Folder ".$folder."/ was deleted!";
		}
		if(!empty($_POST['folders'])){
			$i = 0;
			foreach($_POST['folders'] as $folder){
				$success=del_folder($folder);
				$i++;
				if($i > 1){
					$success="Folders were deleted.";
				}
			}
		}else{
			$error="No folders selected!";
		}
	}else{
		$error="You do not have permission to delete folders!";
	}
}

?>
<?php
	$pgsettings = array(
		"title" => "Upload Files",
		"icon" => "icon-upload"
	);
	require_once("includes/begin_cpanel.php");
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript">
   <!-- jQuery for seven sets of "Select All" checkboxes -->
    $(document).ready(function() {
             $('input[id="files"]').click(function() {
             $("#file :checkbox").attr('checked', $(this).attr('checked'));
        });  
     });
</script>
<?php if(check_permission("Uploading","create_folders")){?>
<h1>Create New Folder</h1>
<br />
<form action="upload-files.php" method="post">
    <input name="name" type="text" value="<?php if(isset($_POST['name']))echo $_POST['name']; ?>" maxlength="128" />
    <input name="newfolder" type="submit" value="Create new folder" />
</form>
<?php } ?>
<h1>Folder List</h1>
<?php $dirs = array_filter(glob('../uploads/*'), 'is_dir'); ?>

<form method="post" action="upload-files.php">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="file">
      <tr>
        <th width="25%">Folder:</th>
        <th width="55%">Number of files:</th>
        <?php if(check_permission("Uploading","delete_folders")){?>
        	<th width="10%"><input type="checkbox" id="files"><label for="files"></th>
        <?php } ?>
      </tr>
        <?php
    
            $query="SELECT * FROM `users` ORDER BY `id` ASC";
            $result=mysqli_query( $connection, $query);
            confirm_query($result);
			if (count($dirs!=0)){
				foreach($dirs as $dir){
					$i = 0; 
					$filedir = $dir."/";
					if ($handle = opendir($filedir)) {
						while (($file = readdir($handle)) !== false){
							if (!in_array($file, array('.', '..')) && !is_dir($filedir.$file)) 
								$i++;
						}
					}
					?>
				  <tr>
					<td style="text-align:left;"><a href="upload-to-folder.php?folder=<?php echo urlencode(substr($dir."/", 11));?>"><?php echo substr($dir, 11)."/";?></a></td>
					<td><?php echo $i;?></td>
                    <?php if(check_permission("Uploading","delete_folders")){?>
						<td><input type="checkbox" name="folders[]" id="<?php echo substr($dir, 11); ?>" value="<?php echo substr($dir, 11); ?>" /><label for="<?php echo substr($dir, 11); ?>"></td>
                    <?php } ?>
				  </tr>
            <?php }
			}else{?>
				<tr align="center">
                	<td colspan="3">No folders!</td>
                </tr>
            <?php
			}?>
      <tr>
        <th colspan="2"></th>
        <?php if(check_permission("Uploading","delete_folders")){?>
        	<th width="10%"><input class="red btn" type="submit" name="deletefolder" value="Delete Folders" /></th>
		<?php } ?>
      </tr>
    </table>
</form>
<?php require_once("includes/end_cpanel.php"); ?>