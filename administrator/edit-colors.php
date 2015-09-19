<?php
require_once("../includes/functions.php");
?>
<?php
if(isset($_POST['new_color'])){
    if(check_permission("Website","edit_site_colors")){
        
        $name = strip_tags(mysqli_real_escape_string($connection, $_POST['new_color_name']));
        $hex = strip_tags(mysqli_real_escape_string($connection, $_POST['new_color_hex']));

/*      $query="SELECT * FROM `style_colors` WHERE `name` = '{$name}'";
        $result=mysqli_query($connection, $query);*/

        $query="INSERT INTO `style_colors` (c_name, color_hex, date_created, creator) VALUES ('{$name}','{$hex}', '{$date}', {$_SESSION['user_id']})";
        $result=mysqli_query($connection, $query);
        confirm_query($result);

        if($name==""){
            $color = $hex;
        }else{
            $color = $name;
        }

        $success = "Color \"".$color."\" added!";
    }else{
        $error="You do not have permission to edit colors.";
    }
}
if(isset($_GET['delcolor'])){
    if(check_permission("Website","edit_site_colors")){

        $query="SELECT * FROM `style_colors` WHERE `cid` = '{$_GET['delcolor']}'";
        $result=mysqli_query($connection, $query);
        $colorData = mysqli_fetch_array($result);
        if($colorData['deletable']==1){
            $query="DELETE FROM `style_colors` WHERE `cid` = {$colorData['cid']}";
            $result=mysqli_query($connection, $query);
            confirm_query($result);

            $selectorQuery="SELECT * FROM `css_selectors` WHERE `style_color_id` = '{$_GET['delcolor']}'";
            $selectorResult=mysqli_query($connection, $selectorQuery);

            while($selector = mysqli_fetch_array($selectorResult)){
                $editQuery="UPDATE `css_selectors` SET `style_color_id` = '1' WHERE `cid` = {$selector['sid']}";
                $editResult=mysqli_query($connection, $editQuery);
            }

            if($colorData['c_name']==""){
                $color = $colorData['color_hex'];
            }else{
                $color = $colorData['name'];
            }

            $success = "Color \"".$color."\" deleted!";
        }else{
            $error="This color cannot be deleted!";
        }
    }else{
        $error="You do not have permission to edit colors.";
    }
}
?>
<?php
    $pgsettings = array(
        "title" => "Edit Site Colors",
        "icon" => "icon-palette"
    );
    require_once("includes/begin_cpanel.php");
?>
<script type="text/javascript">
    function saveDragDropNodes()
    {
        var saveString = "";
        var uls = document.getElementById('connected').getElementsByTagName('UL');
        for(var no=0;no<uls.length;no++){    // Looping through all <ul>
            var lis = uls[no].getElementsByTagName('LI');
            for(var no2=0;no2<lis.length;no2++){
                if(saveString.length>0)saveString = saveString + ";";
                saveString = saveString + uls[no].id + '|' + lis[no2].id;
            }
        }
        return saveString;
    }
    function sendPost(){
        selectorArray = saveDragDropNodes();
        $.post("post/color_edit_post.php",
        {
            colorData: $('#colorData').serialize(),
            cssSelectors: selectorArray,
        },
        function(data, status){
            if(status == 'success'){
                Materialize.toast('Colors updated!', 8000, 'green');
            }
        });
    }
    function touchHandler(event) {
        var touch = event.changedTouches[0];

        var simulatedEvent = document.createEvent("MouseEvent");
            simulatedEvent.initMouseEvent({
            touchstart: "mousedown",
            touchmove: "mousemove",
            touchend: "mouseup"
        }[event.type], true, true, window, 1,
            touch.screenX, touch.screenY,
            touch.clientX, touch.clientY, false,
            false, false, false, 0, null);

        touch.target.dispatchEvent(simulatedEvent);
        event.preventDefault();
    }

    function init() {
        document.addEventListener("touchstart", touchHandler, true);
        document.addEventListener("touchmove", touchHandler, true);
        document.addEventListener("touchend", touchHandler, true);
        document.addEventListener("touchcancel", touchHandler, true);
    }
</script>
<form method="post">
    <label for="new_color_name">Name</label><input type="text" id="new_color_name" name="new_color_name" value="<?php if(isset($_POST['new_color_name'])){echo $_POST['new_color_name'];} ?>" maxlength="32" />
    <label for="new_color_hex">Color</label><input name="new_color_hex" id="new_color_hex" type="text" value="<?php if(isset($_POST['new_color_hex'])){echo $_POST['new_color_hex'];} ?>" maxlength="7" class="color {hash:true}" />
    <input type="submit" name="new_color" class="btn green" value="Create new color" />
</form>
<br><br>
<form method="post" action="site-settings.php?tab=1" id="colorData">
<input type="button" onclick="sendPost();" name="save_color" class="btn green" value="Save color styles" />
<section id="connected">
	<?php
	$query="SELECT * FROM `style_colors`";
	$result=mysqli_query( $connection, $query);
	while($style_color=mysqli_fetch_array($result)){?>
    	<div class="card">
            <input name="color_field[<?php echo $style_color['cid']; ?>]" type="text" value="<?php echo $style_color['color_hex']; ?>" maxlength="7" class="color {hash:true}" /><br>
            <div class="card-color-body">
                <input name="color_name[<?php echo $style_color['cid']; ?>]" type="text" value="<?php echo $style_color['c_name']; ?>" maxlength="32" /><br>
                <ul id="<?php echo $style_color['cid']; ?>" class="connected list">
                <?php
                    $selector_query="SELECT * FROM `css_selectors` WHERE `style_color_id` = {$style_color['cid']}";
                    $selector_result=mysqli_query( $connection, $selector_query);
                    while($css_selector=mysqli_fetch_array($selector_result)){?>
                        <li id="<?php echo $css_selector['sid']; ?>"><?php echo $GLOBALS['color_styles'][$css_selector['s_name']]['disp_name']; ?></li>
                    <?php } ?>
                </ul>
                <?php if($style_color['deletable'] == 1){ ?>
                <a href="edit-colors?delcolor=<?php echo $style_color['cid']; ?>">
                    <div class="card-color-delete">
                        Delete
                    </div>
                </a>
                <?php } ?>
            </div>
        </div>
    <?php }?>
</section>
</form>
<?php
    require_once("includes/end_cpanel.php");
?>