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

?>
<?php
	$pgsettings = array(
		"title" => "Permissions",
		"icon" => "icon-flag"
	);
	require_once("includes/begin_cpanel.php");
?>
<style>
table.rank-table td{
    border:solid 1px #ccc;
}
table.rank-table{
    width:auto;
}
table.rank-table th.rotate {
  /* Something you can count on */
  height: 140px;
  white-space: nowrap;
}

table.rank-table th.rotate > div {
  -ms-transform: 
    /* Magic Numbers */
    translate(25px, 51px)
    /* 45 is really 360 - 45 */
    rotate(315deg);
  transform: 
    /* Magic Numbers */
    translate(25px, 51px)
    /* 45 is really 360 - 45 */
    rotate(315deg);
  width: 30px;
}

table.rank-table th.rotate > div > span {
  border-bottom: 1px solid #ccc;
  /* padding: 5px 10px; */
}
</style>

<script type="text/javascript">
   <!-- jQuery for "Select All" checkboxes -->
    $(document).ready(function() {
		var $checkall = 'rankall';
        $('input[id="'+$checkall+'"]').change(function() {
			var $all_check_status = $('input[id="'+$checkall+'"]').is(':checked');
             $("#form label").each(function(index, element) {
				var $target = $(this).attr("for");
				if($all_check_status!=$('input[id="'+$target+'"]').is(':checked')){
                	$(this).trigger('click');
				}
            });
        });  
     });
</script>
<?php if(check_permission("Users","create_rank")){?>
    <h1>Create New Rank</h1>
    <form method="post" action="ranks.php">
    <table width="100%" border="0" cellpadding="0" class="form">
      <tr>
        <td style="text-align:center;"><input name="name" placeholder="Name" type="text" value="<?php if(isset($name)){echo $name;} ?>" maxlength="50" /></td>
        <td colspan="2" align="center"><input name="submit" type="submit" value="Create New Rank" class="green btn"/></td>
      </tr>
    </table>
    </form><br />
<?php } ?>
<?php
//Gets rank properties
$query="SELECT * FROM `ranks` ORDER BY `id` ASC";
$result=mysqli_query( $connection, $query);
confirm_query($result);
$rank_count = mysqli_num_rows($result);
?>
<h1>Rank List</h1>
<?php
$ranks_array = array();
while($rank=mysqli_fetch_array($result)){
    array_push($ranks_array, $rank);
}
?>
<form method="post">
<table class="rank-table">
  <thead>
    <tr>
      <!-- First column header is not rotated -->
      <th></th>
      <!-- Following headers are rotated -->
      <?php
      foreach($ranks_array as $value){ ?>
          <th class="rotate"><div><span><a href="edit-rank?rank=<?php echo urlencode($value['id']); ?>"><?php echo $value['name']; ?></a></span></div></th>
        <?php
      }
      ?>
    </tr> 
  </thead>
  <tbody>
    <tr>
      <th>Color</th>
      <?php 
      foreach($ranks_array as $value){ ?>
      <td style="background-color:<?php echo $value['color']; ?>;"></td>
    <?php
      }
      ?>
    </tr>
    <?php
    foreach ($blank_permissions as $permgroup_name => $permgroup_perms) {?>
    <tr>
      <th></th>
      <th colspan="<?php echo $rank_count; ?>"><?php echo $permgroup_name; ?></th>
    </tr>
    <?php
        foreach ($permgroup_perms as $perm_name => $perm_data) {?>
    <tr>
      <th><?php echo $perm_data['disp_name']; ?></th>
        <?php
        foreach ($ranks_array as $value) {
            $rank_permission = check_rank_permission($value['id'], $permgroup_name, $perm_name);
            if($rank_permission === true){ ?>
        <td><i class='material-icons green-text'>&#xE061;</i></td>
        <?php }else{ ?>
        <td><i class='material-icons red-text'>&#xE061;</i></td>
        <?php
            }
        }
        ?>
      </tr>
        <?php    
        }
        ?>
    
        <?php
    }
    ?>
    <tr>
      <th>Delete</th>
      <?php
      foreach($ranks_array as $value){ ?>
          <th><input type="checkbox" name="ranks[]"<?php if($value['deletable']==0){echo ' disabled';} ?> value="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" /><label for="<?php echo $value['id']; ?>"></label></th>
        <?php
      }
      ?>
    </tr>
    <tr>
      <th></th>
      <th colspan="<?php echo $rank_count; ?>"><a class="modal-trigger red btn" href="#modal1">Delete Ranks</a></th>
    </tr>
  </tbody>
</table>
<div id="modal1" class="modal">
<div class="modal-content">
      <h4>Are you sure you want to delete these ranks?</h4>
      <p>Once you delete, there will be no way to recover it!</p>
    </div>
    <div class="modal-footer">
    <div class="row right">
    <div class="col l12 s12">
    <a href="#!" class="modal-close waves-effect waves-blue btn blue ">Cancel</a>
      <input class="red btn" type="submit" name="delrank" value="Delete Selected Ranks" />
      </div>
      </div>
    </div>
</div>
</form>
<!-- <div style="overflow-x:auto;">
    <form method="post" action="ranks.php">
    <table border="1" cellspacing="0" cellpadding="0" id="form" style="min-height:100px; background-color:#DDDDDD; width:100%;">
        <tr>
            <td style="min-width:150px;">
                <table width="100%" border="1" cellspacing="0" cellpadding="0">
                    <tr height="20">
                        <th>
                            Permission Group
                        </th>
                    </tr>
                    <tr height="88">
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
            <td width="100%">
            	<div style="width:inherit; height:100%; overflow-x:auto; position:relative;">
                    <table border="1" cellspacing="0" cellpadding="2">
                      <tr height="20">
                      <?php
                        foreach($blank_permissions as $permisson_group => $single_permissions){?>
                            <th colspan="<?php echo count($single_permissions); ?>"><?php echo $permisson_group; ?></th>
                        <?php
                        }
                        ?>
                      </tr>
                      <tr height="88">
                      
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
            <td>
                <table width="100%" border="1" cellspacing="0" cellpadding="0">
                    <tr height="109">
                        <th>
                            Color
                        </th>
                        <?php if(check_permission("Users","delete_rank")){?>
                            <th>
                                Delete<br>
								<input type="checkbox" id="rankall" /><label for="rankall"></label>
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
                                <td><input type="checkbox" name="ranks[]"<?php if($rank['deletable']==0){echo ' disabled';} ?> value="<?php echo $rank['id']; ?>" id="<?php echo $rank['id']; ?>" /><label for="<?php echo $rank['id']; ?>"></label></td>
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
                <th><input class="red btn" type="submit" name="delrank" value="Delete Ranks" /></th>
            <?php }?>
        </tr>
    </table>
    </form>
</div> -->
<?php require_once("includes/end_cpanel.php"); ?>