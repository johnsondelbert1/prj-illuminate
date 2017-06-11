<?php
require_once("../includes/functions.php");
require_once('../ajax_processing/calendar_functions.php');
confirm_logged_in();

if(isset($_POST['submit'])){
        
    $name=sanitizeString($_POST['name']);
    
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
        $query="UPDATE `calendars` SET 
            `name` = '{$name}', `visibility` = '{$visible}' WHERE `id` = {$_GET['id']}";
        $result=mysqli_query($connection, $query);
        confirm_query($result);
        $success = "Calendar has been updated!";
    }else{
        $error = 'Name cannot be blank';
    }

}

$query="SELECT * FROM `calendars` WHERE `id` = ".urldecode($_GET['id']);
$result=mysqli_query( $connection, $query);

if(mysqli_num_rows($result) == 0){
    redirect_to('list-calendars');
}else{
    $calendar = mysqli_fetch_array($result);
}

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    $calVisibility = array();
}
set_error_handler("exception_error_handler");

if(!$calVisibility = unserialize($calendar['visible'])){
    $calVisibility = array();
}

	$pgsettings = array(
		"title" => "Editing calendar: ".$calendar['name'],
		"icon" => "icon-pen"
	);
	require_once("includes/begin_cpanel.php");
?>
<form method="post">
    <p>Name: <input type="text" name="name" value="<?php echo $calendar['name']; ?>" /></p>

    <p>Visible to:<select name="visible[]" id="visibilitySelector">
        <option value="any"<?php if(isset($_GET['page'])&&$calVisibility[0] == 'any'){echo ' selected="selected"';} ?>>Anyone</option>
        <option value="loggedin"<?php if(isset($_GET['page'])&&$calVisibility[0] == 'loggedin'){echo ' selected="selected"';} ?>>Logged In</option>
        <option value="loggedout"<?php if(isset($_GET['page'])&&$calVisibility[0] == 'loggedout'){echo ' selected="selected"';} ?>>Logged Out</option>
        <option value="" id="selranks" <?php if(isset($_GET['page'])&&$calVisibility[0] != 'any'&&$calVisibility[0] != 'loggedin'&&$calVisibility[0] != 'loggedout'){echo ' selected="selected"';} ?>>Custom</option>
    </select>
    <div id="rankcontainer" style="background-color:#CCCCCC;<?php if(isset($_GET['page'])&&$calVisibility[0] != 'any'&&$calVisibility[0] != 'loggedin'&&$calVisibility[0] != 'loggedout'){echo ' visibility:visible;';}else{echo ' visibility:hidden;';} ?>">
        <?php 
            $query="SELECT * FROM `ranks`";
            $listrankssquery=mysqli_query( $connection, $query);
            confirm_query($listrankssquery);

            while($listrank = mysqli_fetch_array($listrankssquery)){
        ?>
        <input id="rank-<?php echo $listrank['id']; ?>" type="checkbox" name="visible[]" value="<?php echo $listrank['id']; ?>" <?php if(isset($_GET['page'])&&in_array($listrank['id'], $calVisibility)){echo "checked ";} ?>/><label for="rank-<?php echo $listrank['id']; ?>"><?php echo $listrank['name']; ?></label><br/>
        <?php
        }
        ?>
    </div></p>
    <input type="submit" name="submit" value="Save" class="btn green" />
</form>
<?php 
    getCalendar($calendar['id']);
?>

<script type="text/javascript">
    //Calendar Operation JS
    function getCalendar(target_div,calendar_id,year,month){
        $.ajax({
            type:'POST',
            url:'<?php echo $GLOBALS['HOST'];?>/ajax_processing/calendar_functions.php',
            data:'func=getcalendar&id='+calendar_id+'&year='+year+'&month='+month,
            success:function(html){
                $('#'+target_div).html(html);
            }
        });
    }
    
    function getEvents(calID, date){
        $.ajax({
            type:'POST',
            url:'<?php echo $GLOBALS['HOST'];?>/ajax_processing/calendar_functions.php',
            data:'func=getEvents&id='+calID+'&date='+date,
            success:function(html){
                $('#modalViewEvents-'+calID+' .event_list').html(html);
                $("#modalViewEvents-"+calID).openModal();
            }
        });
    }
    //For Add Event
    function addEvent(calID,date){
        $('#modalAddEvent-'+calID+' .cal-event-date').val(date);
        $('#modalAddEvent-'+calID+' .cal-eventDateView').html(date);
        $("#modalAddEvent-"+calID).openModal();
    }

    $(document).ready(function(){
        $(document).on('click', '.date-cell-viewevent', function(){
            date = $(this).parent().attr('date');
            calID = $(this).parent().attr('name');
            getEvents(calID,date);
        });
        $(document).on('change', '.month_dropdown',function(){
            calID = $(this).attr('id').split('-');
            calID = calID[2];
            getCalendar('calendar_div_'+calID,calID,$('#calendar_div_'+calID+' .year_dropdown').val(),$('#calendar_div_'+calID+' .month_dropdown').val());
        });
        $(document).on('change', '.year_dropdown',function(){
            calID = $(this).attr('id').split('-');
            calID = calID[2];
            getCalendar('calendar_div_'+calID,calID,$('#calendar_div_'+calID+' .year_dropdown').val(),$('#calendar_div_'+calID+' .month_dropdown').val());
        });
        $(document).on('click', '.cal-delete-event',function(){
            elementID = $(this).attr('id').split('-');
            calID = elementID[1];
            eventID = elementID[2];
            $.ajax({
                type:'POST',
                url:'<?php echo $GLOBALS['HOST'];?>/ajax_processing/calendar_functions.php',
                data:'func=deleteEvent&id='+eventID,
                success:function(html){
                    //refresh event list in modal
                    $('#modalViewEvents-'+calID+' .event_list').html(html);
                    //refresh calendar in background
                    getCalendar('calendar_div_'+calID,calID,$('#calendar_div_'+calID+' .year_dropdown').val(),$('#calendar_div_'+calID+' .month_dropdown').val());
                    Materialize.toast('Event has been deleted.', 8000, 'green');
                }
            });
        });

        $('.cal-addEventBtn').on('click',function(){
            calID = $(this).attr('id').split('-');
            calID = calID[2];
            var date = $('#modalAddEvent-'+calID+' .cal-event-date').val();
            var title = $('#modalAddEvent-'+calID+' .cal-event-title').val();
            var location = $('#modalAddEvent-'+calID+' .cal-event-location').val();
            var startTime = $('#modalAddEvent-'+calID+' .cal-event-timeStart').val();
            var endTime = $('#modalAddEvent-'+calID+' .cal-event-timeEnd').val();
            var recurrence = $('#modalAddEvent-'+calID+' .cal-event-recurrence').val();

            $.post('<?php echo $GLOBALS['HOST'];?>/ajax_processing/calendar_functions.php',
            {
                func: 'addEvent',
                id: calID,
                date: date,
                title: title,
                location: location,
                startTime: startTime,
                endTime: endTime,
                recurrence: recurrence
            },
            function(msg ,status){
                if(status == 'success'){
                    if(msg == 'ok'){
                        var dateSplit = date.split("-");

                        //reset form
                        $('#modalAddEvent-'+calID+' .cal-event-title').val('');
                        $('#modalAddEvent-'+calID+' .cal-event-location').val('');
                        $('#modalAddEvent-'+calID+' .cal-event-timeStart').val($('#modalAddEvent-'+calID+' .cal-event-timeStart option:first').val());
                        $('#modalAddEvent-'+calID+' .cal-event-timeEnd').val($('#modalAddEvent-'+calID+' .cal-event-timeEnd option:first').val());
                        $('#modalAddEvent-'+calID+' .cal-event-recurrence').val($('#modalAddEvent-'+calID+' .cal-event-recurrence option:first').val());

                        getCalendar('calendar_div_'+calID,calID,dateSplit[0],dateSplit[1]);
                        Materialize.toast('Event "'+title+'" Created Successfully.', 8000, 'green');
                    }else{
                        Materialize.toast('Some problem occurred, please try again. '+msg, 8000, 'red');
                    }
                }else{
                    Materialize.toast('Some problem occurred, please try again. '+msg, 8000, 'red');
                }
            });
        });
        $('select[id="visibilitySelector"]').change(function() {
            if($(this).find("option:selected").attr('id') == 'selranks'){
                $('#rankcontainer').css('visibility','visible');
            }else{
                $('#rankcontainer').css('visibility','hidden');
            }
        });
    });
</script>

<?php
	require_once("includes/end_cpanel.php");
?>