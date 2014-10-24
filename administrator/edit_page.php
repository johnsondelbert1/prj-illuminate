<?php
require_once("../includes/functions.php");
?>
<?php
if(isset($_GET['action'])&&$_GET['action']=='newpage'){
	if(!check_permission("Pages","add_pages")){
		redirect_to("index.php");
	}
}elseif(isset($_GET['action'])&&$_GET['action']=='edit'){
	if(!check_permission("Pages","edit_pages")){
		redirect_to("index.php");
	}
}

$query="SELECT * FROM `pages`";
$result=mysqli_query( $connection, $query);
$rows=mysqli_num_rows($result);

if(($rows)!=0||(isset($_GET['action'])&&$_GET['action']=='newpage')){
	if((isset($_GET['action'])&&isset($_GET['page']))||isset($_GET['action'])&&$_GET['action']=='newpage'){
		if(isset($_POST['submit'])||isset($_POST['sandb'])||isset($_POST['sandnewp'])){
			
			$query="SELECT * FROM `pages` WHERE `name`='{$_POST['name']}'";
			$result = mysqli_query( $connection, $query);
			$samenames = mysqli_num_rows($result);
			
			$query="SELECT * FROM `pages` WHERE `id`='{$_GET['page']}'";
			$result = mysqli_query( $connection, $query);
			$current_page = mysqli_fetch_array($result);
			
			if($samenames==0||$current_page['name']==$_POST['name']){
				$id=$_GET['page'];
				$content= mysqli_real_escape_string($connection, $_POST['content']);
				$name=mysqli_real_escape_string($connection, $_POST['name']);
				$position=$_POST['pgorder'];
				
				$issub=0;
				if($_POST['sub']!='none'){
					$issub=1;
				}
				if($issub==1){$subpg=$_POST['sub'];}else{$subpg=0;}
				
				if(isset($_POST['published'])){$published=1;}else{$published=0;}
				if(isset($_POST['banner'])){$banner=1;}else{$banner=0;}
				if(isset($_POST['horiz_menu'])){$horiz_menu=1;}else{$horiz_menu=0;}
				if(isset($_POST['vert_menu'])){$vert_menu=1;}else{$vert_menu=0;}
				if(isset($_POST['horiz_menu_visible'])){$horiz_menu_visible=1;}else{$horiz_menu_visible=0;}
				if(isset($_POST['vert_menu_visible'])){$vert_menu_visible=1;}else{$vert_menu_visible=0;}
				
				if(isset($_POST['url'])){$url=$_POST['url'];}else{$url="";}
				
				if(isset($_POST['galleries'])&&$_POST['galleries']!=""){
					$galleries=serialize($_POST['galleries']);
				}else{
					$galleries="";
				}
				if(isset($_POST['forms'])&&$_POST['forms']!=""){
					$forms=serialize($_POST['forms']);
				}else{
					$forms="";
				}
				
				date_default_timezone_set($site_info['timezone']);
				$date=date("Y/m/d H:i:s", time());
				
				//Check for another Blog or Forum page type
				if($_POST['pgtype']=="Blog" || $_POST['pgtype']=="Forum"){
					$query="SELECT * FROM `pages` WHERE `type`='{$_POST['pgtype']}'";
					$result_pgtype = mysqli_query( $connection, $query);
					
					if(mysqli_num_rows($result_pgtype)>=1){
						$other_page_name = "";
						while ($pagetype = mysqli_fetch_array($result_pgtype)){
							if($pagetype['id']!=$id){
								$query = "UPDATE `pages` SET `type`='Custom' WHERE id = {$pagetype['id']}";
								mysqli_query( $connection, $query);
								$other_page_name = $pagetype['name'];
							}else{
								$success = "The page was successfully updated.";
							}
						}
						if(!isset($success)){$success = "The page was successfully updated, but there is only one ".$_POST['pgtype']." type page allowed. \"".$other_page_name."'s\" page type was changed to \"Custom\".";}
					}else{
						$success = "The page was successfully updated.";
					}
				}
				
				$query = "UPDATE `pages` SET `content` = '{$content}', `name`='{$name}', `position`={$position}, `published`='{$published}', `visible`={$_POST['visible']}, 
				`issubpage`={$issub}, `parent`={$subpg}, `galleries`='{$galleries}', `forms`='{$forms}', `type`='{$_POST['pgtype']}', `target`='{$_POST['target']}', 
				`banner`='{$banner}', `url`='{$url}', `lastedited`='{$date}', `editor`={$_SESSION['user_id']}, `horiz_menu`={$horiz_menu}, `vert_menu`={$vert_menu}, 
				`horiz_menu_visible`={$horiz_menu_visible}, `vert_menu_visible`={$vert_menu_visible} WHERE id = {$id}";
				
				$result = mysqli_query( $connection, $query);
				if (mysqli_affected_rows($connection) == 1) {
					if(!isset($success)){$success = "The page was successfully updated.";}
				} else {
					$error = "The page could not be updated.";
					$error .= "<br />" . mysqli_error($connection);
				}
				
				if(isset($_POST['sandb'])){
					redirect_to("page_list.php");
				}elseif(isset($_POST['sandnewp'])){
					redirect_to("edit_page.php?action=newpage");
				}
			}else{
				$error = "There is already a page of the same name, \"".$_POST['name']."\".";
			}
		}elseif(isset($_POST['newpage'])||isset($_POST['newandb'])||isset($_POST['newandnewp'])){	
			
			$content=mysqli_real_escape_string($connection, $_POST['content']);
			$name=mysqli_real_escape_string($connection, $_POST['name']);
			$position=$_POST['pgorder'];
			
			$query="SELECT * FROM `pages` WHERE `name`='{$name}'";
			$result = mysqli_query( $connection, $query);
			$samenames = mysqli_num_rows($result);
			
			if($samenames==0){	
				$issub=0;
				if($_POST['sub']!='none'){
					$issub=1;
				}
				if($issub==1){$subpg=$_POST['sub'];}else{$subpg=0;}
				
				if(isset($_POST['banner'])){$banner=1;}else{$banner=0;}
				if(isset($_POST['url'])){$url=$_POST['url'];}else{$url="";}
				
				if(isset($_POST['galleries'])&&$_POST['galleries']!=""){
					$galleries=serialize($_POST['galleries']);
				}else{
					$galleries="";
				}
				
				date_default_timezone_set($site_info['timezone']);
				$date=date("Y/m/d H:i:s", time());
				
				if($_POST['name']!=""){
					$query="INSERT INTO `pages` 
					(`name`, `content`, `position`, `issubpage`, `parent`, `visible`, `galleries`, `type`, `target`, `banner`, `url`, `created`, `creator`) 
					VALUES 
					('{$name}', '{$content}', '{$position}', '{$issub}', '{$subpg}', {$_POST['visible']}, '{$galleries}', '{$_POST['pgtype']}', '{$_POST['target']}', '{$banner}', '{$url}', '{$date}', {$_SESSION['user_id']})";
					$result = mysqli_query( $connection, $query);
					confirm_query($result);
					if(isset($_POST['newpage'])){
						$query="SELECT * FROM `pages` ORDER BY `id` DESC LIMIT 1";
						$result=mysqli_query( $connection, $query);
						$editedpage=mysqli_fetch_array($result);
						redirect_to("edit_page.php?action=edit&page=".$editedpage['id']);
					}elseif(isset($_POST['newandb'])){
						redirect_to("page_list.php");
					}elseif(isset($_POST['newandnewp'])){
						redirect_to("edit_page.php?action=newpage");
					}
				}else{
					$error = "Page name cannot be blank.";
				}
			}else{
				$error = "There is already a page of the same name, \"".$_POST['name']."\".";
			}
			
		}elseif(isset($_GET['action'])&&$_GET['action']=="delpage"){
			$query="DELETE FROM `pages` WHERE id={$_GET['page']}";
			$result = mysqli_query( $connection, $query);
			
			$query="SELECT * FROM `pages` ORDER BY `position` ASC LIMIT 1";
			$result=mysqli_query( $connection, $query);
			$firstpage=mysqli_fetch_array($result);
			redirect_to("edit_page.php?action=edit&page=".$firstpage['id']);
		}	
	}else{
		$query="SELECT * FROM `pages` ORDER BY `position` ASC LIMIT 1";
		$result=mysqli_query( $connection, $query);
		$firstpage=mysqli_fetch_array($result);
		redirect_to("edit_page.php?action=edit&page=".$firstpage['id']);
	}
}else{
	redirect_to("edit_page.php?action=newpage");
}
?>

<?php
	$query="SELECT * FROM `pages` ORDER BY `position` ASC";
	$listpagesquery=mysqli_query( $connection, $query);
	confirm_query($listpagesquery);
?>

<?php
	$query="SELECT position FROM `pages` ORDER BY `position` ASC";
	$listidsquery=mysqli_query( $connection, $query);
	confirm_query($listidsquery);
?>

<?php	
if(isset($_GET['page'])){
	$query="SELECT * FROM `pages` WHERE id={$_GET['page']}";
	$getpagequery=mysqli_query( $connection, $query);
	confirm_query($getpagequery);
	$selpage=mysqli_fetch_array($getpagequery);
}
?>

<?php
$title="";
if($_GET['action']=="edit"){
	$title = "Editing Page: ".$selpage['name'];
}elseif($_GET['action']=="newpage"){
	$title = "Adding new Page";
} ?>
<?php
	$pgsettings = array(
		"title" => $title,
		"icon" => "icon-newspaper"
	);
	require_once("includes/begin_cpanel.php");
?>
<script type="text/javascript" src="../tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: "textarea",
		theme: "modern",
		skin: 'light',
		width: '100%',
		plugins: [
			"advlist autolink lists link image charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen",
			"insertdatetime media nonbreaking save table contextmenu directionality",
			"emoticons template paste textcolor"
		],
		toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
		toolbar2: "print preview media | forecolor backcolor emoticons",
		image_advtab: true,
		templates: [
			{title: 'Test template 1', content: 'Test 1'},
			{title: 'Test template 2', content: 'Test 2'}
		]
	});
	
	function disable(select){
		 var selectedOption = select.options[select.selectedIndex];
		 
		 if(selectedOption.value == "Link"){
			 document.getElementById('url').disabled = false;
			 document.getElementById('url').readOnly = false;
		 }else{
			document.getElementById('url').disabled = true; 
			document.getElementById('url').readOnly = true; 
		 }
		 
		 
	}
	<!-- jQuery for seven sets of "Select All" checkboxes -->
	$(document).ready(function() {
			 $('input[id="gallall"]').click(function() {
			 $("#gall :checkbox").attr('checked', $(this).attr('checked'));
		});
			 $('input[id="formall"]').click(function() {
			 $("#form :checkbox").attr('checked', $(this).attr('checked'));
		});
	 });
</script>
<form action="edit_page.php?<?php if(isset($_GET['action'])&&$_GET['action']=="edit"){echo "action=edit&&page=".$selpage['id'];}elseif(isset($_GET['action'])&&$_GET['action']=="newpage"){echo "action=newpage";} ?>" method="post" name="editpage">
<table cellpadding="5" id="sticker">
  <tr>
  	<?php if(check_permission("Pages","add_pages")){?>
        <td width="110px"><input class="green" type= "submit" name="<?php if(isset($_GET['action'])&&$_GET['action']=="edit"){echo "submit";}elseif(isset($_GET['action'])&&$_GET['action']=="newpage"){echo "newpage";} ?>" value="Save" /></td>
        <td width="110px"><input class="green" type= "submit" name="<?php if(isset($_GET['action'])&&$_GET['action']=="edit"){echo "sandb";}elseif(isset($_GET['action'])&&$_GET['action']=="newpage"){echo "newandb";} ?>" value="Save & Close" /></td>
        <td width="110px"><input class="green" type= "submit" name="<?php if(isset($_GET['action'])&&$_GET['action']=="edit"){echo "sandnewp";}elseif(isset($_GET['action'])&&$_GET['action']=="newpage"){echo "newandnewp";} ?>" value="Save & New" /></td>
	<?php } ?>
    <?php if(check_permission("Pages","delete_pages")&&$_GET['action']=="edit"){?>
        <?php if(isset($_GET['page'])){?><td width="110px"><a class="red" href="edit_page.php?action=delpage&&page=<?php echo $_GET['page']; ?>">Delete</a></td><?php } ?>
    <?php } ?>
    <td width="110px"><a class="red" href="page_list.php">Cancel</a></td>
    <td></td>
  </tr>
</table>
<br />

<table width="100%" border="0" cellspacing="5" cellpadding="5" class="editpageform">
  <tr>
    <td align="right"><b>Name:</b></td>
    <td>
    	<input type="text" name="name" value="<?php if(isset($_GET['page'])){echo $selpage['name'];} ?>" />
    </td>
	<td>
    	<b>Published:</b> <input type="checkbox" name="published" <?php if(isset($_GET['page'])&&$selpage['published']==1){echo "checked ";} ?>/>
    </td>
    <td align="right">
		<b>Visible to:</b>
    </td>
    <td>
    	<select name="visible">
        	<option value="1"<?php if(isset($_GET['page'])&&$selpage['visible'] == 1){echo ' selected="selected"';} ?>>Everyone</option>
            <option value="2"<?php if(isset($_GET['page'])&&$selpage['visible'] == 2){echo ' selected="selected"';} ?>>Logged in</option>
            <option value="3"<?php if(isset($_GET['page'])&&$selpage['visible'] == 3){echo ' selected="selected"';} ?>>Admins</option>
            <option value="0"<?php if(isset($_GET['page'])&&$selpage['visible'] == 0){echo ' selected="selected"';} ?>>(None)</option>
        </select>
    </td>
  </tr>
  <tr>
  	<td align="right"><b>Page Order:</b></td>
    <td>
        <select name="pgorder"><?php $numpages=mysqli_num_rows($listpagesquery)+1; $count=1; while($numpages>=$count){ ?><option value="<?php echo $count; ?>"<?php if(isset($_GET['page'])&&intval($selpage['position'])==$count){echo " selected=\"selected\"";} ?>><?php echo $count;?></option><?php $count++; }  ?>
        <?php if(isset($_GET['action'])&&$_GET['action']=="newpage"){?><option value="<?php echo ($count); ?>" selected="selected"><?php echo ($count); ?></option><?php }?></select>
    </td>
    
    <td align="right"><b>Page Type:</b></td>
    <td>
		<?php
            $types = array('Custom','Blog','Forum','Link');
        ?>
        <select name="pgtype" onchange="disable(this)">
        <?php
        $typecount=0;
        foreach($types as $type){
        ?>
            <option value="<?php echo $type; ?>"<?php if ((isset($selpage['type'])&&$type == $selpage['type'])||$_GET['action']=='newpage'&&$typecount==0){echo ' selected="selected"';} ?>><?php echo $type; ?></option>
        <?php 
        $typecount++;
        } ?>
        </select>
    </td>
    <td rowspan="4">
        <div id="TabbedPanels1" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
                <li class="TabbedPanelsTab" tabindex="0">Galleries</li>
                <li class="TabbedPanelsTab" tabindex="0">Forms</li>
            </ul>
            <div class="TabbedPanelsContentGroup">
                <div class="TabbedPanelsContent">
					<?php
                        $query="SELECT * FROM `galleries` ORDER BY `id` ASC";
                        $galleryquery=mysqli_query( $connection, $query);
                        confirm_query($galleryquery);?>
                        <table width="30%" border="0" id="gall">
                          <tr>
                            <th scope="col" style="text-align:right">Select all:</th>
                            <th scope="col"><input type="checkbox" id="gallall"></th>
                          </tr>
                        <?php
                        while($gallery=mysqli_fetch_array($galleryquery)){
                            $checked = false;
                            if(isset($selpage['galleries'])&&$selpage['galleries']!=""){
                                $pagegalleries=unserialize($selpage['galleries']);
                                foreach($pagegalleries as $pagegalleryid){
                                    if($pagegalleryid == $gallery['id']){
                                        $checked = true;
                                    }
                                }
                            }
                            
                            ?>
                            <tr>
                                <td width="80%" style="text-align:right"><a href="edit_gallery.php?gallid=<?php echo urlencode($gallery['id']); ?>"><?php echo $gallery['name']; ?></a></td>
                                <td width="20%" style="text-align:center;"><input type="checkbox" name="galleries[]" value="<?php echo $gallery['id']; ?>" <?php if($checked == true){echo "checked";} ?> /></td>
                            </tr>
                        
                    <?php } ?>
                    </table>
                </div>
                <div class="TabbedPanelsContent">
					<?php
                        $query="SELECT * FROM `forms` ORDER BY `id` ASC";
                        $formquery=mysqli_query( $connection, $query);
                        confirm_query($formquery);
                        ?><table width="30%" border="0" id="form">
                          <tr>
                            <th scope="col" style="text-align:right">Select all:</th>
                            <th scope="col"><input type="checkbox" id="formall"></th>
                          </tr>
                        <?php
                        while($form=mysqli_fetch_array($formquery)){
                            $checked = false;
                            if(isset($selpage['forms'])&&$selpage['forms']!=""){
                                $pagegalleries=unserialize($selpage['forms']);
                                foreach($pagegalleries as $pageformid){
                                    if($pageformid == $form['id']){
                                        $checked = true;
                                    }
                                }
                            }
                            
                            ?>
                            <tr>
                                <td width="80%" style="text-align:right"><a href="edit_form.php?gallid=<?php echo urlencode($form['id']); ?>"><?php echo $form['name']; ?></a></td>
                                <td width="20%" style="text-align:center;"><input type="checkbox" name="forms[]" value="<?php echo $form['id']; ?>" <?php if($checked == true){echo "checked";} ?> /></td>
                            </tr>
                    <?php } 
                    ?></table>
                </div>
            </div>
        </div>
    </td>
  </tr>
  <!--<tr>
  	<td align="right">Icon:</td>
    <td>
    	<input type="text" name="icon" value="<?php if(isset($_GET['page'])){echo $selpage['icon'];} ?>" />
    </td>
  </tr>-->
  <tr>
  	<td align="right"><b>Parent Page:</b></td>
    <td>
    	<?php
        $query="SELECT * FROM `pages` ORDER BY `position` ASC";
        $listpagesquery=mysqli_query( $connection, $query);
        confirm_query($listpagesquery);
        ?>
        <select name="sub">
        	<option value="none"<?php if(isset($selpage['issubpage'])&&$selpage['issubpage']==0){echo " selected=\"selected\"";} ?>>(None)</option>
			<?php
            while($listpage=mysqli_fetch_array($listpagesquery)){
				if($listpage['issubpage']==0||isset($_GET['page'])&&intval($listpage['id'])!=intval($_GET['page'])){?>
                	<option value="<?php echo $listpage['id'];?>"<?php if(isset($selpage['parent'])&&$selpage['parent']==$listpage['id']){echo " selected=\"selected\"";} ?>><?php echo $listpage['name']?></option>
			<?php }
            }?>
        </select>
    </td>
    <td align="right"><b>External URL:</b></td>
    <td>
    	<input type="text" name="url" id="url" value="<?php if(isset($_GET['page'])){echo $selpage['url'];}else{echo "http://";} ?>" maxlength="1024" <?php if((isset($selpage['type'])&&$selpage['type']!="Link")||$_GET['action']=="newpage"){echo "readonly disabled ";} ?>/>
    </td>
  </tr>
  <tr>
  	<td align="right"><b>Target:</b></td>
    <td>
		<?php
            $targets = array('_blank','_parent','_top');
        ?>
        <select name="target">
        	<option value="_self"<?php if ((isset($selpage['target'])&&$selpage['target']=="_self")){echo ' selected="selected"';}?>>_self</option>
        <?php
        foreach($targets as $target){
        ?>
            <option value="<?php echo $target; ?>"<?php if ((isset($selpage['target'])&&$target == $selpage['target'])){echo ' selected="selected"';} ?>><?php echo $target; ?></option>
        <?php 
        } ?>
        </select>
    </td>
    <td align="right"><b>Banner:</b></td>
    <td>
    	<input type="checkbox" name="banner" <?php if(isset($_GET['page'])&&$selpage['banner']==1||$_GET['action']=="newpage"){echo "checked ";} ?>/>
    </td>
  </tr>
  <tr>
  	<td align="right"><b>Show page on:</b></td>
    <td>
    	Horizontal Menu <input type="checkbox" name="horiz_menu" <?php if(isset($_GET['page'])&&$selpage['horiz_menu']==1){echo "checked ";} ?>/><br>
        Vertical Menu <input type="checkbox" name="vert_menu" <?php if(isset($_GET['page'])&&$selpage['vert_menu']==1){echo "checked ";} ?>/>
    </td>
    <td align="right"><b>Menus visible on page:</b></td>
    <td>
    	Horizontal Menu <input type="checkbox" name="horiz_menu_visible" <?php if(isset($_GET['page'])&&$selpage['horiz_menu_visible']==1){echo "checked ";} ?>/><br>
        Vertical Menu <input type="checkbox" name="vert_menu_visible" <?php if(isset($_GET['page'])&&$selpage['vert_menu_visible']==1){echo "checked ";} ?>/>
    </td>
  </tr>
  <tr>
  	<td colspan="5" align="center"><b>Page Content:</b></td>
  </tr>
  <tr>
  	<td colspan="5">
    	<textarea name="content" id="content" class="text" style="width:860px; height:500px;" ><?php if(isset($_GET['page'])){echo $selpage['content'];} ?></textarea>
    </td>
  </tr>
</table>
</form>
<?php
	require_once("includes/end_cpanel.php");
?>