<?php

require_once "internal/helpers/includes_no_header.php";

//phpCAS::logout(); die();

if(!phpCAS::isAuthenticated())
{
	phpCAS::forceAuthentication();
}
else
{
  $rcs = phpCAS::getUser();

  //Clear out phpCAS session variables from local PHP session
  //This will clear out what your application thinks about user login

  if(User::find_by_rcs($rcs) == null)
  {
    //clear_session();
    //header("location: invalid_login.php");
    phpCAS::logoutWithUrl("http://engineeringambassadors.union.rpi.edu/invalid_login.php");
  }
  else
  {
    header('location: index.php');
  }
}

?>