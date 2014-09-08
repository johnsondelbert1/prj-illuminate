<?php
require_once("includes/session.php");
require_once("includes/functions.php");

if(isset($_GET['delpost'])&&$_GET['delpost']!=''){
	
	$query="SELECT * FROM `blog` WHERE id={$_GET['delpost']}";
	$result = mysqli_query( $connection, $query);
	confirm_query($result);
	$galleryid=mysqli_fetch_array($result);
		
	if(check_permission("Blog","delete_blog")||(isset($_SESSION['user_id'])&&$galleryid['poster']==$_SESSION['user_id'])){
		
		$query="DELETE FROM `blog` WHERE `id` = {$_GET['delpost']}";
		$result=mysqli_query($connection, $query);
		confirm_query($result);
		
		//Specify the target directory and add forward slash
		$dir = "blog_galleries/".$galleryid['id']."/gallery/";
		foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') continue;
			unlink($dir.DIRECTORY_SEPARATOR.$item);
		}
		rmdir($dir);
		$dir = "blog_galleries/".$galleryid['id']."/gallery-thumbs/";
		foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') continue;
			unlink($dir.DIRECTORY_SEPARATOR.$item);
		}
		rmdir($dir);
		$dir = "blog_galleries/".$galleryid['id']."/";
		rmdir($dir);
		
		$success="Blog post deleted!";
	}else{
		$error="You do not have permission to delete this post!";
	}
}

$pgsettings = array(
	"title" => "Blog",
	"pageselection" => "blog",
	"nav" => true,
	"banner" => 1,
	"use_google_analytics" => 1,
);
require_once("includes/begin_html.php");
?>

<?php
  	if(check_permission("Blog","post_blog")){?>
		<a class="green" href="new_blog_post.php">New</a><br /><br />
	<?php }

$query="SELECT * FROM `blog` ORDER BY `datecreated` DESC";
$result=mysqli_query( $connection, $query);
$gall_num = 0;
if (mysqli_num_rows($result)!=0){
	while($post=mysqli_fetch_array($result)){
		$query="SELECT * FROM `users` WHERE `id` = ".$post['poster'];
		$userresult=mysqli_query( $connection, $query);
		$userdata=mysqli_fetch_array($userresult);
		$createdtimestamp = strtotime($post['datecreated']);
		$lasteditedtimestamp = strtotime($post['datecreated']);
		?>
		<table width="100%" height="100%" class="blog">
			<tr>
				<td colspan="2">
					<table class="blogtitle" width="100%" height="100%">
						<tr>
							<td width="70%">
								<a href="view_blog_post.php?post=<?php echo $post['id']; ?>" style="color:#333;"><?php echo $post['title']; ?></a>
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
								Posted by: <b><?php echo $userdata['username']; ?></b> at <?php echo date("g:i A", $createdtimestamp);
								if($post['lastedited']!="0000-00-00 00:00:00"){
									$lasteditedtimestamp = strtotime($post['lastedited']);
										echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Edited: ".date("F j, Y, g:i A", $lasteditedtimestamp);
									}?>
							</td>
						</tr>
						<tr>
							<td class="blogbody" valign="top">
								<?php 	$content = $post['content'];
										if (strlen($content) > 1200) {
											// truncate string
											$stringCut = substr($content, 0, 1200);
										
											// make sure it ends in a word so assassinate doesn't become ass...
											$content = substr($stringCut, 0, strrpos($stringCut, ' ')).'... <a href="view_blog_post.php?post='.$post['id'].'">Read More</a>'; 
										}
										echo $content;
								?>
							</td>
						  </tr>
						  <tr>
							<td colspan="2">
								<?php gallery("blog_galleries/".$post['id']."/gallery/", "blog_galleries/".$post['id']."/gallery-thumbs/", 100, 6, $gall_num, 6);
								$gall_num++;?>
							</td>
						</tr>
					</table>
				</td>
				</tr>
                <tr>
                    <td class="blogfooter" colspan="2">
                        <?php if(check_permission("Blog","edit_blog")||(isset($_SESSION['user_id'])&&$post['poster']==$_SESSION['user_id'])){?><a class="bluesmall" href="edit_blog_post.php?post=<?php echo $post['id'] ?>">Edit</a><?php } ?>
                        <?php if(check_permission("Blog","delete_blog")||(isset($_SESSION['user_id'])&&$post['poster']==$_SESSION['user_id'])){?><a class="redsmall" href="blog.php?delpost=<?php echo $post['id'] ?>">Delete</a><?php } ?>
                    </td>
                </tr>
			</tr>
		</table>
		<br />
	<?php }
}else{?>
	<p>There are no blog posts!</p>
<?php }
?>
<?php
require_once("includes/end_html.php");
?>