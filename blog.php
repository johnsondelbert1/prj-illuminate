<?php
require_once("includes/session.php");
require_once("includes/functions.php");

if(isset($_GET['delpost'])&&$_GET['delpost']!=''){
	
	if(check_permission("Blog","delete_blog")||(isset($_SESSION['user_id'])&&$galleryid['poster']==$_SESSION['user_id'])){
		$query="SELECT * FROM `blog` WHERE id={$_GET['delpost']}";
		$result = mysqli_query( $connection, $query);
		confirm_query($result);
		if(mysqli_num_rows($result)!=0){
			$galleryid=mysqli_fetch_array($result);
			
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
			$error="Blog post does not exist!";
		}
	}else{
		$error="You do not have permission to delete this post!";
	}
}
if(isset($_GET['page'])&&$_GET['page']<=0){
	redirect_to($GLOBALS['HOST']."/blog?page=1");
}

$query="SELECT * FROM `blog`";
$result=mysqli_query( $connection, $query);
$num_posts = mysqli_num_rows($result);

$num_pages = ceil($num_posts/10);

if(isset($_GET['page'])&&$_GET['page']>=1){
	$current_page = $_GET['page'];
}else{
	$current_page = 1;
}

$query="SELECT * FROM `blog` ORDER BY `datecreated` DESC LIMIT ";
	$query.=(($current_page * 10)-10).",".($current_page * 10);
$result=mysqli_query( $connection, $query);

$query="SELECT * FROM `pages` WHERE `type` = 'Blog'";
$result_page_prop=mysqli_query( $connection, $query);
$page_properties = mysqli_fetch_array($result_page_prop);

$pgsettings = array(
	"title" => $page_properties['name'],
	"pageselection" => "blog",
	"nav" => $page_properties['horiz_menu_visible'],
	"banner" => $page_properties['banner'],
	"slider" => $page_properties['slider'],
	"use_google_analytics" => 1,
);
require_once("includes/begin_html.php");?>

<script type="text/javascript">
$(document).ready(function () {
	$(".btn-click-action").click(function(){
		$("#del_button").attr("href", "blog.php?delpost="+$(this).attr('name'));
	});
});
</script>

<?php
if (mysqli_num_rows($result)!=0){
  	if(check_permission("Blog","post_blog")){?>
		<a class="btn green" href="new_blog_post.php">New</a>
    
	<?php }?>
	</div>
    <div class="row">
    
    <?php
	echo_page($num_pages, $current_page, "blog.php?");?>
    </div>
    
    <?php
    $gall_num = 0;
	while($post=mysqli_fetch_array($result)){
		$query="SELECT * FROM `users` WHERE `id` = ".$post['poster'];
		$userresult=mysqli_query( $connection, $query);
		$userdata=mysqli_fetch_array($userresult);
		$createdtimestamp = strtotime($post['datecreated']);
		$lasteditedtimestamp = strtotime($post['datecreated']);
		if(!file_exists("blog_galleries/".$post['id'])){
			mkdir("blog_galleries/".$post['id']);
		}
		if(!file_exists("blog_galleries/".$post['id']."/gallery/")){
			mkdir("blog_galleries/".$post['id']."/gallery/");
		}
		if(!file_exists("blog_galleries/".$post['id']."/gallery-thumbs/")){
			mkdir("blog_galleries/".$post['id']."/gallery-thumbs/");
		}
		?>
		<table width="100%" height="100%" class="blog card">
			<tr>
				<td colspan="2">
					<div class="blogtitle" width="100%" height="100%">
					
								<h5><a href="view_blog_post.php?post=<?php echo $post['id']; ?>" style="color:#333;"><?php echo $post['title']; ?></a></h5>
							<div class="container">
                    <div class="row right blog-btn">
                    <div class="col l12 s12">
                        <?php if(check_permission("Blog","edit_blog")||(isset($_SESSION['user_id'])&&$post['poster']==$_SESSION['user_id'])){?><a class="btn-floating blue" href="edit_blog_post.php?post=<?php echo $post['id'] ?>"><i class="mdi-editor-mode-edit"></i></a><?php } ?>
                        <?php if(check_permission("Blog","delete_blog")||(isset($_SESSION['user_id'])&&$post['poster']==$_SESSION['user_id'])){?>
                        <a class="modal-trigger btn-floating red btn-click-action" href="#modal1" name="<?php echo $post['id'] ?>"><i class="mdi-action-delete"></i></a><?php } ?>
                        </div>
                    </div>
                    </div>
					</div>
				</td>
			</tr>
			<tr>
				<td width="80%" valign="top">
					<table width="100%" height="100%">
						<tr>
							<div class="blogname">
                            <div class="row">
								
                                <div class="col l2 s4">
                                 <?php
								if($post['lastedited']!="0000-00-00 00:00:00"){
									$lasteditedtimestamp = strtotime($post['lastedited']);
										echo '<i class="mdi-editor-mode-edit"></i> '.date("g:i A", $lasteditedtimestamp);
									}
									else{
									echo '<i class="mdi-device-access-time"></i> ' .date("M jS 'y", $createdtimestamp);	
									}
									?> 
                                    </div>
                                    <div class="col l2 s4">
                               <?php
								if($post['lastedited']!="0000-00-00 00:00:00"){
									$lasteditedtimestamp = strtotime($post['lastedited']);
										echo '<i class="mdi-editor-mode-edit"></i> '.date("M jS 'y", $lasteditedtimestamp);
										
									}
									else{
									echo '<i class="mdi-action-today"></i> ' .date("M jS 'y", $createdtimestamp);	
									}
										?> </div>
                                    <div class="col s4">
                                    <i class="mdi-action-face-unlock"></i> &nbsp;<b><?php echo $userdata['username']; ?></b>
                                    </div>
							</div>
                            </div>
						</tr>
						<tr>
							<td class="blogbody" valign="top">
								<?php 	$content = $post['content'];
										if (strlen($content) > 1200) {
											// truncate string
											$stringCut = substr($content, 0, 600);
										
											// make sure it ends in a word so assassinate doesn't become ass...
											$content = substr($stringCut, 0, strrpos($stringCut, ' ')).'... <a class="waves-effect waves-blue btn-flat" href="view_blog_post.php?post='.$post['id'].'">Read More</a>'; 
										}
										echo $content;
								?>
							</td>
						  </tr>
						  <tr>
							<td colspan="2">
								<?php
								$i = 0;
								$files = glob("blog_galleries/".$post['id']."/gallery/" . "*");
								if ($files){
									$i = count($files);
								}
								if($i>0){gallery("blog_galleries/".$post['id']."/gallery/", "blog_galleries/".$post['id']."/gallery-thumbs/", 100, 100, $gall_num, 6);}
								$gall_num++;?>
							</td>
						</tr>
					</table>
				</td>
				</tr>
                <tr>
                    <td>
                    <!--
                    <div class="container">
                    <div class="row right">
                    <div class="col l12 s12">
                        <?php if(check_permission("Blog","edit_blog")||(isset($_SESSION['user_id'])&&$post['poster']==$_SESSION['user_id'])){?><a class="btn-floating blue blog-btn" href="edit_blog_post.php?post=<?php echo $post['id'] ?>"><i class="mdi-editor-mode-edit"></i></a><?php } ?>
                        <?php if(check_permission("Blog","delete_blog")||(isset($_SESSION['user_id'])&&$post['poster']==$_SESSION['user_id'])){?>
                        <a class="modal-trigger blog-btn btn-floating red btn-click-action" href="#modal1" name="<?php echo $post['id'] ?>"><i class="mdi-action-delete"></i></a><?php } ?>
                        </div>
                    </div>
                    </div>
                    -->
                    </td>
                </tr>
			</tr>
		</table>
        <div id="modal1" class="modal">
            <div class="modal-content">
              <h4>Are you sure you want to delete?</h4>
              <p>Once you delete this post there will be no way to recover it</p>
            </div>
            <div class="modal-footer">
            <div class="row right">
            <div class="col l12 s12">
            <a href="#!" class="modal-close waves-effect waves-blue btn blue ">Cancel</a>
              <a href="blog.php?delpost=" id="del_button" class="modal-close waves-effect waves-red btn red ">Delete</a>
              </div>
              </div>
            </div>
      	</div>
		<br />
	<?php }
}else{
  	if(check_permission("Blog","post_blog")){?>
		<br><a class="btn green" href="new_blog_post.php">New</a><br /><br />
	<?php } ?>
	<p>There are no blog posts!</p>
<?php }
?>
<div>
    <div class="row">
    
    <?php
	echo_page($num_pages, $current_page, "blog.php?");?>
    </div>
<?php
require_once("includes/end_html.php");
?>