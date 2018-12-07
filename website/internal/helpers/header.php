<?php
//This PHP file includes the header HTML for the whole site. It can change depending on whether user is logged
//in or not, as well as their privilidges

//Create a different token for each page to protect against CSRF attacks.
set_csrf_token();

$new_title = "";

if(isset($title))
  $new_title = $title . " | RPI Engineering Ambassadors";
else 
  $new_title = "RPI Engineering Ambassadors | Find your passion and engineer it!";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" xmlns:fb="http://ogp.me/ns/fb#">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $new_title; ?></title>
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/bootstrap-responsive.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>

  <?php echo get_debug_msg(); ?>

  <div id="head">

    <div id="head_cen">
      <div id="head_sup" class="head_height">
        <ul>
          <li><a class="<?php echo active_str('index.php'); ?>" href="index.php">Home</a></li>
          <li><a class="<?php echo active_str('about.php'); ?>" href="about.php">ABOUT US</a></li>
          <li><a class="<?php echo active_str('member_all.php'); ?>" href="member_all.php">MEMBERS</a></li>
          <li><a class="<?php echo active_str('presentation_all.php'); ?>" href="presentation_all.php">PRESENTATIONS</a></li>
          <li><a class="<?php echo active_str('schools.php'); ?>" href="schools.php">SCHOOLS</a></li>
          <?php if (User::is_logged_in()): ?>
            <li><a class="<?php echo active_str('calendar.php'); ?>" href="calendar.php">INTERNAL CALENDAR</a></li>    
          <?php endif; ?> 
          <li><a class="<?php echo active_str('sponsors.php'); ?>" href="sponsors.php">SPONSORS</a></li>
          <li><a class="<?php echo active_str('contact.php'); ?>" href="contact.php">CONTACT</a></li>

        </ul>
      </div>
    </div>
    
    <div id = "divide">
      <p align="right" style="float:right; width:300px; margin-top:-120px; margin-right:10px;">
      <?php if(!phpCAS::isAuthenticated()): ?>
          <!--<a href="https://sharepoint.rpi.edu/core/IED/EngAmb/default.aspx">Ambassador Login</a>-->
          <!--<a href="register.php">Register</a>&nbsp|-->
          <a href="login.php">Ambassador Login</a>
      <?php else: ?>
        Logged in as <b><?php echo User::get_current_user()->rcs_id ?></b>
        <?php if(is_user_admin()): ?>
          <br><a href="register.php">Register new user</a>  
        <?php endif; ?>
        <br><a href="member_view.php?id=<?php echo User::get_current_user()->rcs_id ?>">View Profile</a>
        <br><a href="member_edit.php?id=<?php echo User::get_current_user()->rcs_id ?>">Edit Profile</a>
        <br><a href="logout.php">Logout</a>
        
      <?php endif; ?>
      </p>
    </div>

  </div>
