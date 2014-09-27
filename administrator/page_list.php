<?php
require_once("../includes/functions.php");
if(!check_permission(array("Pages;add_pages","Pages;edit_pages","Pages;delete_pages",))){
	redirect_to("index.php");
}
?>
<?php
$query="SELECT * FROM `pages`";
$result=mysqli_query( $connection, $query);
$rows=mysqli_num_rows($result);

if($rows=0){
	redirect_to("edit_page.php?action=newpage");
}
if(isset($_POST['delpages'])){
	if(check_permission("Pages","delete_pages")){
		function del_page($id){
			global $connection;
			$query="DELETE FROM `pages` WHERE `id`={$id}";
			$result = mysqli_query( $connection, $query);
		}
		if(isset($_POST['pages'])){
			foreach($_POST['pages'] as $page){
				del_page($page);
			}
			$success="Pages deleted!";
		}else{
			$error="No pages selected!";
		}
	}
}
?>

<?php
	$query="SELECT * FROM `pages` ORDER BY `position` ASC";
	$listpagesquery=mysqli_query( $connection, $query);
	confirm_query($listpagesquery);
?>

<?php
	$query="SELECT position FROM `pages` ORDER BY `position` ASC";
	$listidsquery=mysqli_query( $connection, $query);
	confirm_query($listidsquery);
?>

<?php	
if(isset($_GET['page'])){
	$query="SELECT * FROM `pages` WHERE id={$_GET['page']}";
	$getpagequery=mysqli_query( $connection, $query);
	confirm_query($getpagequery);
	$selpage=mysqli_fetch_array($getpagequery);
}
?>

<?php
	$pgsettings = array(
		"title" => "Edit Page",
		"icon" => "icon-newspaper"
	);
	require_once("includes/begin_cpanel.php");
?>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript">
   <!-- jQuery for seven sets of "Select All" checkboxes -->
    $(document).ready(function() {
             $('input[id="pageall"]').click(function() {
             $("#page :checkbox").attr('checked', $(this).attr('checked'));
        });  
     });
</script>
<form method="post" action="page_list.php">
<table border="0" cellpadding="5" id="sticker">
  <tr>
    <?php if(check_permission("Pages","add_pages")){?>
    	<td width="110px"><a href="edit_page.php?action=newpage" class="green">New</a></li></td>
    <?php } ?>    
    <?php if(check_permission("Pages","delete_pages")){?>
    	<td width="110px"><input name="delpages" type="submit" value="Delete Pages" class="red" /></td>
    <?php } ?>
    <td></td>
  </tr>
</table>
<table style=" text-align:left;" width="100%"  class="list" cellpadding="0" cellspacing="0" id="page">
    <tr class="header">
        <th>
        	Order
        </th>
        <th>
        	Title <span style="font-size:10px;">Page type</span>
        </th>
        <th>
        	Parent Page
        </th>
        <th>
        	Author
        </th>
        <th>
        	Date
        </th>
        <th>
        	Published
        </th>
        <th>
        	Hits
        </th>
        
        <th>
        	ID
        </th>
        
        <th>
        	View
        </th>
        <th style="text-align:center;">
        <?php if(check_permission("Pages","delete_pages")){?>
            <input type="checkbox" id="pageall">
        <?php } ?>
        </th>
        <!--<th>
        	Last Edited
        </th>
        <th>
        	Editor
        </th> -->
    </tr>
<?php
while($listpage=mysqli_fetch_array($listpagesquery)){?>
	<tr>
    <!-- Order -->
        <td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
        	<?php echo $listpage['position']; ?>
        </td>
    <!-- Name -->
    	<td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
        <?php if(check_permission("Pages","edit_pages")){?>
			<a href="edit_page.php?action=edit&amp;page=<?php echo urlencode($listpage['id'])?>"><?php echo $listpage['name']?></a><br /><span style="font-size:10px;">Type: <?php echo $listpage['type']?></span>
        <?php }else{ ?>
        	<?php echo $listpage['name']?><br /><span style="font-size:10px;">Type: <?php echo $listpage['type']?></span>
        <?php } ?>
    	</td>
        <!-- Parent -->
        <td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
        	<?php
            if($listpage['parent']!=0){
				$query="SELECT * FROM `pages` WHERE id={$listpage['parent']}";
				$getpagequery=mysqli_query( $connection, $query);
				confirm_query($getpagequery);
				$parentpage=mysqli_fetch_array($getpagequery);
				echo $parentpage['name'];
			}else{
				echo "(None)";
			}
			?>
        </td>
        <!-- Author -->
        <td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
        	<?php $user=get_user($listpage['creator']); if($user['username']!=""){echo $user['username'];}else{echo "N/A";} ?>
        </td>
        <!-- Date -->
        <td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
        	<?php 
				if($listpage['created']!="0000-00-00 00:00:00"){
					$lasteditedtimestamp = strtotime($listpage['created']);
					echo date("n/j/Y, g:i A", $lasteditedtimestamp);
				}else{
					echo "N/A";
				}
			?>
        </td>
        <!-- Published -->
        <td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
        	<?php
				if($listpage['published']==1){
					echo "Yes";
				}else{
					echo "No";
				}
			?>
        </td>
        <!-- Hits -->
        <td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
        	<?php echo $listpage['views']; ?>
        </td>
        
        <!-- ID -->
        <td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
        	<?php echo $listpage['id']; ?>
        </td>
        <!-- URL -->
        <td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
        	<a href="../index.php?page=<?php echo urlencode($listpage['id']);?>" onclick="window.open('../index.php?page=<?php echo urlencode($listpage['name']);?>', 'newwindow', 'width=1017, height=500'); return false;">View<!--index.php?page=<?php echo urlencode($listpage['id']);?>--></a>
        </td>
        <?php if(check_permission("Pages","delete_pages")){?>
		<td width="5%" style="text-align:center;"><input type="checkbox" name="pages[]" value="<?php echo $listpage['id']; ?>" /></td>
        <?php } ?>
        <!-- Edited --><!--
        <td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
        	<?php 
				if($listpage['lastedited']!="0000-00-00 00:00:00"){
					$lasteditedtimestamp = strtotime($listpage['lastedited']);
					echo date("n/j/Y, g:i A", $lasteditedtimestamp);
				}else{
					echo "N/A";
				}
			?>
        </td> -->
        <!-- Editor --><!--
        <td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
        	<?php $user=get_user($listpage['editor']); echo $user['username']; ?>
        </td>-->
    </tr>
<?php }?>
</table>
</form>
<br />

<?php
	require_once("includes/end_cpanel.php");
?>