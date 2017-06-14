            	</div>
                </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="footerwrap">
        <div id="footer">
            <div id="footer-content" style="float:left;">
                <?php echo $GLOBALS['site_info']['contact_phone'];?><br/>
                <?php echo $GLOBALS['site_info']['address_line1'];if($GLOBALS['site_info']['address_line2']!=''){echo ', '.$GLOBALS['site_info']['address_line2'];}?><br/>
                <?php echo $GLOBALS['site_info']['address_city'];if($GLOBALS['site_info']['address_stateregion']!=''){echo ', '.$GLOBALS['site_info']['address_stateregion'];} echo ' '.$GLOBALS['site_info']['address_zip'];?><br/>
                <?php echo $GLOBALS['site_info']['address_country'];?><br/>
                <?php echo '<a href="mailto:'.$GLOBALS['site_info']['contact_email'].'">'.$GLOBALS['site_info']['contact_email'].'</a>';?>
                
            </div>
            <div id="footer-content" style="float:left;">
                <?php if($GLOBALS['site_info']['footer_content']!=""){echo $GLOBALS['site_info']['footer_content']."<br>";} ?>
            </div>
            <div style="text-align:right; font-size:14px; float:right;" id="login-status">
                <?php check_login(); ?><br/><br/><br/>
                <?php echo $GLOBALS['site_info']['name']; ?><?php if($GLOBALS['site_info']['copyright_text']!=''){echo ' '.$GLOBALS['site_info']['copyright_text'];} ?>, Copyright © <?php echo date("Y"); ?>.
            </div>
        </div>
    </div>
    <!-- Illuminate CMS by Second Gen Design -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.1/masonry.pkgd.js"></script>
    <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <!-- Drag and drop touch screens -->
    <script src="<?php echo $GLOBALS['HOST']; ?>/jscripts/jquery.ui.touch-punch.min.js"></script>
    <!-- Website designed by Second Gen Design -->
<script type="text/javascript">
$(document).on("scroll", function() {

    if($(document).scrollTop()>25) {
        $("div.nav").removeClass("large").addClass("small");
    } else {
        $("div.nav").removeClass("small").addClass("large");
    }
    
});
</script>
    <script type="text/javascript">
    <?php
    //Display messages
    if(isset($error)&&!is_array($error)){
        echo "Materialize.toast('".$error."', 8000, 'red');";
    }elseif(isset($error)&&is_array($error)&&!empty($error)){
        foreach ($error as $value) {
            echo "Materialize.toast('".$value."', 8000, 'red');";
        }
    } 
    ?>
    <?php
    if(isset($success)&&!is_array($success)){
        echo "Materialize.toast('".$success."', 8000, 'green');";
    }elseif(isset($success)&&is_array($success)&&!empty($success)){
        foreach ($success as $value) {
            echo "Materialize.toast('".$value."', 8000, 'green');";
        }
    } 
    ?>
    <?php
    if(isset($message)&&!is_array($message)){
        echo "Materialize.toast('".$message."', 8000, 'yellow black-text');";
    }elseif(isset($message)&&is_array($message)&&!empty($message)){
        foreach ($message as $value) {
            echo "Materialize.toast('".$value."', 8000, 'yellow black-text');";
        }
    } 
    ?>
    </script>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function(){
            if($("#TabbedPanels1").length){
                var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
            }

            $('.datepicker').pickadate({
                selectMonths: true, // Creates a dropdown to control month
                selectYears: 15, // Creates a dropdown of 15 years to control year
                //reassigns the picker to the body so it isn't restrained to within the modal
                onStart: () => {
                  $('.picker').appendTo('body');
                }
            });

            autosize(document.querySelectorAll('textarea'));

            // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
            $('.modal-trigger').leanModal();

            //Apply materialize to all dropdowns except for class materialize-ignore
            $('select').not('.materialize-ignore').material_select();

            $('.tooltipped').tooltip({delay: 50});

            //AJAX for updating email subscription
            $(".update-check-subscription").change(function(e){
                var act;
                if(this.checked){
                    act = 'sub';
                }else{
                    act = 'unsub';
                }
                var subData = this.getAttribute('id').split('_');
                var subType = subData[1];
                var subId = subData[2];
                $.post("<?php echo $GLOBALS['HOST']; ?>/ajax_processing/update_subscription.php",
                {
                    action: act,
                    type: subType,
                    id: subId,
                },
                function(data, status){
                    if(status == 'success'){
                        if(data == 'success'){
                            Materialize.toast('Subscription updated!', 8000, 'green');
                        }else{
                            Materialize.toast('An error has occured.'+data, 8000, 'red');
                        }
                    }else{
                        Materialize.toast('An error has occured. Try again later.', 8000, 'red');
                    }
                });
            });
        });

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
        function addEvent(calID){
            //$('#modalAddEvent-'+calID+' .cal-event-date').val(date);
            //$('#modalAddEvent-'+calID+' .cal-eventDateView').html(date);
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
/*            $('.cal-edit-btn').on('click', function(){
                $("#modalAddEvent-"+calID).openModal();
            });*/
            $('.date_cell').mouseleave(function(){
                $(".date_popup_wrap").fadeOut();        
            });
            $(document).click(function(){
                //$('.event_list').slideUp('slow');
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
                        if(msg.includes('returndate')){
                            var dateSplit = msg.split("-");

                            //reset form
                            $('#modalAddEvent-'+calID+' .cal-event-title').val('');
                            $('#modalAddEvent-'+calID+' .cal-event-date').val('');
                            $('#modalAddEvent-'+calID+' .cal-event-location').val('');
                            $('#modalAddEvent-'+calID+' .cal-event-timeStart').val($('#modalAddEvent-'+calID+' .cal-event-timeStart option:first').val());
                            $('#modalAddEvent-'+calID+' .cal-event-timeEnd').val($('#modalAddEvent-'+calID+' .cal-event-timeEnd option:first').val());
                            $('#modalAddEvent-'+calID+' .cal-event-recurrence').val($('#modalAddEvent-'+calID+' .cal-event-recurrence option:first').val());

                            getCalendar('calendar_div_'+calID,calID,dateSplit[0],dateSplit[1]);
                            Materialize.toast('Event "'+title+'" Created Successfully.', 8000, 'green');
                        }else{
                            Materialize.toast('A problem occurred. '+msg, 8000, 'red');
                        }
                    }else{
                        Materialize.toast('A problem occurred. '+msg, 8000, 'red');
                    }
                });
            });
        });

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
/*            $('.cal-edit-btn').on('click', function(){
                $("#modalAddEvent-"+calID).openModal();
            });*/
            $('.date_cell').mouseleave(function(){
                $(".date_popup_wrap").fadeOut();        
            });
            $(document).click(function(){
                //$('.event_list').slideUp('slow');
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
        });
    </script>

    <!--JQuery UI-->
    <script>
        $(function() {
            $('.sortable').sortable();
            $('.handles').sortable({
                handle: 'span'
            });
            $('.connected').sortable({
                connectWith: '.connected'
            });
            $('.exclude').sortable({
                items: ':not(.disabled)'
            });
        });
    </script>
      
    <script type="text/javascript">
    //custom JS code
	
	// Initialize collapse button
  $('.button-collapse').sideNav({
      menuWidth: 300, // Default is 240
      edge: 'left', // Choose the horizontal origin
      closeOnClick: true // Closes side-nav on <a> clicks, useful for Angular/Meteor
    }
  );
  </script>
    </body>
</html>
