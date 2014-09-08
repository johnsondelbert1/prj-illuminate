	<br/>
    </div>
    </div>
    <div id="footerwrap">
    <div id="footer">
    
    	<div style="float:left;">
			<?php echo $site_info['name'] ?>, Copyright © 2014.
        </div>
    	<div style="text-align:right; font-size:10px; float:right;">
			<?php check_login(); ?>
        </div>
    </div>
    </div>
    <script type="text/javascript">
    	var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
    </script>
    <script type="text/javascript">
    	var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
    </script>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function(){
            $("a[rel^='prettyPhoto']").prettyPhoto();
        });
    </script>
    
    </body>
</html>