<?php
require_once("includes/functions.php");

$query="SELECT * FROM `pages` ORDER BY `position` ASC LIMIT 1";
$result=mysqli_query($connection, $query);
$firstpage=mysqli_fetch_array($result);

$query="SELECT * FROM `pages` WHERE `id` = {$site_info['homepage']}";
$result_homepage=mysqli_query($connection, $query);
confirm_query($result_homepage);

$query="SELECT * FROM `pages` WHERE `visible` = 1";
$result=mysqli_query($connection, $query);
$rows=mysqli_num_rows($result);

if(($rows)!=0){
	if(isset($_GET['page'])){
		$id=intval($_GET['page']);
		$pgname=urldecode($_GET['page']);
		$query="SELECT * FROM `pages` WHERE name='{$pgname}'";
		$result=mysqli_query( $connection, $query);
		$page=mysqli_fetch_array($result);
		
		if(mysqli_num_rows($result)==0){
			$error = "No page by this name found. Maybe it was deleted or moved?";
			$pgsettings = array(
				"title" => "No page by this name found!",
				"pageselection" => "true",
				"nav" => true,
				"banner" => 1,
				"use_google_analytics" => 1,
			);
			require_once("includes/begin_html.php");
		}else{
			
			if($page['visible']==3&&$_SESSION['rank']!=1){
				redirect_to("index.php");
			}
			$query="UPDATE `pages` SET views = views + 1 WHERE `id` = {$page['id']}";
			$result=mysqli_query( $connection, $query);
			
			switch ($page['type']){
				case "Link":
					redirect_to($page['url']);
					break;
				case "Blog":
					if(isset($_GET['error'])){
						redirect_to("blog.php?error=".$_GET['error']);
					}
					redirect_to("blog.php");
					break;
				case "Forum":
					redirect_to("forums.php");
					break;
				case "Custom":
					$pgsettings = array(
						"title" => $page["name"],
						"pageselection" => "true",
						"nav" => true,
						"banner" => $page['banner'],
						"use_google_analytics" => 1,
					);
					require_once("includes/begin_html.php");
					
					echo $page['content'];
					
					if($page['galleries']!=""){
						$pagegalleries=unserialize($page['galleries']);
						if(count($pagegalleries)>1){
							?>
							<div id="TabbedPanels1" class="TabbedPanels">
								  <ul class="TabbedPanelsTabGroup">
									<?php
									foreach($pagegalleries as $gallery){
										$query="SELECT * FROM `galleries` WHERE id={$gallery}";
										$result=mysqli_query( $connection, $query);
										$pagegallery=mysqli_fetch_array($result);
										?>
										<li class="TabbedPanelsTab" tabindex="0"><?php echo $pagegallery['name'] ?></li>
										<?php
									}
								  ?>
								  </ul>
								  <div class="TabbedPanelsContentGroup">
								  <?php
								  $gallcount = 0;
								  foreach($pagegalleries as $gallery){
										$query="SELECT * FROM `galleries` WHERE id={$gallery}";
										$result=mysqli_query( $connection, $query);
										$pagegallery=mysqli_fetch_array($result); 
								  ?>
									<div class="TabbedPanelsContent">
									<?php
										gallery("galleries/".$pagegallery['name']."/gallery/", "galleries/".$pagegallery['name']."/gallery-thumbs/", 100, 6, $gallcount);
										if($pagegallery['subgalleries']!=""){
											$subgalleries = unserialize($pagegallery['subgalleries']);
											foreach($subgalleries as $subgalleryid){
												$query="SELECT * FROM `galleries` WHERE id={$subgalleryid}";
												$result=mysqli_query( $connection, $query);
												$subgallery=mysqli_fetch_array($result);
												?>
                                                <h2><?php echo $subgallery['name'] ?></h2>
                                                <?php
												gallery("galleries/".$subgallery['name']."/gallery/", "galleries/".$subgallery['name']."/gallery-thumbs/", 100, 6, $gallcount);
											}
											
										}
										
										?>
									</div>
								<?php
									$gallcount++;
								  }
								  ?>
                                  		</div>
									</div>
                                  <?php
							}else{
								$query="SELECT * FROM `galleries` WHERE id={$pagegalleries[0]}";
								$result=mysqli_query( $connection, $query);
								$onegallery=mysqli_fetch_array($result);?>
								<h2><?php echo $onegallery['name'] ?></h2>
                                <?php
								gallery("galleries/".$onegallery['name']."/gallery/", "galleries/".$onegallery['name']."/gallery-thumbs/", 100, 6, 'gall');
								if($onegallery['subgalleries']!=""){
									$subgalleries = unserialize($onegallery['subgalleries']);
									foreach($subgalleries as $subgalleryid){
										$query="SELECT * FROM `galleries` WHERE id={$subgalleryid}";
										$result=mysqli_query( $connection, $query);
										$subgallery=mysqli_fetch_array($result);
										?>
										<h2><?php echo $subgallery['name'] ?></h2>
										<?php
										gallery("galleries/".$subgallery['name']."/gallery/", "galleries/".$subgallery['name']."/gallery-thumbs/", 100, 6, 'gall');
									}
									
								}
							}
							 ?>
						<?php
                 	break;
			}
			
		}
		}
	}else{
		if(mysqli_num_rows($result_homepage)!=0){
			$homepage=mysqli_fetch_array($result_homepage);
			redirect_to("index.php?page=".urlencode($homepage['name']));
		}else{
			redirect_to("index.php?page=".urlencode($firstpage['name']));
		}
		
	}
}else{
	$pgsettings = array(
		"title" => "No pages!",
		"pageselection" => false,
		"nav" => true,
		"banner" => 1,
		"use_google_analytics" => 1,
	);
	require_once("includes/begin_html.php");
?>
	<p>(This website has no pages to display! Admin user must go add pages in the <a href="administrator/control_panel.php">Control Panel</a> to add content.)</p>
<?php
}
?>

<?php
	require_once("includes/end_html.php");
?>