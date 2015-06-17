            	</div>
            </td>
        </tr>
    </table>
    </div>
    <div id="footerwrap">
        <div id="footer">
            <div id="footer-content" style="float:left;">
                <?php if($site_info['footer_content']!=""){echo $site_info['footer_content']."<br>";} ?>
                <?php echo $site_info['name']; ?> <?php echo $site_info['copyright_text']; ?>, Copyright © <?php echo date("Y"); ?>.<!-- Website designed by <a href="http://www.secondgenerationdesign.com" target="_blank">Second Gen Design</a>-->
            </div>
            <div style="text-align:right; font-size:10px; float:right;" id="login-status">
                <?php check_login(); ?>
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
    </script>
        <!--Import jQuery before materialize.js-->
      <!--<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
      
       <script type="text/javascript">
    //custom JS code
	
	// Initialize collapse button
  $('.button-collapse').sideNav({
      menuWidth: 300, // Default is 240
      edge: 'left', // Choose the horizontal origin
      closeOnClick: true // Closes side-nav on <a> clicks, useful for Angular/Meteor
    }
  );
  $(document).ready(function(){
    // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
    $('.modal-trigger').leanModal();
  });
  $(document).ready(function(){
    $('.tooltipped').tooltip({delay: 50});
  });
  </script>
    </body>
</html>