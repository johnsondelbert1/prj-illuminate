
                </div>
    		</div>
        
                <div id="footer">
                        <div style="float:right; text-align:left; margin-right:10px;">
                            <img src="./images/favicon.png" width="16" height="16"  alt=""/><a href="http://www.illuminatecms.com" target="_blank"> IlluminateCMS </a>- v<?php echo $site_version; ?><!-- by <a href="http://www.secondgenerationdesign.com" target="_blank">Second Gen Design</a>, --> Copyright © 2011-<?php echo date("Y"); ?>.
                        </div>
                    </div>
            </div>
            
    <script type="text/javascript">
    	var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");

		function changeTab(id){
			TabbedPanels1.showPanel(id);
			return false;
		};
		<?php if(isset($_GET['tab'])){?>changeTab(<?php echo $_GET['tab'];?>);<?php } ?>
    </script>
    
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>-->
    <!-- dropdown -->
    <!--<script type='text/javascript'>  
  
 var $jq = jQuery.noConflict();  
  
</script>
    <script src="jscripts/drop/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="jscripts/drop/bootstrap.min.js"></script>
    <script src="jscripts/drop/bootstrap-select.js"></script>
    <script src="jscripts/drop/application.js"></script>-->

        <script src="../jscripts/jquery.fileuploadmulti.min.js"></script>
		<script type="text/javascript" src="jscripts/materialize.min.js"></script>
        <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="jscripts/jquery.ui.touch-punch.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				
				$('select').material_select();
				
				var s = $("#sticker");
				var pos = s.position();                    
				$(window).scroll(function() {
					var windowpos = $(window).scrollTop();
					if (s.length && windowpos >= pos.top) {
						s.addClass("stick");
						$('.content').css("padding-top","58px");
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
		<script>
			$( "#horiz-menu li a" ).click(function(e){
				$(this).next('ul').toggleClass('active');
			});
        </script>
<!--<script>
$( "#horiz-menu li ul li a" ).click(function(e){
    $(this).next('ul').toggleClass('active');
});
	  </script>-->
    </body>
</html>