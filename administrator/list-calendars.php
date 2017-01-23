<?php
require_once("../includes/functions.php");
confirm_logged_in();
if(!check_permission("Website","cpanel_access")){
	redirect_to("login.php?error=".urlencode('You do not have Cpanel Access'));
}
if(!check_permission(array("Calendars;add_calendar","Calendars;add_event","Calendars;delete_event"))){
  redirect_to("index?error=".urlencode('You do not have access to that!'));
}
  if(isset($_POST['new_calendar'])){
    if(check_permission("Calendars","add_delete_calendar")){
      $name = mysqli_real_escape_string($connection, $_POST['name']);

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
        $query="INSERT INTO `calendars` (
              `name`, `created`, `creator`, `visibility` 
            ) VALUES (
              '{$name}', '{$date}', {$user_info['id']}, '{$visible}')";
        $result=mysqli_query( $connection, $query);
        confirm_query($result);
        $success="Calendar created!";
      }else{
        $error='Name cannot be empty.';
      }
    }else{
      $error='You don\'t have permission to do that.';
    }

  }
?>
<?php
	$pgsettings = array(
		"title" => "Calendars",
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
  <?php if(check_permission("Calendars","add_delete_calendar")){ ?>
  <h2>Add Calendar</h2>
  <form method="post">
    <label for="name">Name</label><input name="name" id="name" type="text" value="" maxlength="128" style="width:400px;" /> 
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
    <input name="new_calendar" type="submit" class="btn green" value="Add Calendar">
  </form>
  <?php } ?>
  <h2>Edit Calendars</h2>
  <br/>
  <ul>
    <?php
    
      $query="SELECT * 
      FROM  `calendars` 
      ORDER BY  `id` ASC";
        
    $result=mysqli_query($connection, $query);
    confirm_query($result);
    if(mysqli_num_rows($result)!=0){
      while($calendar=mysqli_fetch_array($result)){?>
            <li><a href="edit-calendar?id=<?php echo urlencode($calendar['id']); ?>"><?php echo $calendar['name']; ?></a></li>
          <?php
      }
    }else{?>
      <li>[No calendars]</li>
    <?php 
    }
    ?>
  </ul>

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