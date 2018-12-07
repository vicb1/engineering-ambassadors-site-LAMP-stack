<?php

//This PHP file handles authentication of logged-in ambassadors.
require_once Path::lib() . "CAS-1.3.2/CAS.php";

//phpCAS::setDebug("log.txt");

phpCAS::client(CAS_VERSION_2_0,'cas-auth.rpi.edu',443,'/cas');

//This next line is needed to set SSL encryption for a secure connection.
phpCAS::setCasServerCACert(Path::lib() . "CAS-1.3.2/cas-auth.pem");

//Set the user if they're authenticated
if(phpCAS::isAuthenticated())
{
  User::set_current_user(phpCAS::getUser());
}

?>