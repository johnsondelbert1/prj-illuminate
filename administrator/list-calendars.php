<?php
require_once("../includes/functions.php");
confirm_logged_in();
if(!check_permission("Website","cpanel_access")){
	redirect_to("login.php?error=".urlencode('You do not have Cpanel Access'));
}
if(!check_permission(array("Calendars;add_calendar","Calendars;add_event","Calendars;delete_event"))){
  redirect_to("index?error=".urlencode('You do not have access to that!'));
}
//New calendars
if(isset($_POST['new_calendar'])){
  if(check_permission("Calendars","add_delete_calendar")){
    $name = sanitizeString($_POST['name']);

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
      $error='Name cannot be empty';
    }
  }else{
    $error='You don\'t have permission to do that';
  }
}

//Delete Calendars
if(isset($_POST['del_calendars'])){
  if(check_permission("Calendars","add_delete_calendar")){

    function del_cal($id){
      global $connection;
      
      $query="DELETE FROM `calendars` WHERE `id` = {$id}";
      $result=mysqli_query( $connection, $query);
      confirm_query($result);

      $query="DELETE FROM `calendar_events` WHERE `calendar_id` = {$id}";
      $result=mysqli_query( $connection, $query);
      confirm_query($result);
      
      return true;
    }
    if(isset($_POST['calendars'])){
      $flag = false;
      foreach($_POST['calendars'] as $calendar){
        $flag = del_cal($calendar);
      }
      if($flag == true){
        $success="Calendar(s) deleted";
      }else{
        $error="Problems deleting calendars";
      }
    }else{
      $error="No calendars selected!";
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
      //jQuery for "Select All" checkboxes
      var $checkall = 'selAll';
      $('input[id="'+$checkall+'"]').change(function() {
        var $all_check_status = $('input[id="'+$checkall+'"]').is(':checked');
        $("#form label").each(function(index, element) {
          var $target = $(this).attr("for");
          if($all_check_status!=$('input[id="'+$target+'"]').is(':checked')){
            $(this).trigger('click');
          }
        });
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
<form method="post">
    <table width="90%" style="text-align:left;" class="list" border="0" cellspacing="0" id="form">
        <tr>
            <th>
                Name
            </th>
            <th>
              Date Created
            </th>
            <th>
                Events
            </th>
            <?php if(check_permission("Calendars","add_delete_calendar")){?>
            <th style="text-align:center;" width="20%">
                <input type="checkbox" id="selAll">
                <label for="selAll"></label>
            </th>
            <?php } ?>
        </tr>
    <?php
      $query="SELECT * 
      FROM  `calendars` 
      ORDER BY  `id` ASC";
        
    $result=mysqli_query($connection, $query);
    confirm_query($result);
    if(mysqli_num_rows($result)!=0){
      while($calendar=mysqli_fetch_array($result)){

      $query="SELECT * 
      FROM  `calendar_events` 
      WHERE `calendar_id` = ".$calendar['id'];
        
    $eventsResult=mysqli_query($connection, $query);
    confirm_query($eventsResult);
    $numEvents = mysqli_num_rows($eventsResult)
        ?>
            <tr>
              <td><a href="edit-calendar?id=<?php echo urlencode($calendar['id']); ?>"><?php echo $calendar['name']; ?></a></td>
              <td>
              <?php 
              if($calendar['created']!="0000-00-00 00:00:00"){
                $timestamp = strtotime($calendar['created']);
                echo date("n/j/Y", $timestamp);
              }else{
                echo "N/A";
              }
              ?>
              </td>
              <td><?php echo $numEvents; ?></td>
              <?php if(check_permission("Calendars","add_delete_calendar")){?>
              <td style="text-align:center;"><input type="checkbox" name="calendars[]" value="<?php echo $calendar['id']; ?>" id="<?php echo $calendar['id']; ?>" /><label for="<?php echo $calendar['id']; ?>"></td>
              <?php } ?>
            </tr>
          <?php
      }
      if(check_permission("Calendars","add_delete_calendar")){?>
        <tr><td colspan="3"></td><td style="text-align:center;"><input type="submit" name="del_calendars" value="Delete Calendars" class="btn red"></td></tr>
      <?php }

    }else{?>
      <tr colspan="4"><td>[No calendars]</td></tr>
    <?php 
    }
    ?>
  </table>
</form>

<?php
	require_once("includes/end_cpanel.php");
?>