
                </div>
    		</div>
        
                <div id="footer">
                        <div style="float:left; text-align:left; margin-left:10px;">
                            Project Illuminate by <a href="http://www.secondgenerationdesign.com" target="_blank">Second Gen Design</a>, Copyright © 2011-<?php echo date("Y"); ?>.<br/>
                            Version <?php echo $site_info['version']; ?>
                        </div>
                    </div>
            </div>
    <script type="text/javascript">
    	var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
    </script>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function(){
            $("a[rel^='prettyPhoto']").prettyPhoto();
        });
		function changeTab(id){
			TabbedPanels1.showPanel(id);
			return false;
		}
	<?php if(isset($_GET['tab'])){?>changeTab(<?php echo $_GET['tab'];?>);<?php } ?>
    </script>
    <script src="../jscripts/jquery.fileuploadmulti.min.js"></script>
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>-->
    <!-- dropdown -->
    <!--<script type='text/javascript'>  
  
 var $jq = jQuery.noConflict();  
  
</script>
    <script src="jscripts/drop/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="jscripts/drop/bootstrap.min.js"></script>
    <script src="jscripts/drop/bootstrap-select.js"></script>
    <script src="jscripts/drop/application.js"></script>-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
      <script type="text/javascript" src="jscripts/materialize.min.js"></script>
      <script type="text/javascript">
	  $(document).ready(function() {
    $('select').material_select();
  });
	  </script>
    </body>
</html>