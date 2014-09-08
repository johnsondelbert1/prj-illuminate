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
				
				if(isset($_POST['banner'])){$banner=1;}else{$banner=0;}
				if(isset($_POST['url'])){$url=$_POST['url'];}else{$url="";}
				
				if(isset($_POST['galleries'])&&$_POST['galleries']!=""){
					$galleries=serialize($_POST['galleries']);
				}else{
					$galleries="";
				}
				
				date_default_timezone_set($site_info['timezone']);
				$date=date("Y/m/d H:i:s", time());
				
				$query = "UPDATE `pages` SET `content` = '{$content}', `name`='{$name}', `position`={$position}, `visible`={$_POST['visible']}, 
				`issubpage`={$issub}, `parent`={$subpg}, `galleries`='{$galleries}', `type`='{$_POST['pgtype']}', `target`='{$_POST['target']}', 
				`banner`='{$banner}', `url`='{$url}', `lastedited`='{$date}', `editor`={$_SESSION['user_id']} WHERE id = {$id}";
				
				$result = mysqli_query( $connection, $query);
				if (mysqli_affected_rows($connection) == 1) {
					$success = "The page was successfully updated.";
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
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
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
	 });
	 <!-- jQuery Sticky Menu -->
	 $(document).ready(function() {
    var s = $("#sticker");
    var pos = s.position();                    
    $(window).scroll(function() {
        var windowpos = $(window).scrollTop();
        if (windowpos >= pos.top+10) {
            s.addClass("stick");
			$('.content').css("padding-top","58px");
        } else {
            s.removeClass("stick");
			$('.content').css("padding-top","5px");
        }
    });
});
</script>
<form action="edit_page.php?<?php if(isset($_GET['action'])&&$_GET['action']=="edit"){echo "action=edit&&page=".$selpage['id'];}elseif(isset($_GET['action'])&&$_GET['action']=="newpage"){echo "action=newpage";} ?>" method="post" name="editpage">
<table cellpadding="5" id="sticker">
  <tr>
    <td width="110px"><input class="green" type= "submit" name="<?php if(isset($_GET['action'])&&$_GET['action']=="edit"){echo "submit";}elseif(isset($_GET['action'])&&$_GET['action']=="newpage"){echo "newpage";} ?>" value="Save" /></td>
    <td width="110px"><input class="green" type= "submit" name="<?php if(isset($_GET['action'])&&$_GET['action']=="edit"){echo "sandb";}elseif(isset($_GET['action'])&&$_GET['action']=="newpage"){echo "newandb";} ?>" value="Save & Close" /></td>
    <td width="110px"><input class="green" type= "submit" name="<?php if(isset($_GET['action'])&&$_GET['action']=="edit"){echo "sandnewp";}elseif(isset($_GET['action'])&&$_GET['action']=="newpage"){echo "newandnewp";} ?>" value="Save & New" /></td>
    <?php if(isset($_GET['page'])){?><td width="110px"><a  class="red" href="edit_page.php?action=delpage&&page=<?php echo $_GET['page']; ?>">Delete</a></td><?php } ?>
    <td width="110px"><a class="red" href="page_list.php">Cancel</a></td>
  <td></td>
  </tr>
</table>
<br />

<table width="100%" border="0" cellspacing="5" cellpadding="5" class="editpageform">
  <tr>
    <td align="right">Name:</td>
    <td><input type="text" name="name" value="<?php if(isset($_GET['page'])){echo $selpage['name'];} ?>" /></td>
    
	<td align="right">Visible to:</td>
    <td>
    	<select name="visible">
        	<option value="1"<?php if(isset($_GET['page'])&&$selpage['visible'] == 1){echo ' selected="selected"';} ?>>Everyone</option>
            <option value="2"<?php if(isset($_GET['page'])&&$selpage['visible'] == 2){echo ' selected="selected"';} ?>>Logged in</option>
            <option value="3"<?php if(isset($_GET['page'])&&$selpage['visible'] == 3){echo ' selected="selected"';} ?>>Admins</option>
            <option value="0"<?php if(isset($_GET['page'])&&$selpage['visible'] == 0){echo ' selected="selected"';} ?>>(None)</option>
        </select>
    </td>
    <td rowspan="4">
    <?php
        $query="SELECT * FROM `galleries` ORDER BY `id` ASC";
        $galleryquery=mysqli_query( $connection, $query);
        confirm_query($galleryquery);?>
        <table width="30%" border="0" id="gall">
          <tr>
            <th scope="col" style="text-align:right">Galleries:</th>
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
    </td>
  </tr>
  <tr>
  	<td align="right">Page Order:</td>
    <td>
        <select name="pgorder"><?php $numpages=mysqli_num_rows($listpagesquery)+1; $count=1; while($numpages>=$count){ ?><option value="<?php echo $count; ?>"<?php if(isset($_GET['page'])&&intval($selpage['position'])==$count){echo " selected=\"selected\"";} ?>><?php echo $count;?></option><?php $count++; }  ?>
        <?php if(isset($_GET['action'])&&$_GET['action']=="newpage"){?><option value="<?php echo ($count); ?>" selected="selected"><?php echo ($count); ?></option><?php }?></select>
    </td>
    
    <td align="right">Page Type:</td>
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
  </tr>
  <!--<tr>
  	<td align="right">Icon:</td>
    <td>
    	<input type="text" name="icon" value="<?php if(isset($_GET['page'])){echo $selpage['icon'];} ?>" />
    </td>
  </tr>-->
  <tr>
  	<td align="right">Parent Page:</td>
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
    <td align="right">External URL:</td>
    <td>
    	<input type="text" name="url" id="url" value="<?php if(isset($_GET['page'])){echo $selpage['url'];}else{echo "http://";} ?>" maxlength="1024" <?php if((isset($selpage['type'])&&$selpage['type']!="Link")||$_GET['action']=="newpage"){echo "readonly disabled ";} ?>/>
    </td>
  </tr>
  <tr>
  	<td align="right">Target:</td>
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
    <td align="right">Banner:</td>
    <td>
    	<input type="checkbox" name="banner" <?php if(isset($_GET['page'])&&$selpage['banner']=="1"||$_GET['action']=="newpage"){echo "checked ";} ?>/>
    </td>
  </tr>
  <tr>
  	<td colspan="5" align="center">Page Content:</td>
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