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
						
						$query="INSERT INTO users (
											username, created, email, hashed_pass, rank
										) VALUES (
											'{$user}', ,'{$date}', '{$email}', '{$hashed_pass}', {$_POST['rank']})";
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
}elseif(isset($_POST['deluser'])){
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
   <!-- jQuery for seven sets of "Select All" checkboxes -->
    $(document).ready(function() {
             $('input[id="accountall"]').click(function() {
             $("#acc :checkbox").attr('checked', $(this).attr('checked'));
        });  
     });
</script>
<?php if(check_permission("Users","add_users")){?>
    <h2>Add New Account</h2>
    
    <br />
    <form method="post" action="accounts.php">
    <table width="100%" border="0" cellpadding="0" class="form" style="text-align:center;">
        <tr>
        <th scope="col">Username</th>
        <th scope="col">Email</th>
        <th scope="col">Password</th>
        <th scope="col">Confirm</th>
        <th scope="col">Rank</th>
      </tr>
      <tr>
        <td><input name="newuser" placeholder="Username" type="text" value="<?php if(isset($user)){echo $user;} ?>" maxlength="50" /></td>
        <td><input name="newemail" placeholder="email@example.com" type="email" value="<?php if(isset($email)){echo $email;} ?>"maxlength="200" /></td>
        <td><input name="newpass" placeholder="Password" type="password" maxlength="50" /></td>
        <td><input name="newconfpass" placeholder="Confirm" type="password" maxlength="50" /></td>
        <td>
            <select name="rank">
                <?php
                while($rank=mysqli_fetch_array($rankresult)){?>
                        <option value="<?php echo $rank['id'];?>"><?php echo $rank['name']?></option>
                <?php
                }?>
            </select>
        </td>
        <td align="center"><input name="submit" type="submit" value="Create Account" class="submit"/></td>
      </tr>
    </table>
    </form><br />
<?php } ?>
<h2>Account List</h2>
<br />
<form method="post" action="accounts.php">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="acc" style="text-align:center;">
      <tr>
        <th width="10%">User Name:</th>
        <th width="30%">Email:</th>
        <th width="20%">Created:</th>
        <th width="10%">Rank:</th>
        <th width="20%">Last Logged In:</th>
        <?php if(check_permission("Users","delete_users")){?>
        	<th width="10%">Delete <input type="checkbox" id="accountall"></th>
        <?php } ?>
      </tr>
        <?php
            $query="SELECT * FROM `users` ORDER BY `id` ASC";
            $result=mysqli_query( $connection, $query);
            confirm_query($result);
            while($user=mysqli_fetch_array($result)){
				$query="SELECT * FROM `ranks` WHERE id={$user['rank']}";
				$rankresult=mysqli_query($connection, $query);
				$rank=mysqli_fetch_array($rankresult);
				?>
              <tr>
                <td><?php echo $user['username'];?></td>
                <td><?php echo $user['email'];?></td>
                <td><?php if($user['created']!="0000-00-00 00:00:00"){echo date("m/d/Y h:i A" ,strtotime($user['created']));}else{echo "N/A";} ?></td>
                <td><?php echo $rank['name'];?></td>
                <td><?php if($user['last_logged_in']!="0000-00-00 00:00:00"){echo date("D, m/d/Y h:i A" ,strtotime($user['last_logged_in']));}else{echo "N/A";} ?></td>
                <?php if(check_permission("Users","delete_users")){?>
                	<td><?php if($user['deletable']==1){ ?><input type="checkbox" name="accounts[]" value="<?php echo $user['id']; ?>" /><?php } ?></td>
                <?php } ?>
              </tr>
            <?php } ?>
      <tr>
        <th colspan="5"></th> 
        <?php if(check_permission("Users","delete_users")){?>
        	<th width="10%"><input class="red" type="submit" name="deluser" value="Delete Accounts" /></th>
        <?php } ?>
      </tr>
    </table>
</form>
<?php require_once("includes/end_cpanel.php"); ?>