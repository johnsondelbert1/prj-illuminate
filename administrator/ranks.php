<?php
require_once("../includes/functions.php");
?>
<?php
if(!check_permission(array("Users;create_rank","Users;edit_rank","Users;delete_rank"))){
	redirect_to("index.php");
}
if(isset($_POST['submit'])){
	if(check_permission("Users","create_rank")){
		$name=strip_tags(trim(mysql_prep($_POST['name']), " \t\n\r\0\x0B"));
		$date=date("Y/m/d H:i:s", time());
		
		if ($name!=""){
			$query="INSERT INTO `ranks` (
						name, created 
					) VALUES (
						'{$name}', '{$date}')";
			$result=mysqli_query( $connection, $query);
			confirm_query($result);
			
			$createdquery="SELECT `id` FROM `ranks` ORDER BY `created` DESC LIMIT 1";
			$createdresult=mysqli_query( $connection, $createdquery);
			confirm_query($createdresult);
			$createdrank=mysqli_fetch_array($createdresult);
			redirect_to("edit-rank.php?rank=".urlencode($createdrank['id'])."&success=".urlencode("Rank created!"));
			$success="Rank created!";
		}else{
			$error="Name is left blank.";
		}
	}else{
		$error="You do not have permission to create ranks!";
	}
}
if(isset($_POST['delrank'])){
	if(check_permission("Users","delete_rank")){
		$message="";
		if(!empty($_POST['ranks'])){
			foreach($_POST['ranks'] as $account){
				if(del_rank($account)==true){
					$success="Ranks were deleted!";
				}else{
					$error="Cannot delete an undeletable rank.";
				}
			}
		}else{
			$error="No ranks selected for deletion.";
		}
	}else{
		$error="You do not have permission to delete ranks!";
	}
}

//Gets rank properties
$query="SELECT * FROM `ranks` ORDER BY `id` ASC";
$result=mysqli_query( $connection, $query);
confirm_query($result);
?>
<?php
	$pgsettings = array(
		"title" => "Ranks",
		"icon" => "icon-flag"
	);
	require_once("includes/begin_cpanel.php");
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript">
   <!-- jQuery for seven sets of "Select All" checkboxes -->
    $(document).ready(function() {
             $('input[id="ranks"]').click(function() {
             $("#rank :checkbox").attr('checked', $(this).attr('checked'));
        });  
     });
</script>
<?php if(check_permission("Users","create_rank")){?>
    <h2>Ranks</h2>
    <br />
    <form method="post" action="ranks.php">
    <table width="100%" border="0" cellpadding="0" class="form">
      <tr>
        <td>Name:<br/><input name="name" placeholder="Name" type="text" value="<?php if(isset($name)){echo $name;} ?>" maxlength="50" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input name="submit" type="submit" value="Create New Rank" class="submit"/></td>
      </tr>
    </table>
    </form><br />
<?php } ?>
<h2>Rank List</h2>
<br />
<form method="post" action="ranks.php">
<table width="90%" border="1" cellspacing="0" cellpadding="0" id="rank" style="min-height:100px; background-color:#DDDDDD;">
	<tr>
    	<td style="padding-bottom:12px; min-width:150px;">
        	<table width="100%" border="1" cellspacing="0" cellpadding="0">
            	<tr height="20">
                	<th>
                    	Permission Group
                    </th>
                </tr>
                <tr height="86">
                	<th>
                    	Permission
                    </th>
                </tr>
				<?php
                while($rank=mysqli_fetch_array($result)){?>
                    <tr height="35">
                        <td>
                        	<?php if(check_permission("Users","edit_rank")){?>
                            	<a href="edit-rank.php?rank=<?php echo urlencode($rank['id']);?>"><?php echo $rank['name'];?></a>
                            <?php }else{ ?>
                            	<?php echo $rank['name'];?>
                            <?php } ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </td>
        <td style="width:auto;">
        	<div style="overflow-x:scroll; height:100%; min-width:700px; width:inherit;">
                <table border="1" cellspacing="0" cellpadding="2" width="100%" height="100%">
                  <tr height="20">
                  <?php
                    foreach($blank_permissions as $permisson_group => $single_permissions){?>
                        <th colspan="<?php echo count($single_permissions); ?>"><?php echo $permisson_group; ?></th>
                    <?php
                    }
                    ?>
                  </tr>
                  <tr>
                  
                  <?php
                    foreach($blank_permissions as $permisson_group => $single_permissions){
                        foreach(array_keys($single_permissions) as $single_permisson){?>
                        <td><?php echo $blank_permissions[$permisson_group][$single_permisson]['disp_name']; ?></td>
                    <?php
                        }
                    }
                    ?>
                  </tr>
                    <?php
                
                        $query="SELECT * FROM `ranks` ORDER BY `id` ASC";
                        $result=mysqli_query( $connection, $query);
                        confirm_query($result);
                        
                        //Image filename
                        $yes = "check-green.png";
                        $no = "x-red.png";
                        
                        while($rank=mysqli_fetch_array($result)){
                            $ranks_permissions=unserialize($rank['permissions']);
                            if(empty($ranks_permissions)){
                                $ranks_permissions = $blank_permissions;
                            }
                            $ranks_permissions = array_replace_recursive($blank_permissions, $ranks_permissions);
                            ?>
                          <tr align="center" height="35">
                            <?php
                            foreach(array_keys($blank_permissions) as $perm_group){
                                foreach($blank_permissions[$perm_group] as $perm_key => $permission){?>
                                    <td><img src="images/<?php if($ranks_permissions[$perm_group][$perm_key]['value']==1){echo $yes;}else{echo $no;} ?>" /></td>
                                <?php
                                }
                            }?>
                          </tr>
                        <?php } ?>
                </table>
            </div>
    	</td>
        <td style="padding-bottom:12px;">
        	<table width="100%" border="1" cellspacing="0" cellpadding="0">
            	<tr height="109">
                	<th>
                    	Color
                    </th>
                    <?php if(check_permission("Users","delete_rank")){?>
                        <th>
                            Delete <input type="checkbox" id="ranks" />
                        </th>
                    <?php }?>
                </tr>
				<?php
				$query="SELECT * FROM `ranks` ORDER BY `id` ASC";
				$result=mysqli_query( $connection, $query);
				confirm_query($result);
                while($rank=mysqli_fetch_array($result)){?>
                    <tr height="35">
                        <td<?php if($rank['color']!=""){ ?> style="padding:5px; width:40px; background-color:<?php echo $rank['color'];?>;"<?php } ?>><?php if($rank['color']==""){ echo "(None)"; }?></td>
                        <?php if(check_permission("Users","delete_rank")){?>
                        	<td><?php if($rank['deletable']==1){ ?><input type="checkbox" name="ranks[]" value="<?php echo $rank['id']; ?>" /><?php }else{ ?><input type="checkbox" disabled /><?php } ?></td>
                        <?php }?>
                    </tr>
                <?php
                }
                ?>
            </table>
        </td>
    </tr>
    <tr>
        <th colspan="2"></th>  
        <?php if(check_permission("Users","delete_rank")){?>
        	<th><input class="button red" type="submit" name="delrank" value="Delete Ranks" /></th>
        <?php }?>
    </tr>
</table>
</form>
<?php require_once("includes/end_cpanel.php"); ?>