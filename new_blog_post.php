<?php
require_once("includes/session.php");
require_once("includes/functions.php");
?>
<?php
if(!check_permission("Blog","post_blog")){
	redirect_to($site_info['base_url']."/blog?error=".urlencode("You do not have permission to post a blog!"));
}
if(isset($_POST['submit'])){
	if($_POST['title']!=""){
		$content=strip_tags(nl2br(mysqli_real_escape_string($connection, $_POST['content'])), "<b><a><p><img><br><hr><ul><ol><li><sup><sub><video><source>");
		$title=strip_tags(mysqli_real_escape_string($connection, $_POST['title']));
		date_default_timezone_set($site_info['timezone']);
		$date=date("Y/m/d H:i:s", time());
		
		$query="INSERT INTO `blog` (
					`datecreated`, `poster`, `title`, `content` 
				) VALUES (
					'{$date}', '{$_SESSION['user_id']}', '{$title}', '{$content}')";
		$result=mysqli_query( $connection, $query);
		confirm_query($result);
		
		$query="SELECT * FROM `blog` ORDER BY `id` DESC LIMIT 1";
		$result=mysqli_query( $connection, $query);
		$lastid=mysqli_fetch_array($result);
		
		mkdir("blog_galleries/".$lastid['id']);
		mkdir("blog_galleries/".$lastid['id']."/gallery");
		mkdir("blog_galleries/".$lastid['id']."/gallery-thumbs");
		
		redirect_to($site_info['base_url']."/blog?success=".urlencode("Blog posted!"));
	}else{
		$error="Title cannot be blank.";
	}

}
?>
<?php
$pgsettings = array(
	"title" => "Compose New Blog Post",
	"pageselection" => false,
	"nav" => true,
	"banner" => 0,
	"use_google_analytics" => 1,
);
require_once("includes/begin_html.php");
?>
<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: "textarea",
		theme: "modern",
		skin: 'light',
		width: '100%',
		height: '400px',
		resize: false,
		menubar : false,
		statusbar : false,
		plugins: [
			"advlist autolink lists link image charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars fullscreen",
			"insertdatetime media nonbreaking save contextmenu directionality",
			"emoticons paste textcolor code table colorpicker"
		],
		contextmenu: "link image print code fullscreen",
		toolbar1: "insertfile | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image | forecolor backcolor emoticons | undo redo | print colorpicker",
		image_advtab: true,
		templates: [
			{title: 'Test template 1', content: 'Test 1'},
			{title: 'Test template 2', content: 'Test 2'}
		]
	});
</script>
<h1>Compose New Blog Post</h1>
<form action="new_blog_post.php" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#ffffff">
  <td>
  <input style="width:45%;" type="text" name="title" class="text" maxlength="1024" placeholder="Title"<?php if(isset($_POST['title'])){echo $_POST['title'];} ?>/>
  </td>
  </tr>
  <tr>
    <td style="overflow:hidden;"><textarea name="content" id="newblogcontent"><?php if(isset($_POST['content'])){echo $_POST['content'];};?></textarea></td>
  </tr>
  <tr bgcolor="#ffffff">
  <td>
  <table width="200" border="0">
  <tr>
    <td><input class="green" class="button" type="submit" name="submit" value="Submit" /></td>
    <td><a class="blue" href="blog.php">Cancel</a></td>
  </tr>
</table>
  </td>
</table>
</form>


<?php
	require_once("includes/end_html.php");
?>