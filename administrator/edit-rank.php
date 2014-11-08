<?php
require_once("../includes/functions.php");
?>
<?php
if(!check_permission("Users","edit_rank")){
	redirect_to("ranks.php?error=".urlencode("You do not have permission to edit ranks!"));
}

if(isset($_POST['submit'])){
	
	//Get rank properties
	$editing_rank = urldecode($_GET['rank']);
	$query="SELECT * FROM `ranks` WHERE `id` = '{$editing_rank}'";
	$result=mysqli_query( $connection, $query);
	confirm_query($result);
	$editing_rank = mysqli_fetch_array($result);
	$editing_rank_perms = unserialize($editing_rank['permissions']);
	
	if($editing_rank['editable']==1){
		$name=strip_tags(trim(mysql_prep($_POST['name']), " \t\n\r\0\x0B"));
		$color=strip_tags(trim(mysql_prep($_POST['color']), " \t\n\r\0\x0B"));
		
		if(!empty($_POST['permissions'])){
			$new_rank_perms = str_replace("on",1,$_POST['permissions']);
			$new_rank_perms = serialize($new_rank_perms);
		}else{
			$new_rank_perms = "";
		}
		
		if ($name!=""){
			$query="UPDATE `ranks` SET
						name = '{$name}', permissions = '{$new_rank_perms}', color = '{$color}' WHERE `id` = {$editing_rank['id']}";
			$result=mysqli_query( $connection, $query);
			confirm_query($result);
			$success="Rank updated!";
			
			$query="SELECT * FROM `ranks` WHERE `id` = '{$editing_rank['id']}'";
			$result=mysqli_query( $connection, $query);
			confirm_query($result);
			$editing_rank = mysqli_fetch_array($result);
		}else{
			$error="Name is left blank.";
		}
	}else{
		$color=strip_tags(trim(mysql_prep($_POST['color']), " \t\n\r\0\x0B"));
		
		$query="UPDATE `ranks` SET
					color = '{$color}' WHERE `id` = {$editing_rank['id']}";
		$result=mysqli_query( $connection, $query);
		confirm_query($result);
		$success="Rank updated!";
		
		$query="SELECT * FROM `ranks` WHERE `id` = '{$editing_rank['id']}'";
		$result=mysqli_query( $connection, $query);
		confirm_query($result);
		$editing_rank = mysqli_fetch_array($result);
	}
}

//Get rank properties
$editing_rank = urldecode($_GET['rank']);
$query="SELECT * FROM `ranks` WHERE `id` = '{$editing_rank}'";
$result=mysqli_query( $connection, $query);
confirm_query($result);
$editing_rank = mysqli_fetch_array($result);
$editing_rank_perms = unserialize($editing_rank['permissions']);

//Build rank permission array based on blank permission array
$editing_permission = $blank_permissions;
if(!empty($editing_rank_perms)){
	$editing_permission = array_replace_recursive($blank_permissions, $editing_rank_perms);
}

?>
<?php
	$pgsettings = array(
		"title" => "Editing rank: ".$editing_rank['name'],
		"icon" => "icon-flag"
	);
	require_once("includes/begin_cpanel.php");
?>
<script type="text/javascript">
   <!-- jQuery for seven sets of "Select All" checkboxes -->
    $(document).ready(function() {
             $('input[id="ranks"]').click(function() {
             $("#rank :checkbox").attr('checked', $(this).attr('checked'));
        });  
     });
</script>
<form method="post" action="edit-rank.php?rank=<?php echo $_GET['rank']; ?>">
<table cellpadding="5" id="sticker">
  <tr>
    <td width="110px"><input name="submit" type="submit" value="Update Rank" class="green"/></td>
    <td width="110px"><a class="red" href="ranks.php">Cancel</a></td>
  <td></td>
  </tr>
</table>

<table width="100%" border="0" cellpadding="0" class="form">
  
  <tr>
    <td>Name:<br/><input name="name" placeholder="Name" type="text" value="<?php echo $editing_rank['name']; ?>" maxlength="50" /></td>
  </tr>
  <tr>
    <td>Color:<br/><input name="color" type="text" value="<?php echo $editing_rank['color'];?>" style="background-color:<?php echo $editing_rank['color'];?>" maxlength="7" class="color {hash:true, required:false}" /></td>
  </tr>
  <?php if($editing_rank['editable']!=0){?>
  <tr>
    <td>Permissions:
    	<span style="font-size:14px;">
            <ul id="ranks">
                <?php
                foreach($blank_permissions as $perm_group_key => $perm_group){?>
                    <li>
                        <?php
                        if (is_array($perm_group)){?>
                            <?php echo "<p>".$perm_group_key."</p>"; ?>
                            <table>
                                <?php
								foreach($perm_group as $permission_key => $permission){
                                ?>
                                <tr>
                                    <td>
                                                                            <input type="checkbox" name="permissions[<?php echo $perm_group_key; ?>][<?php echo $permission_key; ?>][value]"<?php if($editing_permission[$perm_group_key][$permission_key]['value']==1){echo " checked";} ?> value="1" />
                                        <span class="tooltips"><?php echo $permission['disp_name']; ?><?php if($permission['description']!=""){echo "<span>".$permission['description']."</span>";}?></span>
                                    </td>
                                    <td></li>
                                    </td>
                                </tr>
                                <?php
								}
                                ?>
                            </table>
                            <?php
                            
                        }else{?>
                            <li><?php echo $perm_group; ?></li>
                            <?php
                        }?>
                    </li>
                <?php }?>
            </ul>
        </span>
    </td>
  </tr>
  <?php }?>
</table>
</form>
<?php require_once("includes/end_cpanel.php"); ?>