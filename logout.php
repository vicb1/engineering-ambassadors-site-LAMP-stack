<?php

require_once "internal/helpers/includes_no_header.php";

if(phpCAS::isAuthenticated())
{
	phpCAS::logoutWithUrl("http://engineeringambassadors.union.rpi.edu/");
}
else
{
	header('location: index.php');
}

?>