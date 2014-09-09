<?php
require_once("includes/session.php");
require_once("includes/functions.php");

?>
<?php
	function style($admin,$username){
		if($admin==1){echo "class=\"forumsadmin";}if(logged_in()&&$username==$_SESSION['username']){echo " userspost";}echo "\"";
	}
	
	$query="SELECT * 
		FROM  `forum_threads` 
		WHERE  `id`={$_GET['thread']}";
			
	$threadquery=mysqli_query($connection, $query);
	confirm_query($threadquery);
	$thread=mysqli_fetch_array($threadquery);
	
	$query="SELECT * 
		FROM  `forums` 
		WHERE  `id`={$thread['forumid']}";
			
	$forumquery=mysqli_query($connection, $query);
	confirm_query($forumquery);
	$forum=mysqli_fetch_array($forumquery);
	
    $query="SELECT * 
		FROM  `forum_posts` 
		WHERE  `threadid`={$_GET['thread']}";
			
	$result=mysqli_query($connection, $query);
	confirm_query($result);
	$num_posts = mysqli_num_rows($result);
	
	$num_pages = ceil($num_posts/10);
	
	if(isset($_GET['page'])&&$_GET['page']>=1){
		$current_page = $_GET['page'];
	}else{
		$current_page = 1;
	}
	
	$query="SELECT * FROM `forum_posts` WHERE  `threadid`={$_GET['thread']} ORDER BY `date` asc LIMIT ";
		$query.=(($current_page * 10)-10).",".($current_page * 10);
	$result=mysqli_query( $connection, $query);
	
	$query="UPDATE `forum_threads` SET `views` = `views` + 1 WHERE `id` ={$_GET['thread']}";
			
	$threadquery=mysqli_query($connection, $query);
	confirm_query($threadquery);
	?>
<?php
$pgsettings = array(
	"title" => $thread['name'],
	"pageselection" => "forum",
	"nav" => true,
	"banner" => 1,
	"use_google_analytics" => 1,
);
require_once("includes/begin_html.php");
?>
<h1><?php echo $thread['name']; ?></h1><br>
<a style="text-decoration:none;" href="forums.php"><?php echo $site_info['name']; ?> Forums</a> &gt; <a style="text-decoration:none;" href="view_forum.php?forum=<?php echo $forum['id']; ?>"><?php echo $forum['name']; ?></a> &gt; <a style="text-decoration:none;" href="view_thread.php?thread=<?php echo $thread['id']; ?>"><?php echo $thread['name']; ?></a><br><br>
<?php if(check_permission("Forum","reply_to_thread")&&$thread['locked']==0){?>
	<a class="green" href="new_topic.php?forum=<?php echo urlencode($forum['id'])."&&thread=".urlencode($thread['id']); ?>&amp;&amp;action=newmessage">Reply</a>
<?php } 
    echo "<p>Page ".$current_page." of ".$num_pages."</p>";
	
	if($current_page>1){ ?>
    	<a href="view_thread.php?thread=<?php echo $_GET['thread']; ?>&page=<?php echo $current_page - 1; ?>">&#60; Prev</a>
    <?php } ?>
     | 
	<?php if($num_pages>1&&$current_page<$num_pages){ ?>
    	<a href="view_thread.php?thread=<?php echo $_GET['thread']; ?>&page=<?php echo $current_page + 1; ?>">Next &#62;</a>
    <?php } ?>
  <?php
  	$count=1;
	while($forummessage=mysqli_fetch_array($result)){
		$query="SELECT * 
			FROM  `users` 
			WHERE username='{$forummessage['poster']}'";
				
		$userquery=mysqli_query($connection, $query);
		confirm_query($userquery);
		$user=mysqli_fetch_array($userquery);
		
		?>
        
        <table width="100%" cellspacing="1" cellpadding="0" style="margin-bottom:10px;">
          <tr>
            <td>
            	<div style="min-height:150px;" class="forumbody">
                    <p class="postcount" align="right"><b># <?php echo $count; ?></b></p>Posted by: <b><?php echo $forummessage['poster']; ?></b><br /><?php echo $forummessage['message']; ?></div>
                    
                    <div class="forumfooter">Posted: <?php echo date("m/d/Y h:i A" ,strtotime($forummessage['date']));?><?php if($forummessage['lasteditdate']!="0000-00-00 00:00:00"){echo ", Last Edit: ".date("m/d/Y h:i A" ,strtotime($forummessage['lasteditdate']));} ?>
                    <?php
                        if(check_permission("Forum","edit_thread")&&$user['username']==$_SESSION['username']&&($thread['locked']==0)){?><br><a class="bluesmall" href="new_topic.php?msg=<?php echo $forummessage['id']; ?>&amp;&amp;forum=<?php echo $forum['id']; ?>&amp;&amp;thread=<?php echo $thread['id']; ?>&amp;&amp;action=editpost">Edit</a>
                    <?php } ?>
             	</div>
             </td>
          </tr>
        </table>
        
	<?php
	$count++;
    }
  ?>
<?php if(check_permission("Forum","reply_to_thread")&&$thread['locked']==0){?>
	<a class="green" href="new_topic.php?forum=<?php echo urlencode($forum['id'])."&&thread=".urlencode($thread['id']); ?>&amp;&amp;action=newmessage">Reply</a><br><br>
<?php } ?>
<a style="text-decoration:none;" href="forums.php"><?php echo $site_info['name']; ?> Forums</a> &gt; <a style="text-decoration:none;" href="view_forum.php?forum=<?php echo $forum['id']; ?>"><?php echo $forum['name']; ?></a> &gt; <a style="text-decoration:none;" href="view_thread.php?thread=<?php echo $thread['id']; ?>"><?php echo $thread['name']; ?></a><br /><br />
<?php
	require_once("includes/end_html.php");
?>