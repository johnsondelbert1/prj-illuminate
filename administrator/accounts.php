<?php
require_once("../includes/functions.php");
if(!check_permission(array("Users;add_users","Users;delete_users",))){
	redirect_to("index.php");
}
$query="SELECT * FROM `ranks`";
$rankresult=mysqli_query($connection, $query);

if(isset($_POST['submit'])){
	if(check_permission("Users","add_users")){
		$user=strip_tags(trim(mysql_prep($_POST['newuser']), " \t\n\r\0\x0B"));
		$email=strip_tags(trim(mysql_prep($_POST['newemail']), " \t\n\r\0\x0B"));
		$pass=strip_tags(mysql_prep($_POST['newpass']));
		$confpass=strip_tags(mysql_prep($_POST['newconfpass']));
		$hashed_pass=sha1($pass);
		
		if ($user!="" && $email!="" && $pass!=""){
			if($pass==$confpass){
				if(filter_var($email,FILTER_VALIDATE_EMAIL)){
					$query="SELECT username FROM users
										WHERE username='{$user}'";
					$result=mysqli_query($connection, $query);
					confirm_query($result);
					if (mysqli_num_rows($result)==0){
						$date=date("Y/m/d H:i:s", time());
						
						$query="INSERT INTO `users` (
											username, created, email, hashed_pass, rank
										) VALUES (
											'{$user}', '{$date}', '{$email}', '{$hashed_pass}', {$_POST['rank']})";
								$result=mysqli_query( $connection, $query);
								$success="Account created!";
					}else{
						$error="An account with this username already exists!";
					}
				}else{
					$error="Email is not valid.";
				}
			}else{
				$error="Password does not match Confirm Password.";
			}
		}else{
			$error="Field is left blank.";
		}
	}else{
		$error="You do not have permission to add users!";
	}
}

if(isset($_POST['deluser'])){
	if(check_permission("Users","delete_users")){
		$message="";
		if(!empty($_POST['accounts'])){
			foreach($_POST['accounts'] as $account){
				if(del_acc($account)==true){
					$success="Accounts were deleted!";
				}else{
					$error="Cannot delete only webmaster account left.";
				}
			}
		}else{
			$error="No accounts selected.";
		}
	}else{
		$error="You do not have permission to delete users!";
	}
}

if(isset($_POST['upd_ranks'])){
	if(check_permission("Users","add_users")){
		$message="";
		if(!empty($_POST['acc_ranks'])){
			foreach($_POST['acc_ranks'] as $account => $rank){
				$query="SELECT * FROM `users` WHERE `id` = {$account}";
				$result=mysqli_query( $connection, $query);
				$deletable_user = mysqli_fetch_array($result);
				if($deletable_user['deletable']==true){
					$query="UPDATE `users` SET
								`rank` = {$rank} WHERE `id` = {$account}";
					$result=mysqli_query( $connection, $query);
					confirm_query($result);
					$success="Account ranks updated!";
				}else{
					if($deletable_user['rank']!=$rank){
						$error = 'Account "'.$deletable_user['username'].'" in the list cannot be changed!';
					}
				}
			}
		}else{
			$error="No accounts selected.";
		}
	}else{
		$error="You do not have permission to delete users!";
	}
}
?>
<?php
	$pgsettings = array(
		"title" => "Accounts",
		"icon" => "icon-user3"
	);
	require_once("includes/begin_cpanel.php");
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript">
   <!-- jQuery for "Select All" checkboxes -->
    $(document).ready(function() {
		var $checkall = 'accountall';
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
<?php if(check_permission("Users","add_users")){?>
    <h1>Add New Account</h1>
    <form method="post" action="accounts.php" class="col s12">
      <div class="row">
        <div class="input-field col s6"><input name="newuser" placeholder="Username" type="text" value="<?php if(isset($user)){echo $user;} ?>" maxlength="50" /></div>
        <div class="input-field col s6"><input name="newemail" placeholder="email@example.com" type="email" value="<?php if(isset($email)){echo $email;} ?>"maxlength="200" /></div>
        </div>
        <div class="row">
        <div class="input-field col s6"><input name="newpass" placeholder="Password" type="password" maxlength="50" /></div>
        <div class="input-field col s6"><input name="newconfpass" placeholder="Confirm" type="password" maxlength="50" /></div>
        </div>
           <div class="row">
        <div class="input-field col s6"> <select name="rank">
                <?php
                while($rank=mysqli_fetch_array($rankresult)){?>
                        <option value="<?php echo $rank['id'];?>"><?php echo $rank['name']?></option>
                <?php
                }?>
            </select></div>
<div class="input-field col s6"><input name="submit" type="submit" value="Create Account" class="green btn"/></div></div>
    </form><br />
<?php } ?>
<h1>Account List</h1>
<form method="post" action="accounts.php">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="form" style="text-align:center;">
      <tr>
        <th width="10%">User Name:</th>
        <th width="30%">Email:</th>
        <th width="20%">Created:</th>
        <th width="10%">Rank:</th>
        <th width="20%">Last Logged In:</th>
        <?php if(check_permission("Users","delete_users")){?>
        	<th width="10%"><input type="checkbox" id="accountall" /><label name="accountall" for="accountall"></label></th>
        <?php } ?>
      </tr>
        <?php
            $query="SELECT * FROM `users` ORDER BY `id` ASC";
            $result=mysqli_query( $connection, $query);
            confirm_query($result);
			
			$ranks = array();
			$query="SELECT * FROM `ranks`";
			$rankresult=mysqli_query($connection, $query);
			while($rank=mysqli_fetch_array($rankresult)){
				 array_push($ranks, $rank);
			}
			
            while($user=mysqli_fetch_array($result)){

				?>
              <tr>
                <td><?php echo $user['username'];?></td>
                <td><?php echo $user['email'];?></td>
                <td><?php if($user['created']!="0000-00-00 00:00:00"){echo date("m/d/Y h:i A" ,strtotime($user['created']));}else{echo "N/A";} ?></td>
                <td>
                	<select name="acc_ranks[<?php echo $user['id'];?>]">
                    	<?php
						foreach($ranks as $rank){?>
							<option value="<?php echo $rank['id']; ?>"<?php if($user['rank'] == $rank['id']){echo " selected";} ?>><?php echo $rank['name']; ?></option>
                        <?php
						}
						?>
                    </select>
                </td>
                <td><?php if($user['last_logged_in']!="0000-00-00 00:00:00"){echo date("D, m/d/Y h:i A" ,strtotime($user['last_logged_in']));}else{echo "N/A";} ?></td>
                <?php if(check_permission("Users","delete_users")){?>
                	<td><input type="checkbox" name="accounts[]"<?php if($user['deletable']==0){echo ' disabled';} ?> id="<?php echo $user['id']; ?>" /><label for="<?php echo $user['id']; ?>"></td>
                <?php } ?>
              </tr>
            <?php } ?>
      <tr>
        <th colspan="3"></th>
        <th><input class="blue btn" type="submit" name="upd_ranks" value="Change Ranks" /></th>
        <th></th>
        <?php if(check_permission("Users","delete_users")){?>
        	<th width="10%"><input class="red btn" type="submit" name="deluser" value="Delete Accounts" /></th>
        <?php } ?>
      </tr>
    </table>
</form>
<?php require_once("includes/end_cpanel.php"); ?>