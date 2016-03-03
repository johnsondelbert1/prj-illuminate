<?php
require_once("../includes/functions.php");
confirm_logged_in();
if(!check_permission("Website","cpanel_access")){
	redirect_to("login.php?error=".urlencode('You do not have Cpanel Access'));
}
if(!check_permission("Forum","edit_forum")){
  redirect_to("index?error=".urlencode('You do not have access to that!'));
}
  if(isset($_POST['user_forum_data'])){
    if(isset($_POST['forum_data'])&&$_POST['forum_data']!=""){
      $forum_data=serialize($_POST['forum_data']);
    }else{
      $forum_data="";
    }
    $query="UPDATE `site_info` SET `forum_post_custom_user_data`='{$forum_data}' WHERE `id` = 1";
    $result=mysqli_query($connection, $query);
    confirm_query($result);

    $success='User data displayed on forums has been updated.';
  }
  if(isset($_POST['new'])){
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    //prep visibility POST array
    if(isset($_POST['visible'])&&$_POST['visible']!=""){
      if($_POST['visible'][0]!=""){
        if(count($_POST['visible'])>1){
          $visible=serialize(array($_POST['visible'][0]));
        }else{
          $visible=serialize($_POST['visible']);
        }
      }else{
        if(count($_POST['visible'])>1){
          $visible=$_POST['visible'];
          array_shift($visible);
          $visible=serialize($visible);
        }else{
          $visible=serialize(array('any'));
        }
        
      }
    }else{
      $visible=serialize(array('any'));
    }

    if($name != ''){
      $query="INSERT INTO `forums` (
            `name`, `description`, `visible` 
          ) VALUES (
            '{$name}', '{$description}', '{$visible}')";
      $result=mysqli_query( $connection, $query);
      confirm_query($result);
      $success="Forum created!";
    }else{
      $error='Name cannot be empty.';
    }

  }
  if(isset($_GET['action'])&&isset($_GET['forumid'])){
      if(check_permission("Forum","add_delete_forum")){
        
        if($_GET['action']=="editforum"){
          $query="SELECT name, description FROM forums WHERE id={$_GET['forumid']}";
          $result=mysqli_query( $connection, $query);
          $forumedit=mysqli_fetch_array($result);
          $forumname=strip_tags($forumedit['name']);
          $forumdesc=strip_tags($forumedit['description']);
        }elseif($_GET['action']=="delforum"){
          $query="SELECT name, description FROM forums WHERE id={$_GET['forumid']}";
          $result=mysqli_query( $connection, $query);
          if(mysqli_num_rows($result)!=0){
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
          }else{
            $error="Forum does not exist!";
          }
        }
      }else{
        $error="You do not have permission to perform this action!";
      }
  }
?>
<?php
	$pgsettings = array(
		"title" => "Forum Settings",
		"icon" => "icon-dashboard"
	);
	require_once("includes/begin_cpanel.php");
	?>
  <script type="text/javascript">
    $(document).ready(function() {
          $('select[id="visibilitySelector"]').change(function() {
            if($(this).find("option:selected").attr('id') == 'selranks'){
              $('#rankcontainer').css('visibility','visible');
            }else{
              $('#rankcontainer').css('visibility','hidden');
            }
          });
          $(".btn-click-action").click(function(){
            $("#del_button").attr("href", "list-forums?action=delforum&forumid="+$(this).attr('name'));
          });
     });
  </script>
  <h2>Add Forum</h2>
  <form method="post">
    <label for="name">Name</label><input name="name" id="name" type="text" value="" maxlength="128" style="width:400px;" /> 
    <label for="description">Description</label><input name="description" id="name" type="text" value="" maxlength="512" style="width:400px;" /><br/>
    <h2>Visibility</h2>
    <select name="visible[]" id="visibilitySelector">
        <option value="any">Anyone</option>
        <option value="loggedin">Logged In</option>
        <option value="loggedout">Logged Out</option>
        <option value="" id="selranks">Custom</option>
    </select>
    <div id="rankcontainer" style="background-color:#DDDDDD; visibility: hidden;">
      <?php 
        $query="SELECT * FROM `ranks`";
        $listrankssquery=mysqli_query( $connection, $query);
        confirm_query($listrankssquery);

        while($listrank = mysqli_fetch_array($listrankssquery)){
      ?>
      <input id="rank-<?php echo $listrank['id']; ?>" type="checkbox" name="visible[]" value="<?php echo $listrank['id']; ?>" /><label for="rank-<?php echo $listrank['id']; ?>"><?php echo $listrank['name']; ?></label><br/>
      <?php
      }
      ?>
    </div>
    <input name="new" type="submit" class="btn green" value="Add Forum">
  </form>
<table class="forum" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <th class="forumtitle" colspan="2">Forum</th>
    <th class="forumtitle">Last thread posted</th>
    <?php if(check_permission(array("Forum;add_delete_forum","Forum;edit_forum",))){?>
      <th class="forumtitle">Controls</th>
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
      
        $query="SELECT id, forumid FROM `forum_threads` 
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
            <td><a href="edit-forum?id=<?php echo urlencode($forum['id']);?>"><?php echo $forum['name'];?></a><br /><?php echo $forum['description'];?></td>
              <td><b><?php echo $threadcount; ?></b> Topics<br />
              <b><?php echo $messagecount; ?></b> Replies</td>
              <td><?php if($threadcount != 0){echo date("m/d/Y h:i A" ,strtotime($messagedate['date']));}else{echo "N/A";} ?></td>
              <?php if(check_permission(array("Forum;add_delete_forum","Forum;edit_forum",))){?>
              <td style="text-align:center;">
              <a class="btn-floating blue" href="edit-forum?id=<?php echo urlencode($forum['id']);?>"><i class="mdi-editor-mode-edit"></i></a>
                <?php if(check_permission("Forum","add_delete_forum")){?><a class="modal-trigger btn-floating red btn-click-action" href="#modal1" name="<?php echo urlencode($forum['id']);?>"><i class="mdi-action-delete"></i></a><?php } ?>
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
<h2>User Data to Display on Forum Posts</h2>
<form method="post" action="list-forums">
<?php
  //Custom User Fields
  $query="SELECT `forum_post_custom_user_data` FROM `site_info` WHERE `id` = 1";
  $result=mysqli_query($connection, $query);
  confirm_query($result);
  $custom_data=mysqli_fetch_array($result);
  if($custom_data['forum_post_custom_user_data']!=''){
    $displayed_data = unserialize($custom_data['forum_post_custom_user_data']);
  }else{
    $displayed_data = array();
  }

  $query="SELECT * FROM `custom_field_users_properties`";
  $result=mysqli_query( $connection, $query);

    if(mysqli_num_rows($result)!=0){
      while ($field = mysqli_fetch_array($result, MYSQLI_ASSOC)) {?>
    <div class="col s12 l3"><input type="checkbox" name="forum_data[]" id="<?php echo $field['name']; ?>" value="<?php echo $field['id']; ?>"<?php if(in_array($field['id'], $displayed_data)){echo " checked";} ?> /><label for="<?php echo $field['name']; ?>"><?php echo $field['name']; ?></label></div>
    <?php
      }
    }
  ?>
  <input name="user_forum_data" type="submit" class="btn green" value="Save">
</form>
<div id="modal1" class="modal">
<div class="modal-content">
      <h4>Are you sure you want to delete?</h4>
      <p>Once you delete this there will be no way to recover it</p>
    </div>
    <div class="modal-footer">
    <div class="row right">
    <div class="col l12 s12">
    <a href="#!" class="modal-close waves-effect waves-blue btn blue ">Cancel</a>
      <a href="forums.php?action=delforum&forumid=" id="del_button" class="modal-close waves-effect waves-red btn red ">Delete</a>
      </div>
      </div>
    </div>
</div>
<?php
	require_once("includes/end_cpanel.php");
?>