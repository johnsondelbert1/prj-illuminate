<?php
require_once("includes/session.php");
require_once("includes/functions.php");
$nv="[Not Visible]";
$noval="--";
?>
<?php
	if(isset($_GET['user'])){
		if(isset($_GET['action'])=="ban"){
		$query="SELECT * FROM users
				WHERE username='{$_GET['user']}'";
		$result=mysql_query($query,$connection);
		confirm_query($result);
		$user=mysql_fetch_array($result);
			if(check_permission("Users","delete_users")){
				$query="SELECT `id` FROM `users`
						WHERE username='{$_GET['user']}'";
				$result=mysql_query($query,$connection);
				confirm_query($result);
				$userid=mysql_fetch_array($result);
				redirect_to("user_list.php?msg=banned&user=".ban_acc($_GET['user'], $userid['id']));
			}else{
				$error="You do not have sufficient permissions to ban this user.";
			}
		}
		$query="SELECT * FROM users
				WHERE username='{$_GET['user']}'";
		$result=mysqli_query($connection, $query);
		confirm_query($result);
		if(mysqli_num_rows($result)!=0){
			$user=mysqli_fetch_array($result);
		}else{
			$error="User does not exist or has been deleted!";
		}
		
	}else{
		redirect_to("profile.php?user=".urlencode($user_info['username']));
		//$query="SELECT id, username, email, rank, minecraft_username FROM users
		//		WHERE username='{$_SESSION['username']}'";
	}
?>
<?php
$pgsettings = array(
	"title" => $user['username']."'s Profile",
	"pageselection" => false,
	"nav" => true,
	"banner" => 1,
	"use_google_analytics" => 1,
);
require_once("includes/begin_html.php"); ?>
    <script type="text/javascript">
	function confirmdelete(){
		var ans = confirm("Are you sure you want to ban this account?");
		if (ans==true){
			window.location = "profile.php?user=<?php echo $_GET['user'] ?>&&action=ban";
		}
	}
	</script>

	<h1><?php echo $user['username']."'s Profile"; ?></h1>
    <br />

    <?php if(check_permission("Users","delete_users")){?>
<!-- 		<div style="background-color:#8B8B8B; border-radius:5px; padding:5px;">
			<a href="#" onclick="confirmdelete()">Ban this account</a>
		</div> -->
    <?php }?>
<table width="100%" border="0" cellspacing="5" cellpadding="0" style="background-color:#8B8B8B; border-radius:5px; padding:5px; text-align:center;">
  <tr>
    <td>
		<?php
        if(!empty($user['minecraft_username'])){?>
            <div class="profilepic">
            	<img src="images/minecraft.php?u=<?php echo $user['minecraft_username']; ?>&s=250" alt="Minecraft Skin" />
                <span><img src="images/minecraft.php?u=<?php echo $user['minecraft_username']; ?>&s=128" style="<?php echo echorank($user['rank']) ?>" alt="Minecraft Skin" /></span>
            </div>    
        <?php } ?>
    </td>
  </tr>
  <tr>
    <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="heading">Date Joined:</td>
            <td class="read"><?php echo date("m/d/Y" ,strtotime($user['created'])); ?></td>
          </tr>
          <tr>
            <td class="heading">Email:</td>
            <td class="read"><?php echo $user['email'];?></td>
          </tr>
          
          <tr>
            <td class="heading">Last Logged In:</td>
            <td class="read"><?php if($user['last_logged_in']!="0000-00-00 00:00:00"){echo date("D, m/d/Y h:i A" ,strtotime($user['last_logged_in']));}else{echo "N/A";} ?></td>
          </tr>
        </table>

    </td>
  </tr>
<!--   <tr>
  	<td>
    	<a href="compose_mail.php?user=<?php echo urlencode($user['username'])?>"><img src="images/mail.png" alt"Message <?php echo $user['username']; ?>"/> (Message <?php echo $user['username'];?>)</a>
    </td>
  </tr> -->
  <tr>
    <td colspan="2" align="center">
    <h3>Forum Posts</h3>
    <?php
		$query="SELECT * FROM `forum_posts` WHERE `poster`='{$user['username']}' ORDER BY `date` DESC LIMIT 0,5";
		$messagequery=mysqli_query($connection, $query);
		confirm_query($messagequery);
		if(mysqli_num_rows($messagequery)!=0){?>
        
        <div id="CollapsiblePanel1" class="CollapsiblePanel">
          <div class="CollapsiblePanelTab" tabindex="0">Recent Forum Posts</div>
          <div class="CollapsiblePanelContent">
        
        <table width="100%" border="0" cellspacing="0" cellpadding="0">

        <?php 
		while($forummessage=mysql_fetch_array($messagequery)){
			$query="SELECT * FROM `forum_threads`
					WHERE id={$forummessage['threadid']}";
			$threadquery=mysql_query($query,$connection);
			confirm_query($threadquery);
			$thread=mysql_fetch_array($threadquery);
			$query="SELECT * FROM `forums`
					WHERE id={$forummessage['forumid']}";
			$forumquery=mysql_query($query,$connection);
			confirm_query($forumquery);
			$forum=mysql_fetch_array($forumquery);
			?>
            <tr>
                <td align="center">Thread: <?php echo "<a href=\"view_thread.php?thread=".$thread['id']."&&forum=".$forum['id']."\">".$thread['name']."</a>"; ?><br />Forum: <?php echo "<a href=\"view_forum.php?forum=".$forum['id']."\">".$forum['name']."</a>"; ?></td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="1" cellpadding="5" class="<?php if($user['rank']==0){echo "forumsuser";}elseif($user['rank']==1){echo "forumsadmin";}elseif($user['rank']==2||$user['rank']==3){echo "forumsmod";}elseif($user['rank']==4){echo "forumsvip";}?>">
                      <tr>
                        <td colspan="2" class="forummessage" height="20">Date Posted: <?php echo date("D, d/m/Y h:i A" ,strtotime($forummessage['date']));?> <?php if($forummessage['lasteditdate']!="0000-00-00 00:00:00"){echo ", Last Edited: ".date("D, d/m/Y h:i A" ,strtotime($forummessage['lasteditdate']));} ?></td>
                      </tr>
                      <tr>
                        <td colspan="2" class="forummessage"><?php echo $forummessage['message']; ?></td>
                      </tr>
                    </table>
                </td>
            </tr>
		<?php }?>
        	</table>
        <?php
		}else{
			echo "[No posts have been made]";
		}
	?></td>
  </tr>
</table>
<script type="text/javascript">
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1", {contentIsOpen:false});
</script>
<?php
include("includes/end_html.php");
?>