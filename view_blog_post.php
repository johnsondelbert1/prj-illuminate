<?php
require_once("includes/session.php");
require_once("includes/functions.php");

if(isset($_GET['post'])&&$_GET['post']!=''){
	$query="SELECT * FROM `blog` WHERE `id` = {$_GET['post']}";
	$result=mysqli_query( $connection, $query);
	$post=mysqli_fetch_array($result);
}else{
	redirect_to("blog.php");
}

$query="SELECT * FROM `pages` WHERE `type` = 'Blog' ";
$result_page_prop=mysqli_query( $connection, $query);
$page_properties = mysqli_fetch_array($result_page_prop);

$pgsettings = array(
	"title" => $post['title'],
	"pageselection" => "blog",
	"nav" => $page_properties['horiz_menu_visible'],
	"banner" => $page_properties['banner'],
	"slider" => $page_properties['slider'],
	"use_google_analytics" => 1,
);
require_once("includes/begin_html.php");
?>
<h1>View Blog Post</h1>
<?php

	$query="SELECT * FROM `users` WHERE `id` = ".$post['poster'];
	$userresult=mysqli_query( $connection, $query);
	$userdata=mysqli_fetch_array($userresult);
	$createdtimestamp = strtotime($post['datecreated']);
	$lasteditedtimestamp = strtotime($post['datecreated']);
	?>
    <table width="200" border="0">
  <tr>
    <td><?php if(check_permission("Blog","edit_blog")||(isset($_SESSION['user_id'])&&$post['poster']==$_SESSION['user_id'])){?><a class="red" href="blog.php?delpost=<?php echo $post['id'] ?>">Delete</a><?php } ?></td>
    <td><a class="blue" href="blog.php">Back</a></td>
  </tr>
</table>
<table width="100%" height="100%" class="blog">
    <tr>
        <td colspan="2">
            <table class="blogtitle" width="100%" height="100%">
                <tr>
                    <td width="70%">
                        <?php echo $post['title']; ?>
                    </td>
                    <td>
                        <?php echo date("F j, Y", $createdtimestamp);?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="80%" valign="top">
            <table width="100%" height="100%">
                <tr>
                    <td class="blogname" height="30">
                        Posted by: <a href="profile.php?user=<?php echo urlencode($userdata['username']); ?>"><?php echo $userdata['username']; ?></a> at <?php echo date("g:i A", $createdtimestamp);
                        if($post['lastedited']!="0000-00-00 00:00:00"){
                            $lasteditedtimestamp = strtotime($post['lastedited']);
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edited: ".date("F j, Y, g:i A", $lasteditedtimestamp);
                            }?>
                    </td>
                </tr>
                <tr>
                    <td class="blogbody" valign="top">
                        <?php 	echo $post['content'];?>
                    </td>
                </tr>
                      <tr>
                        <td colspan="2">
                        	<?php gallery("blog_galleries/".$post['id']."/gallery/", "blog_galleries/".$post['id']."/gallery-thumbs/", 100, 100);?>
                        </td>
                    </tr>
            </table>
        </td>
        </tr>
        <tr>
        	<td>
				<?php if(check_permission("Blog","edit_blog")||(isset($_SESSION['user_id'])&&$post['poster']==$_SESSION['user_id'])){?><a class="blue small" href="edit_blog_post.php?post=<?php echo $post['id'] ?>">Edit</a><?php } ?>
            </td>
        </tr>
    </tr>
</table>
<?php
	require_once("includes/end_html.php");
?>