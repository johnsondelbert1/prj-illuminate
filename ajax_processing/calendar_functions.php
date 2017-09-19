<?php
//This file to be included in functions.php
if(file_exists('../includes/functions.php')){
	require_once("../includes/functions.php");
}
/*
 * Function requested by Ajax
 */
if(isset($_POST['func']) && !empty($_POST['func']) && isset($_POST['id']) && !empty($_POST['id'])){
	switch($_POST['func']){
		case 'getcalendar':
			getCalendar($_POST['id'],$_POST['year'],$_POST['month'],true);
			break;
		case 'getEvents':
			displayEvents($_POST['id'],$_POST['date']);
			break;
		//For Add Event
		case 'addEvent':
			addEvent($_POST['id'],$_POST['date'],sanitizeString($_POST['title']),sanitizeString($_POST['location']),sanitizeString($_POST['startTime']),sanitizeString($_POST['endTime']),sanitizeString($_POST['recurrence']));
			break;
		case 'deleteEvent':
			deleteEvent($_POST['id']);
			break;
		default:
			break;
	}
}

// Get calendar full HTML
function getCalendar($calID, $year = '',$month = '',$refresh = false){
	global $connection;
	$result = mysqli_query($connection, "SELECT * FROM `calendars` WHERE `id` = ".$calID);
	if($result->num_rows > 0){
		$calendarData = $result->fetch_array();

		//Check to see if calendar is visible to visitor
		if(canView(unserialize($calendarData['visibility']))){
			$dateYear = ($year != '')?$year:date("Y");
			$dateMonth = ($month != '')?$month:date("m");
			$date = $dateYear.'-'.$dateMonth.'-01';
			$currentMonthFirstDay = date("N",strtotime($date));
			$totalDaysOfMonth = cal_days_in_month(CAL_GREGORIAN,$dateMonth,$dateYear);
			$totalDaysOfMonthDisplay = ($currentMonthFirstDay == 7)?($totalDaysOfMonth):($totalDaysOfMonth + $currentMonthFirstDay);
			$boxDisplay = ($totalDaysOfMonthDisplay <= 35)?35:42;

			if(!$refresh){echo '<div id="calendar_div_'.$calID.'">';}
		?>
			<div class="calendar_section">
				<h2><?php echo $calendarData['name']; ?></h2>
				<h2 style="position: relative;">
		        	<a href="javascript:void(0);" onclick="getCalendar('calendar_div_<?php echo $calID; ?>','<?php echo $calID; ?>','<?php echo date("Y",strtotime($date.' - 1 Month')); ?>','<?php echo date("m",strtotime($date.' - 1 Month')); ?>');">&lt;&lt;</a>
		            <select name="month_dropdown" id="cal-month-<?php echo $calID; ?>" style="display: initial; width: auto;" class="month_dropdown dropdown materialize-ignore"><?php echo getAllMonths($dateMonth); ?></select>
					<select name="year_dropdown" id="cal-year-<?php echo $calID; ?>" style="display: initial; width: auto;" class="year_dropdown dropdown materialize-ignore"><?php echo getYearList($dateYear); ?></select>
		            <a href="javascript:void(0);" onclick="getCalendar('calendar_div_<?php echo $calID; ?>','<?php echo $calID; ?>','<?php echo date("Y",strtotime($date.' + 1 Month')); ?>','<?php echo date("m",strtotime($date.' + 1 Month')); ?>');">&gt;&gt;</a>
					<?php
					if(check_permission('Calendars','add_event')){
					?>
		            <a class="btn-floating btn-large waves-effect waves-light green" style="right: 5px; top: -20px; position: absolute;" onclick="addEvent(<?php echo $calID; ?>);"><i class="material-icons">add</i></a>
		        	<?php } ?>
		        </h2>
				<div class="calendar_section_top">
					<ul>
						<li>Sun</li>
						<li>Mon</li>
						<li>Tue</li>
						<li>Wed</li>
						<li>Thu</li>
						<li>Fri</li>
						<li>Sat</li>
					</ul>
				</div>
				<div class="calendar_section_bot">
					<ul>
					<?php 
						$dayCount = 1; 
						for($cb=1;$cb<=$boxDisplay;$cb++){
							if(($cb >= $currentMonthFirstDay+1 || $currentMonthFirstDay == 7) && $cb <= ($totalDaysOfMonthDisplay)){
								//Current date
								$currentDate = $dateYear.'-'.$dateMonth.'-'.$dayCount;

								$eventArr = getEvents($calID, $currentDate);

								$eventNum = count($eventArr);
								//Define date cell color
								if(strtotime($currentDate) == strtotime(date("Y-m-d"))){
									echo '<li date="'.$currentDate.'" name="'.$calID.'" class="grey date_cell">';
								}elseif($eventNum > 0){
									echo '<li date="'.$currentDate.'" name="'.$calID.'" class="light_sky date_cell">';
								}else{
									echo '<li date="'.$currentDate.'" name="'.$calID.'" class="date_cell">';
								}
								echo '<div class="date-cell-viewevent">';
								//Date cell
								echo '<span class="day-number">';
								echo $dayCount;
								echo '</span><br/>';
								
								$i = 0;
								while ($i < $eventNum) {
									if($i < 2){
										echo '<p class="cell-event-item">'.$eventArr[$i]['title'].'</p><br/>';
									}else{
										echo '<p class="cell-event-item">+ '.($eventNum - 2).' more</p><br/>';
										break;
									}
									$i++;
								}
								

								echo '</div>';
								if(check_permission('Calendars','add_event')){
									//For Add Event
									//echo '<div class="cal-edit-btn" onclick="addEvent('.$calID.',\''.$currentDate.'\');"><i class="material-icons" style="height:30px;width:30px;font-size:30px;">playlist_add</i></div>';
									//echo '<a href="javascript:;" onclick="addEvent('.$calID.',\''.$currentDate.'\');">+</a>';
								}
								echo '</li>';
								$dayCount++;
					?>
					<?php }else{ ?>
						<li><span>&nbsp;</span></li>
					<?php } } ?>
					</ul>
				</div>
			</div>
			<?php
			if(!$refresh){?>
			</div>
			<!-- Calendar popups -->
			<div id="modalViewEvents-<?php echo $calID; ?>" class="modal">
				<div class="modal-content" style="text-align:center;">
					<div class="event_list"></div>
				</div>
				<div class="modal-footer">
					<div class="row right">
						<div class="col l12 s12">
							<a href="#!" class="modal-close waves-effect waves-red btn blue" style="margin:10px;">Ok</a>
						</div>
					</div>
				</div>
			</div>
			<?php if(check_permission('Calendars','add_event')){ ?>
			<div id="modalAddEvent-<?php echo $calID; ?>" class="modal">
				<div class="modal-content" style="text-align:center;">
					<h2>Adding event on calendar <?php echo $calendarData["name"]; ?></h2>
					<p><strong>Event Title: </strong><input type="text" class="cal-event-title" value=""/></p>
					<p><strong>Date: </strong><input type="date" class="cal-event-date datepicker"></p>
					<p><strong>Event Location: </strong><input type="text" class="cal-event-location" value=""/></p>
					<p>Event Time:
						<select class="cal-event-timeStart materialize-ignore" style="display: initial; width: auto;"><option value="allDay">All Day</option><option value="12:00AM">12:00AM</option><option value="12:15AM">12:15AM</option><option value="12:30AM">12:30AM</option><option value="12:45AM">12:45AM</option><option value="1:00AM">1:00AM</option><option value="1:15AM">1:15AM</option><option value="1:30AM">1:30AM</option><option value="1:45AM">1:45AM</option><option value="2:00AM">2:00AM</option><option value="2:15AM">2:15AM</option><option value="2:30AM">2:30AM</option><option value="2:45AM">2:45AM</option><option value="3:00AM">3:00AM</option><option value="3:15AM">3:15AM</option><option value="3:30AM">3:30AM</option><option value="3:45AM">3:45AM</option><option value="4:00AM">4:00AM</option><option value="4:15AM">4:15AM</option><option value="4:30AM">4:30AM</option><option value="4:45AM">4:45AM</option><option value="5:00AM">5:00AM</option><option value="5:15AM">5:15AM</option><option value="5:30AM">5:30AM</option><option value="5:45AM">5:45AM</option><option value="6:00AM">6:00AM</option><option value="6:15AM">6:15AM</option><option value="6:30AM">6:30AM</option><option value="6:45AM">6:45AM</option><option value="7:00AM">7:00AM</option><option value="7:15AM">7:15AM</option><option value="7:30AM">7:30AM</option><option value="7:45AM">7:45AM</option><option value="8:00AM">8:00AM</option><option value="8:15AM">8:15AM</option><option value="8:30AM">8:30AM</option><option value="8:45AM">8:45AM</option><option value="9:00AM">9:00AM</option><option value="9:15AM">9:15AM</option><option value="9:30AM">9:30AM</option><option value="9:45AM">9:45AM</option><option value="10:00AM">10:00AM</option><option value="10:15AM">10:15AM</option><option value="10:30AM">10:30AM</option><option value="10:45AM">10:45AM</option><option value="11:00AM">11:00AM</option><option value="11:15AM">11:15AM</option><option value="11:30AM">11:30AM</option><option value="11:45AM">11:45AM</option><option value="12:00PM">12:00PM</option><option value="12:15PM">12:15PM</option><option value="12:30PM">12:30PM</option><option value="12:45PM">12:45PM</option><option value="1:00PM">1:00PM</option><option value="1:15PM">1:15PM</option><option value="1:30PM">1:30PM</option><option value="1:45PM">1:45PM</option><option value="2:00PM">2:00PM</option><option value="2:15PM">2:15PM</option><option value="2:30PM">2:30PM</option><option value="2:45PM">2:45PM</option><option value="3:00PM">3:00PM</option><option value="3:15PM">3:15PM</option><option value="3:30PM">3:30PM</option><option value="3:45PM">3:45PM</option><option value="4:00PM">4:00PM</option><option value="4:15PM">4:15PM</option><option value="4:30PM">4:30PM</option><option value="4:45PM">4:45PM</option><option value="5:00PM">5:00PM</option><option value="5:15PM">5:15PM</option><option value="5:30PM">5:30PM</option><option value="5:45PM">5:45PM</option><option value="6:00PM">6:00PM</option><option value="6:15PM">6:15PM</option><option value="6:30PM">6:30PM</option><option value="6:45PM">6:45PM</option><option value="7:00PM">7:00PM</option><option value="7:15PM">7:15PM</option><option value="7:30PM">7:30PM</option><option value="7:45PM">7:45PM</option><option value="8:00PM">8:00PM</option><option value="8:15PM">8:15PM</option><option value="8:30PM">8:30PM</option><option value="8:45PM">8:45PM</option><option value="9:00PM">9:00PM</option><option value="9:15PM">9:15PM</option><option value="9:30PM">9:30PM</option><option value="9:45PM">9:45PM</option><option value="10:00PM">10:00PM</option><option value="10:15PM">10:15PM</option><option value="10:30PM">10:30PM</option><option value="10:45PM">10:45PM</option><option value="11:00PM">11:00PM</option><option value="11:15PM">11:15PM</option><option value="11:30PM">11:30PM</option><option value="11:45PM">11:45PM</option></select>
						to
						<select class="cal-event-timeEnd materialize-ignore" style="display: initial; width: auto;"><option value="">Select End Time</option><option value="12:00AM">12:00AM</option><option value="12:15AM">12:15AM</option><option value="12:30AM">12:30AM</option><option value="12:45AM">12:45AM</option><option value="1:00AM">1:00AM</option><option value="1:15AM">1:15AM</option><option value="1:30AM">1:30AM</option><option value="1:45AM">1:45AM</option><option value="2:00AM">2:00AM</option><option value="2:15AM">2:15AM</option><option value="2:30AM">2:30AM</option><option value="2:45AM">2:45AM</option><option value="3:00AM">3:00AM</option><option value="3:15AM">3:15AM</option><option value="3:30AM">3:30AM</option><option value="3:45AM">3:45AM</option><option value="4:00AM">4:00AM</option><option value="4:15AM">4:15AM</option><option value="4:30AM">4:30AM</option><option value="4:45AM">4:45AM</option><option value="5:00AM">5:00AM</option><option value="5:15AM">5:15AM</option><option value="5:30AM">5:30AM</option><option value="5:45AM">5:45AM</option><option value="6:00AM">6:00AM</option><option value="6:15AM">6:15AM</option><option value="6:30AM">6:30AM</option><option value="6:45AM">6:45AM</option><option value="7:00AM">7:00AM</option><option value="7:15AM">7:15AM</option><option value="7:30AM">7:30AM</option><option value="7:45AM">7:45AM</option><option value="8:00AM">8:00AM</option><option value="8:15AM">8:15AM</option><option value="8:30AM">8:30AM</option><option value="8:45AM">8:45AM</option><option value="9:00AM">9:00AM</option><option value="9:15AM">9:15AM</option><option value="9:30AM">9:30AM</option><option value="9:45AM">9:45AM</option><option value="10:00AM">10:00AM</option><option value="10:15AM">10:15AM</option><option value="10:30AM">10:30AM</option><option value="10:45AM">10:45AM</option><option value="11:00AM">11:00AM</option><option value="11:15AM">11:15AM</option><option value="11:30AM">11:30AM</option><option value="11:45AM">11:45AM</option><option value="12:00PM">12:00PM</option><option value="12:15PM">12:15PM</option><option value="12:30PM">12:30PM</option><option value="12:45PM">12:45PM</option><option value="1:00PM">1:00PM</option><option value="1:15PM">1:15PM</option><option value="1:30PM">1:30PM</option><option value="1:45PM">1:45PM</option><option value="2:00PM">2:00PM</option><option value="2:15PM">2:15PM</option><option value="2:30PM">2:30PM</option><option value="2:45PM">2:45PM</option><option value="3:00PM">3:00PM</option><option value="3:15PM">3:15PM</option><option value="3:30PM">3:30PM</option><option value="3:45PM">3:45PM</option><option value="4:00PM">4:00PM</option><option value="4:15PM">4:15PM</option><option value="4:30PM">4:30PM</option><option value="4:45PM">4:45PM</option><option value="5:00PM">5:00PM</option><option value="5:15PM">5:15PM</option><option value="5:30PM">5:30PM</option><option value="5:45PM">5:45PM</option><option value="6:00PM">6:00PM</option><option value="6:15PM">6:15PM</option><option value="6:30PM">6:30PM</option><option value="6:45PM">6:45PM</option><option value="7:00PM">7:00PM</option><option value="7:15PM">7:15PM</option><option value="7:30PM">7:30PM</option><option value="7:45PM">7:45PM</option><option value="8:00PM">8:00PM</option><option value="8:15PM">8:15PM</option><option value="8:30PM">8:30PM</option><option value="8:45PM">8:45PM</option><option value="9:00PM">9:00PM</option><option value="9:15PM">9:15PM</option><option value="9:30PM">9:30PM</option><option value="9:45PM">9:45PM</option><option value="10:00PM">10:00PM</option><option value="10:15PM">10:15PM</option><option value="10:30PM">10:30PM</option><option value="10:45PM">10:45PM</option><option value="11:00PM">11:00PM</option><option value="11:15PM">11:15PM</option><option value="11:30PM">11:30PM</option><option value="11:45PM">11:45PM</option></select>
					</p>
					<p>Recurrence:
						<select class="cal-event-recurrence materialize-ignore" style="display: initial; width: auto;">
							<option value="none">Once</option>
							<option value="weekly">Weekly</option>
							<option value="monthly">Monthly</option>
						</select>
					</p>
					<!--<input type="hidden" class="cal-event-date" value=""/>-->
					<div class="event_list"></div>
				</div>
				<div class="modal-footer">
					<div class="row right">
						<div class="col l12 s12">
							<a href="#!" class="modal-close waves-effect waves-red btn red" style="margin:10px;">Cancel</a>
							<a href="#!" class="cal-addEventBtn modal-close waves-effect waves-green btn green" id="cal-addEventBtn-<?php echo $calID; ?>" style="margin:10px;">Add</a>
						</div>
					</div>
				</div>
			</div>
			<?php 
			}
			}
		}
	}
}

/*
 * Get months options list for calendar.
 */
function getAllMonths($selected = ''){
	$options = '';
	for($i=1;$i<=12;$i++)
	{
		$value = ($i < 01)?'0'.$i:$i;
		$selectedOpt = ($value == $selected)?'selected':'';
		$options .= '<option value="'.$value.'" '.$selectedOpt.' >'.date("F", mktime(0, 0, 0, $i+1, 0, 0)).'</option>';
	}
	return $options;
}

/*
 * Get years options list for calendar.
 */
function getYearList($selected = ''){
	$options = '';
	$currentYear = intval(date("Y"));
	for($i=$currentYear-2;$i<=$currentYear+10;$i++)
	{
		$selectedOpt = ($i == $selected)?'selected':'';
		$options .= '<option value="'.$i.'" '.$selectedOpt.' >'.$i.'</option>';
	}
	return $options;
}

function getEvents($calID, $date){
	global $connection;

	//Get day of the week and day of the month from the date
	$dayOfWeek = date('D',strtotime($date));
	$dayOfMonth = date('j',strtotime($date));

	$eventArr = array();
	
	//Get events based on the current date
	$query = "SELECT * FROM `calendar_events` WHERE `date` = '{$date}' AND `recurrence` = 'none' AND `status` = 1 AND `calendar_id` = {$calID} ORDER BY STR_TO_DATE(time_start, '%l:%i %p')";
	$result = mysqli_query($connection, $query);
	if(mysqli_num_rows($result)>0){
		while($event = mysqli_fetch_array($result)){
			array_push($eventArr, $event);
		}
	}

	//Get recurring events that apply to current date
	$query = "SELECT * FROM `calendar_events` WHERE `recurrence` <> 'none' AND (`recurrence_data` = '{$dayOfWeek}' OR `recurrence_data` = '{$dayOfMonth}') AND `status` = 1 AND `calendar_id` = {$calID} ORDER BY STR_TO_DATE(time_start, '%l:%i %p')";
	$result = mysqli_query($connection, $query);
	if(mysqli_num_rows($result)>0){
		while($event = mysqli_fetch_array($result)){
			array_push($eventArr, $event);
		}
	}

	return $eventArr;
}

/*
 * Get events by date
 */
function displayEvents($calendar_id, $date = ''){
	global $connection;

	$eventListHTML = '';
	$date = $date?$date:date("Y-m-d");
	//Get all events for the current date
	$eventArr = getEvents($calendar_id, $date);
	if(count($eventArr) > 0){
		$eventListHTML = '<h2>Events on '.date("l, F j Y",strtotime($date)).'</h2>';
		$eventListHTML .= '<ul>';
		foreach($eventArr as $row){
			if($row['time_start'] == 'allDay'){
				$eventTime = 'All Day';
			}else{
				$eventTime = $row['time_start'].' - '.$row['time_end'];
			}
            $eventListHTML .= '<li>';
            if(check_permission('Calendars','delete_event')){
            	$eventListHTML .= '<span id="delevent-'.$calendar_id.'-'.$row['id'].'" class="cal-delete-event"><img src="'.$GLOBALS['HOST'].'/../images/delete.png" alt="Delete Event" /></span>';
            }
            $eventListHTML .= $row['title'];
            $eventListHTML .= '<ul><li>Location: '.$row['location'].'</li><li>Time: '.$eventTime.'</li></ul>';
            $eventListHTML .= '</li>';
        }
		$eventListHTML .= '</ul>';
	}else{
		$eventListHTML = '<h2>No events on '.date("l, F j Y",strtotime($date)).'</h2>';
	}
	echo $eventListHTML;
}

/*
 * Add event to date
 */
function addEvent($calendar_id, $date, $title, $location, $startTime, $endTime, $recurrence){
	global $connection;

	//Set recurrence data
	$recurrenceData = '';
	switch ($recurrence) {
		case 'none':
			$recurrenceData = '';
			break;
		case 'weekly':
			$recurrenceData = date('D',strtotime($date));
			break;
		case 'monthly':
			$recurrenceData = date('j',strtotime($date));
			break;
		default:
			$recurrence = 'none';
			break;
	}

	//if start time is all day, make end time blank
	if($startTime == 'allDay'){
		$endTime = '';
	}

	$currentDate = date("Y-m-d H:i:s");

	//try to parse date
	$date = strtotime($date);

	if($date != false){
		$formattedDate = date('Y-m-d', $date);
		//Insert the event data into database
		$insert = mysqli_query($connection, "INSERT INTO `calendar_events` (`calendar_id`, `title`, `date`, `created`, `modified`,`time_start`,`time_end`,`location`,`recurrence`,`recurrence_data`) VALUES ({$calendar_id},'{$title}','{$formattedDate}','{$currentDate}','{$currentDate}','{$startTime}','{$endTime}','{$location}','{$recurrence}','{$recurrenceData}')");
		if($insert){
			echo $formattedDate.'-returndate';
		}else{
			echo 'Error inserting into database.';
		}
	}else{
		echo 'Date parse error.';
	}
}
function deleteEvent($event_id){
	global $connection;
	$calendar_id = '';
	$event_date = '';
	if(check_permission('Calendars','delete_event')){
		$result = mysqli_query($connection, "SELECT `calendar_id`, `date` FROM `calendar_events` WHERE `id` = ".$event_id);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){ 
				$calendar_id = $row['calendar_id'];
				$event_date = $row['date'];
			}
			$result = mysqli_query($connection, "DELETE FROM `calendar_events` WHERE `id` = ".$event_id);
			confirm_query($result);
			echo displayEvents($calendar_id,$event_date);
		}
	}
}
?>
