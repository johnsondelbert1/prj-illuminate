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
if(isset($_GET['page'])&&$_GET['page']<=0){
	redirect_to($site_info['base_url']."/blog?page=1");
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
require_once("includes/begin_html.php");

if (mysqli_num_rows($result)!=0){
  	if(check_permission("Blog","post_blog")){?>
    <div class="row">
    <div class="col s6">
		<a class="btn green" href="new_blog_post.php">New</a>
        </div>
        <div class="col s6">
	<?php }
	echo_page($num_pages, $current_page, "blog.php?");?>
    </div>
    </div>
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
                                 <i class="mdi-device-access-time"></i>&nbsp;<?php echo date("g:i A", $createdtimestamp);
								if($post['lastedited']!="0000-00-00 00:00:00"){
									$lasteditedtimestamp = strtotime($post['lastedited']);
										echo '<br><i class="mdi-editor-mode-edit"></i> '.date("g:i A", $lasteditedtimestamp);
									}?> 
                                    </div>
                                    <div class="col l2 s4">
                                </i><i class="mdi-action-today"></i>&nbsp;<?php echo date("M jS 'y", $createdtimestamp);
								if($post['lastedited']!="0000-00-00 00:00:00"){
									$lasteditedtimestamp = strtotime($post['lastedited']);
										echo '<br><i class="mdi-editor-mode-edit"></i> '.date("M jS 'y", $lasteditedtimestamp);
									}?> </div>
                                    <div class="col s4">
                                    <i class="mdi-action-face-unlock"></i> &nbsp;<b><?php echo $userdata['username']; ?></b>
                                    </div>
							</div>
                            </div>
						</tr>
						<tr>
							<td class="blogbody flow-text" valign="top">
								<?php 	$content = $post['content'];
										if (strlen($content) > 1200) {
											// truncate string
											$stringCut = substr($content, 0, 1200);
										
											// make sure it ends in a word so assassinate doesn't become ass...
											$content = substr($stringCut, 0, strrpos($stringCut, ' ')).'...<br> <a href="view_blog_post.php?post='.$post['id'].'">Read More</a>'; 
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
                    <div class="row right">
                    <div class="col l12 s12">
                        <?php if(check_permission("Blog","edit_blog")||(isset($_SESSION['user_id'])&&$post['poster']==$_SESSION['user_id'])){?><a class="btn-floating blue" href="edit_blog_post.php?post=<?php echo $post['id'] ?>"><i class="mdi-editor-mode-edit"></i></a><?php } ?>
                        <?php if(check_permission("Blog","delete_blog")||(isset($_SESSION['user_id'])&&$post['poster']==$_SESSION['user_id'])){?>
                        <a class="modal-trigger btn-floating red" href="#modal1"><i class="mdi-action-delete"></i></a><?php } ?>
                        </div>
                    </div>
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
      <a href="blog.php?delpost=<?php echo $post['id'] ?>" class="modal-close waves-effect waves-red btn red ">Delete</a>
      </div>
      </div>
    </div>
  </div>
		<br />
	<?php }
	echo_page($num_pages, $current_page, "blog.php?");
}else{
  	if(check_permission("Blog","post_blog")){?>
		<br><a class="btn green" href="new_blog_post.php">New</a><br /><br />
	<?php } ?>
	<p>There are no blog posts!</p>
<?php }
?>
<div>
<?php
require_once("includes/end_html.php");
?>