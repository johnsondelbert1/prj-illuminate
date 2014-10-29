<?php
require_once("includes/session.php");
require_once("includes/functions.php");

?>
<?php
	if(isset($_POST['submit'])){
		$query="INSERT INTO forums (
					name, description 
				) VALUES (
					'{$_POST['forumname']}', '{$_POST['forumdesc']}')";
		$result=mysqli_query( $connection, $query);
		$success="Forum created!";
		$forumname="";
		$forumdesc="";
	}elseif(isset($_POST['edit'])){
		$query="UPDATE `forums` SET `name` = '{$_POST['forumname']}', `description`='{$_POST['forumdesc']}' WHERE `id` ={$_GET['forumid']}";
		$result=mysqli_query($connection, $query);
		$success="Forum successfully edited!";
		$forumname="";
		$forumdesc="";
		confirm_query($result);
	}elseif(isset($_GET['action'])&&isset($_GET['forumid'])){
			if(check_permission("Forum","add_delete_forum")){
				
				if($_GET['action']=="editforum"){
					$query="SELECT name, description FROM forums WHERE id={$_GET['forumid']}";
					$result=mysqli_query( $connection, $query);
					$forumedit=mysqli_fetch_array($result);
					$forumname=strip_tags($forumedit['name']);
					$forumdesc=strip_tags($forumedit['description']);
				}elseif($_GET['action']=="delforum"){
					$query="DELETE FROM `forums` WHERE `id` = {$_GET['forumid']}";
					$result=mysqli_query($connection, $query);
					confirm_query($result);
					$query="DELETE FROM `forum_threads` WHERE `forumid` = {$_GET['forumid']}";
					$result=mysqli_query($connection, $query);
					confirm_query($result);
					$query="DELETE FROM `forum_posts` WHERE `forumid` = {$_GET['forumid']}";
					$result=mysqli_query($connection, $query);
					confirm_query($result);
					$success="Forum deleted!";
					$forumname="";
					$forumdesc="";
				}
			}else{
				$error="You do not have permission to perform this action!";
			}
	}else{
		$forumname="";
		$forumdesc="";
	}
?>
<?php
$query="SELECT * FROM `pages` WHERE `type` = 'Forum'";
$result_page_prop=mysqli_query( $connection, $query);
$page_properties = mysqli_fetch_array($result_page_prop);

$pgsettings = array(
	"title" => $site_info['name']." Forums",
	"pageselection" => "forum",
	"nav" => $page_properties['horiz_menu_visible'],
	"banner" => $page_properties['banner'],
	"use_google_analytics" => 1,
);
require_once("includes/begin_html.php");
?>
<h1>Welcome to the Forums!</h1>
  <?php
	if((isset($_GET['action'])&&$_GET['action']=="editforum")&&isset($_GET['forumid'])&&check_permission("Forum","edit_forum")){?>
		<form action="forums.php?forumid=<?php echo urlencode($_GET['forumid']) ?>" method="post">
        <?php if(isset($_GET['action'])&&$_GET['action']=="editforum"){
			$query="SELECT name, description FROM forums WHERE id={$_GET['forumid']}";
			$result=mysqli_query( $connection, $query);
			$editing_forum=mysqli_fetch_array($result);
		}?>
		  <table class="accent" style="margin-right:auto; margin-left:auto;"width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<th scope="col" style="background:#9E9E9E;" colspan="2" class="heading">
					Editing Forum: "<?php echo $editing_forum['name']; ?>"
                </th>
			</tr>
			  <tr style="padding:5px;" >
				<td align="center" style="padding:10px; font-size:18px;">
                    Forum Name: <input type="text" name="forumname" maxlength="50" value="<?php echo $editing_forum['name']; ?>"/>
                    Description: <input type="text" name="forumdesc" value="<?php echo $editing_forum['description']; ?>"/>
                    <input type="submit" name="edit" value="Save Edit" />
                </td>
			</tr>
		  </table>
		</form>
	<?php }
  ?>
  <?php
	if(check_permission("Forum","add_delete_forum")){?>
		<form method="post">
		  <table class="accent" style="margin-right:auto; margin-left:auto;"width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<th scope="col" style="background:#9E9E9E;" colspan="2" class="heading">
					Create New Forum
                </th>
			</tr>
			  <tr style="padding:5px;" >
				<td align="center" style="padding:10px; font-size:18px;">
                    Forum Name: <input type="text" name="forumname" maxlength="50" />
                    Description: <input type="text" name="forumdesc" />
                    <input type="submit" name="submit" value="Create New Forum" />
                </td>
			</tr>
		  </table>
		</form>
	<?php }
  ?>
<table class="forum" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <th class="forumtitle" colspan="3">Forum</th>
    <?php if(check_permission(array("Forum;add_delete_forum","Forum;edit_forum",))){?>
    	<th class="forumtitle" colspan="2">Controls</th>
    <?php } ?>
  </tr>
  <?php
	
    $query="SELECT * 
		FROM  `forums` 
		ORDER BY  `id` ASC";
			
	$result=mysqli_query($connection, $query);
	confirm_query($result);
	if(mysqli_num_rows($result)!=0){
		while($forum=mysqli_fetch_array($result)){
			
				$query="SELECT id, forumid FROM  `forum_threads` 
					WHERE forumid={$forum['id']}";	
				$threadquery=mysqli_query($connection, $query);
				confirm_query($threadquery);
				$threadcount=mysqli_num_rows($threadquery);
				$query="SELECT id, date FROM `forum_posts` 
					WHERE forumid={$forum['id']}
					ORDER BY `date` ASC";
				$messagequery=mysqli_query($connection, $query);
				confirm_query($messagequery);
				$messagedate=mysqli_fetch_array($messagequery);
				$messagecount=mysqli_num_rows($messagequery);?>
                <tr>
                	<td><a href="view_forum.php?forum=<?php echo urlencode($forum['id']);?>"><?php echo $forum['name'];?></a><br /><?php echo $forum['description'];?></td>
                    <td><b><?php echo $threadcount; ?></b> Topics<br />
                    <b><?php echo $messagecount; ?></b> Replies</td>
                    <td><?php if($threadcount != 0){echo date("m/d/Y h:i A" ,strtotime($messagedate['date']));}else{echo "N/A";} ?></td>
                    <?php if(check_permission(array("Forum;add_delete_forum","Forum;edit_forum",))){?>
                    <td style="text-align:center;">
						<?php if(check_permission("Forum","edit_forum")){?><a class="blue" href="forums.php?action=editforum&&forumid=<?php echo urlencode($forum['id']);?>">Edit</a><?php } ?>
                    	<?php if(check_permission("Forum","add_delete_forum")){?><a class="red" href="forums.php?action=delforum&&forumid=<?php echo urlencode($forum['id']);?>">Delete</a><?php } ?>
                    </td>
                    <?php } ?>
                </tr>
				<?php
		}
	}else{?>
		<tr><td colspan="5" align="center">No forums found!</td></tr>
	<?php 
	}
  ?>
</table>

<?php
	require_once("includes/end_html.php");
?>