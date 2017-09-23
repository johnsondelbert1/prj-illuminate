<div class="navbar-fixed">
<nav class="nav-extended">
<div class="nav-wrapper">

  <a href="#" data-activates="slide-out" class="button-collapse show-on-large"><i class="material-icons">menu</i></a>
  <ul class="right">
<?php if(check_permission(array("Uploading;upload_files","Uploading;delete_files","Uploading;create_folders","Uploading;rename_folders","Uploading;delete_folders"))){?>
  <li><a href="#" class='btn dropdown-button dropdown-button2' data-activates='upload'><i class="material-icons">&#xE2C6;</i></a></li>
  <ul id='upload' class='dropdown-content'>
    <li><div class="">
      <form action="/file-upload"
        class="dropzone"
        id="my-awesome-dropzone"></form>
    </div></li>


  </ul>
  <?php } ?>
  <?php if(check_permission(array("Website;edit_site_settings","Website;upload_favicon_banner","Website;edit_google_analytics","Website;edit_socnet"))){?>
  <li><a href="site-settings.php"><i class="material-icons">&#xE8B8;</i></a></li>
  <?php } ?>
</ul>
</div>
<div class="nav-content">
<span class="nav-title"><?= $pgsettings['title']; ?></span>
<?php

if (in_array("btn", $pgsettings))
  {
  echo '<a href="'; ?> <?= $pgsettings["btn"]; ?> <?= '" class="btn-floating btn-large halfway-fab waves-effect waves-light teal">
    <i class="material-icons">add</i>
  </a>';
  }
else
  {
  echo ".";
  }
?>
</div>
<ul id="slide-out" class="side-nav">
<li><a href="index.php"><i class="material-icons">&#xE871;</i>Dashboard</a></li>
<?php if(check_permission("Users","approve_deny_new_users") && $GLOBALS['site_info']['user_creation'] == 'approval'){?>
<li><a href="approval-list">Approve/Deny<?php if($pending_users > 0){echo '<span class="red new badge">'.$pending_users.'</span>';} ?></a></li>
<?php } ?>
<?php if(check_permission(array("Pages;add_pages","Pages;edit_pages","Pages;delete_pages"))){?>
<li class="no-padding">
  <ul class="collapsible collapsible-accordion">
    <li>
      <a class="collapsible-header">Pages<i class="material-icons">arrow_drop_down</i></a>
      <div class="collapsible-body">
        <ul>
        <li><a href="edit_page.php?action=newpage"><i class="material-icons">people</i>New</a></li>
          <li><a href="page_list.php"><i class="material-icons">settings</i>Edit</a></li>
          <li><a href="staff-list.php"><i class="material-icons">people</i>Staff</a></li>

        </ul>
      </div>
    </li>
  </ul>
</li>
<?php } ?>
<?php if(check_permission(array("Galleries;add_gallery","Galleries;edit_gallery","Galleries;delete_gallery","Galleries;rename_gallery","Sliders;add_slider","Sliders;edit_slider","Sliders;delete_slider","Sliders;rename_slider",))){?>
<li class="no-padding">
  <ul class="collapsible collapsible-accordion">
    <li>
      <a class="collapsible-header">Images<i class="material-icons">arrow_drop_down</i></a>
      <div class="collapsible-body">
        <ul>
          <?php if(check_permission(array("Galleries;add_gallery","Galleries;edit_gallery","Galleries;delete_gallery","Galleries;rename_gallery"))){?>
          <li><a href="gallery-list.php"><i class="material-icons">&#xE3B6;</i>Gallery</a></li>
          <?php } ?>
          <?php if(check_permission(array("Sliders;add_slider","Sliders;edit_slider","Sliders;delete_slider","Sliders;rename_slider"))){?>
          <li><a href="slider-list.php"><i class="material-icons">slideshow</i>Slider</a></li>
          <?php } ?>
          <?php if(check_permission("Website","upload_favicon_banner")){ ?>
          <li><a href="site-settings.php?tab=2#"><i class="material-icons">settings</i>Settings</a></li>
          <?php } ?>
        </ul>
      </div>
    </li>
  </ul>
</li>
<?php } ?>
<?php if(check_permission(array("Forms;create_form","Forms;edit_form","Forms;delete_form"))){?>
<li><a href="form-list.php"><i class="material-icons">chrome_reader_mode</i>Forms</a></li>
<?php } ?>
<!--<?php if(check_permission(array("Uploading;upload_files","Uploading;delete_files","Uploading;create_folders","Uploading;rename_folders","Uploading;delete_folders"))){?>
<li><a href="upload-files.php"><i class="material-icons">&#xE2C6;</i>Upload</a></li>
<?php } ?>-->
<?php if(check_permission(array("Forum;add_delete_forum","Forum;edit_forum"))){?>
<li><a href="list-forums"><i class="material-icons">&#xE0BF;</i>Forums</a></li>
<?php } ?>
<?php if(check_permission(array("Calendars;add_delete_calendar","Calendars;add_event","Calendars;delete_event"))){?>
<li><a href="list-calendars"><i class="material-icons">today</i>Calendars</a></li>
<?php } ?>
<?php if(check_permission("Website","edit_site_colors")){ ?>
<li><a href="edit-colors">Theme<i class="material-icons">&#xE40A;</i></a></li>
<?php } ?>
<?php if(check_permission(array("Users;add_users","Users;delete_users","Users;create_rank","Users;edit_rank","Users;delete_rank","Users;approve_deny_new_users"))){?>
<li class="no-padding">
  <ul class="collapsible collapsible-accordion">
    <li>
      <a class="collapsible-header">Users<i class="material-icons">arrow_drop_down</i><i class="material-icons">&#xE853;</i></a>
      <div class="collapsible-body">
        <ul>
          <?php if(check_permission(array("Users;add_users","Users;delete_users"))){?>
          <li><a href="accounts.php">Edit</a></li>
          <?php } ?>
          <?php if(check_permission(array("Users;create_rank","Users;edit_rank","Users;delete_rank"))){?>
          <li><a href="ranks.php">Permissions</a></li>
          <?php } ?>
          <?php if(check_permission("Website","edit_site_colors")){ ?>
          <li><a href="user-settings">User Settings</a></li>
          <?php } ?>
        </ul>
      </div>
    </li>
  </ul>
</li>
<?php } ?>
<!--<?php if(check_permission(array("Website;edit_site_settings","Website;upload_favicon_banner","Website;edit_google_analytics","Website;edit_socnet"))){?>
<li><a href="site-settings.php"><i class="material-icons">&#xE8B8;</i>Settings</a></li>
<?php } ?>-->
<li><a href="../"><i class="material-icons">&#xE5C4;</i>Back to site</a></li>
<li><a href="logout.php"><i class="material-icons">&#xE879;</i>Logout</a></li>
</ul>

</nav>
</div>
