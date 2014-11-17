            	</div>
            </td>
        </tr>
    </table>
    </div>
    <div id="footerwrap">
        <div id="footer">
        
            <div style="float:left; max-width:50%;">
                <?php if($site_info['footer_content']!=""){echo $site_info['footer_content']."<br>";} ?>
                <?php echo $site_info['name']; ?>, Copyright © 2014. Website designed by <a href="http://www.secondgenerationdesign.com" target="_blank">Second Gen Design</a>
            </div>
            <div style="text-align:right; font-size:10px; float:right;">
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
    
    </body>
</html>