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
			$error = "No page by this name found. Maybe it was deleted or renamed?";
			$pgsettings = array(
				"title" => "No page by this name found!",
				"pageselection" => "true",
				"nav" => true,
				"banner" => 1,
				"use_google_analytics" => 1,
			);
			require_once("includes/begin_html.php");
		}else{
			if($page['published']==1){
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
					case "Staff":
						$pgsettings = array(
							"title" => $page["name"],
							"pageselection" => "true",
							"nav" => true,
							"banner" => $page['banner'],
							"use_google_analytics" => 1,
						);

						$staffquery="SELECT * FROM `staff` ORDER BY `order`";
						$staffresult=mysqli_query( $connection, $staffquery);

						require_once("includes/begin_html.php");?>

                        <script type="text/javascript" charset="utf-8">
							$(document).ready(function(){
							  $("div[rel^='prettyPhoto']").prettyPhoto();
							});
                        </script>
                        
						<h2><?php echo $page["name"]; ?></h2>
                        

						<?php
						
                        if(mysqli_num_rows($staffresult)!=0){
							$i=0;
                            while($staff=mysqli_fetch_array($staffresult)){
                                $profile_pic = false;
                                $dir = "images/staff/".$staff['id']."/";
                                foreach (scandir($dir) as $item) {
                                    if ($item == '.' || $item == '..' || $item == 'Thumbs.db') continue;
                                    $profile_pic = $item;
                                }
                                ?>
                                <div class="staffBlock" href="#staff<?php echo $i; ?>" rel="prettyPhoto[staff]">
                                    <span><?php if($profile_pic != false){ ?><img src="<?php echo $dir.$item; ?>" width="120" height="120" /><?php }else{echo "[No Picture]";} ?></span>
                                    <span><?php echo $staff['name']; ?></span>
                                </div>
                                <div id="staff<?php echo $i; ?>" style="display:none;">
                                	<span style="float:left; margin-right:10px; display:block; width:300px; height:auto; text-align:center; background-color:#C5C5C5; padding:10px;"><span style="line-height:300px; height:300px; display:block; padding-bottom:5px;"><?php if($profile_pic != false){ ?><img src="<?php echo $dir.$item; ?>" width="300" height="300" /><?php }else{echo "[No Picture]";} ?></span><span style="line-height:50px; height:50px; display:block;"><strong><?php echo $staff['name']; ?></strong></span></span>
                                    <span style="float:left; display:block;"><?php if($staff['role']!=""){ ?><strong>Role: </strong><?php echo $staff['role']; ?><br><?php } ?>
                                	<strong>Bio: </strong><?php if($staff['bio']!=""){echo $staff['bio'];}else{echo '[No bio]';} ?></span>
                                </div>
                        <?php
						$i++;
                            }?>
                        <div class="clear"></div>
                        <?php
                        }else{?>
                        	<p>[No Staff!]</p>
                        <?php
                        }
						
						break;
					case "Custom":
						$pgsettings = array(
							"title" => $page["name"],
							"pageselection" => "true",
							"nav" => true,
							"banner" => $page['banner'],
							"use_google_analytics" => 1,
						);
						
						$form_validation = array();
						
						if($page['forms']!=""){
							$pageforms=unserialize($page['forms']);
							foreach($pageforms as $pg_form){
								$query="SELECT * FROM `forms` WHERE  `id` = {$pg_form}";
								$result=mysqli_query($connection, $query);
								$form=mysqli_fetch_array($result);
								
								if(isset($_POST[$form['u_name']])){
									//print_r($_POST);
									$count=0;
									$form_send = true;
									foreach($_POST as $post_key => $post_val){
										if($post_key != $form['u_name']){
											$field_validators=unserialize($form['field_validators']);
											
											switch($field_validators[$count]){
												case "none":
													$form_validation[$post_key] = true;
													break;
												case "email":
													if(filter_var($_POST[$post_key],FILTER_VALIDATE_EMAIL)){
														$form_validation[$post_key] = true;
														break;
													}else{
														$form_send = false;
														$form_validation[$post_key] = false;
														$error = 'There were errors in the form, please fix them below.';
													}
													break;
												case "notempty":
													if($_POST[$post_key]!=""){
														$form_validation[$post_key] = true;
														break;
													}else{
														$form_send = false;
														$form_validation[$post_key] = false;
														$error = 'There were errors in the form, please fix them below.';
													}
													break;	
											}
											$count++;
										}
									}
									if($form_send == true){
										$email_message = 'Data from form "'.$form['name'].'", submitted at '.$date.'<br/>';
										foreach($_POST as $post_key => $post_val){
											if($post_key != $form['u_name']){
												$email_message .= $post_key.': <b>'.$post_val.'</b><br/>';
											}
										}
										
										$to = $form['email_to'];
										$email_subject = '"'.$form['name'].'" form submission';
										$headers = "From: ".$form['email_from'].PHP_EOL."\r\n";
										$headers .= "MIME-Version: 1.0\r\n";
										$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
										mail ( $to , $email_subject , $email_message , $headers );
									}
								}
							}
						}
						
						require_once("includes/begin_html.php");?>
						
                        <script type="text/javascript" charset="utf-8">
							$(document).ready(function(){
							  $("a[rel^='prettyPhoto']").prettyPhoto();
							});
                        </script>
						
                        <?php
						echo $page['content'];
						
						if($page['forms']!=""){
							$pageforms=unserialize($page['forms']);
							foreach($pageforms as $pg_form){
								$query="SELECT * FROM `forms` WHERE  `id` = {$pg_form}";
								$result=mysqli_query($connection, $query);
								$form=mysqli_fetch_array($result);
								
								$field_names=unserialize($form['field_names']);
								$field_types=unserialize($form['field_types']);
								$field_descs=unserialize($form['field_descs']);
								$field_placeholders=unserialize($form['field_placeholders']);
								$field_maxchars=unserialize($form['field_maxchars']);
								$field_validators=unserialize($form['field_validators']);
								
								$num_fields = count($field_names);
								$count = 0;
							
								?>
								<form method="post" name="<?php echo $form['u_name']; ?>">
								<table border="1" width="100%">
									<?php while($num_fields>$count){
										$maxchars = intval($field_maxchars[$count]);
									?>
									<tr>
										<td style="vertical-align:top;">
										<span class="tooltips"><label for="<?php echo $field_names[$count]; ?>"><?php echo $field_names[$count]; ?>: </label>
										<?php if($field_types[$count] == "text"){?>
											<input name="<?php echo $field_names[$count]; ?>" id="<?php echo $field_names[$count]; ?>"<?php if(isset($form_validation[$field_names[$count]])&&$form_validation[$field_names[$count]]!=true){echo ' style="border:2px solid #9F0002;"';} ?> value="<?php if(isset($_POST[$field_names[$count]])){echo $_POST[$field_names[$count]];} ?>" type="text"<?php if($field_placeholders[$count] != ""){echo ' placeholder="'.$field_placeholders[$count].'"';} ?><?php if($maxchars != ""){echo ' maxlength="'.$maxchars.'"';} ?> /><?php if(isset($form_validation[$field_names[$count]])&&$form_validation[$field_names[$count]]!=true){?><span style="border-radius:5px; background-color:#9F0002; color:#FFFFFF; padding:5px;"><span><?php if($field_validators[$count]=="email"){echo 'Invalid email';}elseif($field_validators[$count]=="notempty"){echo 'Cannot be blank';}?></span></span><?php } ?>
											<?php }elseif($field_types[$count] == "textarea"){ ?>
											<textarea name="<?php echo $field_names[$count]; ?>" id="<?php echo $field_names[$count]; ?>"<?php if(isset($form_validation[$field_names[$count]])&&$form_validation[$field_names[$count]]!=true){echo ' style="border:2px solid #9F0002;"';} ?> rows="15" cols="75"<?php if($field_placeholders[$count] != ""||$maxchars != ""){$placeholder = $field_placeholders[$count]; if($maxchars != ""){$placeholder.=' (Max. '.$maxchars.' characters)';} echo ' placeholder="'.$placeholder.'"';} ?><?php if($maxchars != ""){echo ' maxlength="'.$maxchars.'"';} ?>><?php if(isset($_POST[$field_names[$count]])){echo $_POST[$field_names[$count]];} ?></textarea><?php if(isset($form_validation[$field_names[$count]])&&$form_validation[$field_names[$count]]!=true){?><span style="border-radius:5px; background-color:#9F0002; color:#FFFFFF; padding:5px;"><span><?php if($field_validators[$count]=="email"){echo 'Invalid email';}elseif($field_validators[$count]=="notempty"){echo 'Cannot be blank';}?></span></span><?php } ?>
											<?php } ?>
                                        <?php if($field_descs[$count]!=""){echo "<span>".$field_descs[$count]."</span>";}?></span>
										</td>
									</tr>
									<?php
									$count++;
									} ?>
									<tr>
										<td>
											<input type="submit" name="<?php echo $form['u_name']; ?>" value="<?php echo $form['submit_value']; ?>" />
										</td>
									</tr>
								</table>
								</form>
								<?php
							}
						}
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
											gallery("galleries/".$pagegallery['name']."/gallery/", "galleries/".$pagegallery['name']."/gallery-thumbs/", 200, 200, $gallcount);
											if($pagegallery['subgalleries']!=""){
												$subgalleries = unserialize($pagegallery['subgalleries']);
												foreach($subgalleries as $subgalleryid){
													$query="SELECT * FROM `galleries` WHERE id={$subgalleryid}";
													$result=mysqli_query( $connection, $query);
													$subgallery=mysqli_fetch_array($result);
													?>
													<h2><?php echo $subgallery['name'] ?></h2>
													<?php
													gallery("galleries/".$subgallery['name']."/gallery/", "galleries/".$subgallery['name']."/gallery-thumbs/", 200, 200, $gallcount);
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
									gallery("galleries/".$onegallery['name']."/gallery/", "galleries/".$onegallery['name']."/gallery-thumbs/", 200, 200, 'gall');
									if($onegallery['subgalleries']!=""){
										$subgalleries = unserialize($onegallery['subgalleries']);
										foreach($subgalleries as $subgalleryid){
											$query="SELECT * FROM `galleries` WHERE id={$subgalleryid}";
											$result=mysqli_query( $connection, $query);
											$subgallery=mysqli_fetch_array($result);
											?>
											<h2><?php echo $subgallery['name'] ?></h2>
											<?php
											gallery("galleries/".$subgallery['name']."/gallery/", "galleries/".$subgallery['name']."/gallery-thumbs/", 200, 200, 'gall');
										}
										
									}
								}
								 ?>
							<?php
						break;
					}
				}
			}else{
				$pgsettings = array(
					"title" => "Unpublished",
					"pageselection" => false,
					"nav" => true,
					"banner" => 1,
					"use_google_analytics" => 1,
				);
				require_once("includes/begin_html.php");
				?><p>(You cannot view this page, it isn't published.)</p><?php
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