<?php
//This script automatically adds all new officers to the database (existing ones detected won't be changed).
//It also updates ALL users that currently exist.

require_once 'internal/helpers/includes_no_header.php';

if(!is_user_admin()) { 
  header('HTTP/1.0 401 Unauthorized'); echo("401 UNAUTHORIZED"); die(); 
}

$json = file_get_contents('http://api.union.rpi.edu/query.php?task=GetOrganization&id='. Config::$CLUB_ID . '&apikey=' .
  Config::$API_KEY);

$decode = json_decode($json);

//Grab all officers
foreach($decode->result->officers as $officer) {

  $pieces = explode(" ",$officer->user_name);

  $first_name = $pieces[0];
  $last_name = $pieces[1];

  $user_api = UserAPI::create_from_name($first_name,$last_name);

  if(!$user_api->is_found()) {
    echo "Error: could not find user \"" . $first_name . " " . $last_name . "\"<br>";
    continue; 
  }

  $user_api->title = $officer->title;

  $result = User::create_member($user_api, true); //false means we're creating regular non-privilidged member

  if($result == 0) {
    echo "Successfully added " . htmlspecialchars($user_api->get_rcs_id()) . " to database.<br>";
  }
  elseif($result == 1) {
    echo htmlspecialchars($user_api->get_rcs_id()) . " is already in database. Updating...<br>";
  }
  elseif($result == 2) {
    echo "Error: " . htmlspecialchars($user_api->get_rcs_id()) . " could not be added.<br>";
  }

}

?>