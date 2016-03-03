<?php
require_once("../includes/functions.php");
confirm_logged_in();
if(!check_permission("Website","cpanel_access")){
	redirect_to("login.php?error=".urlencode('You do not have Cpanel Access'));
}
if(!check_permission("Forum","edit_forum")){
  redirect_to("index?error=".urlencode('You do not have access to that!'));
}
  if(isset($_POST['edit'])){
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
      $query="UPDATE `forums` SET `name` = '{$name}', `description`='{$description}', `visible`='{$visible}' WHERE `id` ={$_GET['id']}";
      $result=mysqli_query($connection, $query);
      confirm_query($result);

      $success="Forum successfully edited!";
    }else{
      $error='Name cannot be empty.';
    }
    
  }
?>
<?php
if(isset($_GET['id'])){
  $query="SELECT * FROM  `forums` WHERE `id` = ".$_GET['id']; 
  $result=mysqli_query($connection, $query);
  confirm_query($result);

  if(mysqli_num_rows($result)!=0){
    $forum=mysqli_fetch_array($result);

    //Get visibility array
    function exception_error_handler($errno, $errstr, $errfile, $errline ) {
        $visibility = array();
    }
    set_error_handler("exception_error_handler");

    if(!$visibility = unserialize($forum['visible'])){
      $visibility = array();
    }
  }else{
    redirect_to("list-forums?error=".urlencode('Forum does not exist!'));
  }
  
}else{
  redirect_to("list-forums?error=".urlencode('Forum does not exist!'));
}
?>
<?php
	$pgsettings = array(
		"title" => 'Editing Forum "'.$forum['name'].'"',
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
     });
  </script>
  <form method="post">
  <input name="edit" type="submit" class="btn green" value="Save">&nbsp;&nbsp;&nbsp;<a class="btn red" href="list-forums">Cancel</a><br/><br/><br/>
    <label for="name">Name</label><input name="name" id="name" type="text" value="<?php echo $forum['name']; ?>" maxlength="128" style="width:400px;" /><br/>
    <label for="description">Description</label><input name="description" id="name" type="text" value="<?php echo $forum['description']; ?>" maxlength="512" style="width:400px;" /><br/>
    <h2>Visibility</h2>
    <select name="visible[]" id="visibilitySelector">
        <option value="any"<?php if($visibility[0] == 'any'){echo ' selected="selected"';} ?>>Anyone</option>
        <option value="loggedin"<?php if($visibility[0] == 'loggedin'){echo ' selected="selected"';} ?>>Logged In</option>
        <option value="loggedout"<?php if($visibility[0] == 'loggedout'){echo ' selected="selected"';} ?>>Logged Out</option>
        <option value="" id="selranks" <?php if($visibility[0] != 'any'&&$visibility[0] != 'loggedin'&&$visibility[0] != 'loggedout'){echo ' selected="selected"';} ?>>Custom</option>
    </select>
    <div id="rankcontainer" style="background-color:#DDDDDD;<?php if($visibility[0] != 'any'&&$visibility[0] != 'loggedin'&&$visibility[0] != 'loggedout'){echo ' visibility:visible;';}else{echo ' visibility:hidden;';} ?>">
      <?php 
        $query="SELECT * FROM `ranks`";
        $listrankssquery=mysqli_query( $connection, $query);
        confirm_query($listrankssquery);

        while($listrank = mysqli_fetch_array($listrankssquery)){
      ?>
      <input id="rank-<?php echo $listrank['id']; ?>" type="checkbox" name="visible[]" value="<?php echo $listrank['id']; ?>" <?php if(in_array($listrank['id'], $visibility)){echo "checked ";} ?>/><label for="rank-<?php echo $listrank['id']; ?>"><?php echo $listrank['name']; ?></label><br/>
      <?php
      }
      ?>
    </div>
  </form>
<?php
	require_once("includes/end_cpanel.php");
?>