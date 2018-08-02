
                </div>
    		</div>
        
                <div id="footer">
                        <div style="float:right; text-align:left; margin-right:10px;">
                            <img src="images/logo16.png" width="16" height="16"  alt=""/><a href="https://www.illuminatecms.com" target="_blank"> IlluminateCMS </a>- v<?php echo $site_version; ?><!-- by <a href="http://www.secondgenerationdesign.com" target="_blank">Second Gen Design</a>, --> Copyright © 2011-<?php echo date("Y"); ?>.
                        </div>
                    </div>
            </div>
    
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>-->
    <!-- dropdown -->
    <!--<script type='text/javascript'>  
  
 var $jq = jQuery.noConflict();  
  
</script>
    <script src="jscripts/drop/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="jscripts/drop/bootstrap.min.js"></script>
    <script src="jscripts/drop/bootstrap-select.js"></script>
    <script src="jscripts/drop/application.js"></script>-->
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script type="text/javascript" src="../materialize/js/materialize.js"></script>
        <!-- Drag and drop touch screens -->
        <script src="jscripts/jquery.ui.touch-punch.min.js"></script>
        <!-- Auto layout cards -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.1/masonry.pkgd.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {

                //Confirm Dialogs
                $('.modal-trigger').leanModal();

                //Masonry
                $grid = $('.masonry').masonry({
                    columnWidth: 10,
                    itemSelector: ".masonry_card"
                });

                $('.datepicker').pickadate({
                    selectMonths: true, // Creates a dropdown to control month
                    selectYears: 15, // Creates a dropdown of 15 years to control year
                    //reassigns the picker to the body so it isn't restrained to within the modal
                    onStart: () => {
                      $('.picker').appendTo('body');
                    }
                });

                //Apply materialize to all dropdowns except for class materialize-ignore
				$('select').not('.materialize-ignore').material_select();

                $('.tooltipped').tooltip({delay: 50});

                //Materialize Tabs
                $('ul.tabs').tabs();
                
                //Display messages
                <?php
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
                    echo "Materialize.toast('".$message."', 8000, 'yellow');";
                }elseif(isset($message)&&is_array($message)&&!empty($message)){
                    foreach ($message as $value) {
                        echo "Materialize.toast('".$value."', 8000, 'yellow');";
                    }
                } 
                ?>
				
				var s = $("#sticker");
				var pos = s.position();                    
				$(window).scroll(function() {
					var windowpos = $(window).scrollTop();
					if (s.length && windowpos >= pos.top) {
						s.addClass("stick");
						$('.content').css("padding-top","0px");
					} else {
						s.removeClass("stick");
						$('.content').css("padding-top","5px");
					}
				});
			});
        </script>
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
        </script>
        <script>
 $('.button-collapse').sideNav({
      menuWidth: 240, // Default is 240
      edge: 'left', // Choose the horizontal origin
      closeOnClick: false // Closes side-nav on <a> clicks, useful for Angular/Meteor
    }

  );
$('.collapsible').collapsible();

        </script>

<!--<script>
$( "#horiz-menu li ul li a" ).click(function(e){
    $(this).next('ul').toggleClass('active');
});
	  </script>-->
    </body>
</html>
