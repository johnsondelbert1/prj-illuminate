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
	$query="SELECT * FROM `pages` WHERE `horiz_menu` = 1 AND `issubpage` = 0  ORDER BY `position`, `name` ASC";
	$listhorizpagesquery=mysqli_query( $connection, $query);
	confirm_query($listhorizpagesquery);
	
	$query="SELECT * FROM `pages` WHERE `vert_menu` = 1 AND `issubpage` = 0  ORDER BY `position`, `name` ASC";
	$listvertpagesquery=mysqli_query( $connection, $query);
	confirm_query($listvertpagesquery);
	
	$query="SELECT * FROM `pages` WHERE `horiz_menu` = 0  AND `vert_menu` = 0 AND `issubpage` = 0 ORDER BY `position`, `name` ASC";
	$listnomenupagesquery=mysqli_query( $connection, $query);
	confirm_query($listnomenupagesquery);

	$query="SELECT position FROM `pages` ORDER BY `position` ASC";
	$listidsquery=mysqli_query( $connection, $query);
	confirm_query($listidsquery);

if(isset($_GET['page'])){
	$query="SELECT * FROM `pages` WHERE id={$_GET['page']}";
	$getpagequery=mysqli_query( $connection, $query);
	confirm_query($getpagequery);
	$selpage=mysqli_fetch_array($getpagequery);
}
function list_pages($queryresult, $is_subpg = false){
	global $connection;
	global $site_info;
	global $site_layout;
	?>
    <?php if($is_subpg == false){ ?>
    <table style=" text-align:left;" width="100%"  class="list" cellpadding="0" cellspacing="0" id="page">
        <tr class="header">
            <th width="25%">
                Title <span style="font-size:10px;">Page type</span>
            </th>
            <th>
                Order
            </th>
            <th>
                Last Edited By
            </th>
            <th>
                Last Edited
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
                <input type="checkbox" id="pageall" name="pageall">
                <label for="pageall"></label>
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
	}
	if(mysqli_num_rows($queryresult)!=0){
		while($listpage=mysqli_fetch_array($queryresult)){ ?>
			<tr>
				<!-- Name -->
				<td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
				<?php if(check_permission("Pages","edit_pages")){?>
					<a style="font-size:20px;" href="edit_page.php?action=edit&amp;page=<?php echo urlencode($listpage['id'])?>"><?php echo $listpage['name']?></a><br /><span style="font-size:10px;">Type: <?php echo $listpage['type']?></span>
				<?php }else{ ?>
					<?php echo $listpage['name']?><br /><span style="font-size:10px;">Type: <?php echo $listpage['type']?></span>
				<?php } ?>
				</td>
				<!-- Order -->
				<td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
					<?php echo $listpage['position']; ?>
				</td>
				<!-- Author -->
				<td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
					<?php $user=get_user($listpage['editor']); if($user['username']!=""){echo $user['username'];}else{echo "N/A";} ?>
				</td>
				<!-- Date -->
				<td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
					<?php 
						if($listpage['lastedited']!="0000-00-00 00:00:00"){
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
					<a href="../index.php?page=<?php echo urlencode($listpage['id']);?>" onclick="window.open('<?php echo $site_info['base_url'];?>/page/<?php echo urlencode($listpage['name']);?>', 'newwindow', 'width=1017, height=500'); return false;">View<!--index.php?page=<?php echo urlencode($listpage['id']);?>--></a>
				</td>
				<?php if(check_permission("Pages","delete_pages")){?>
				<td width="5%" style="text-align:center;"><input type="checkbox" name="pages[]" id="<?php echo $listpage['id']; ?>" value="<?php echo $listpage['id']; ?>" /><label for="<?php echo $listpage['id']; ?>"></label></td>
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
			<?php
				$query="SELECT * FROM `pages` WHERE `parent` = ".$listpage['id']." ORDER BY `position`, `name` ASC";
				$listsubpagesquery=mysqli_query( $connection, $query);
				confirm_query($listsubpagesquery);
				while($listpage=mysqli_fetch_array($listsubpagesquery)){
			?>
				<tr>
					<!-- Name -->
					<td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
					<?php if(check_permission("Pages","edit_pages")){?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a style="font-size:16px;" href="edit_page.php?action=edit&amp;page=<?php echo urlencode($listpage['id'])?>"><?php echo $listpage['name']?></a><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:10px;">Type: <?php echo $listpage['type']?></span>
					<?php }else{ ?>
						<?php echo $listpage['name']?><br /><span style="font-size:10px;">Type: <?php echo $listpage['type']?></span>
					<?php } ?>
					</td>
					<!-- Order -->
					<td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
						<?php echo $listpage['position']; ?>
					</td>
					<!-- Author -->
					<td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
						<?php $user=get_user($listpage['creator']); if($user['username']!=""){echo $user['username'];}else{echo "N/A";} ?>
					</td>
					<!-- Date -->
					<td class="<?php if(isset($_GET['page'])&&$_GET['page']==$listpage['id']){echo "editselected";}else{echo "editunselected";} ?>">
						<?php 
							if($listpage['lastedited']!="0000-00-00 00:00:00"){
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
					<td width="5%" style="text-align:center;"><input type="checkbox" name="pages[]" id="<?php echo $listpage['id']; ?>" value="<?php echo $listpage['id']; ?>" /><label for="<?php echo $listpage['id']; ?>"></label></td>
				<?php } ?>
			<?php } ?>
		<?php } ?>
    <?php }else{ ?>
    	<tr>
        	<td colspan="9" style="text-align:center; font-size:18px;">
            	There are currently no pages. Add a page!
            </td>
        </tr>
    <?php } ?>
    </table>
<?php
}
?>

<?php
	$pgsettings = array(
		"title" => "Pages",
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
<div id="sticker">
    <?php if(check_permission("Pages","add_pages")){ ?>
    	<a href="edit_page.php?action=newpage" class="green btn">New</a></li>
    <?php } ?>
    <?php if(check_permission("Pages","delete_pages")){ ?>
    	<input name="delpages" type="submit" value="Delete" class="red btn" />
    <?php } ?>
</div>
<h1>Horizontal Menu</h1>
<?php list_pages($listhorizpagesquery); ?>
<h1>Vertical Menu</h1>
<?php list_pages($listvertpagesquery); ?>
<h1>Non-Menu</h1>
<?php list_pages($listnomenupagesquery); ?>
</form>
<br />

<?php
	require_once("includes/end_cpanel.php");
?>