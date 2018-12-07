<?php

//Put convenient helper functions here.

function active_str($page_name)
{
  if(basename($_SERVER['PHP_SELF']) == $page_name) 
  { 
    return "active"; 
  }
  return "";
}

function int_to_month($month_int)
{
  return date("F", mktime(0, 0, 0, $month_int, 10));
}

//Is the user an admin?
function is_user_admin()
{
  return User::get_current_user() && User::get_current_user()->is_admin;
}

//Does the current user have authority over the user given?
function has_auth_over($user)
{
  $rcs_id = $user->rcs_id;
  //return true; //Only uncomment for testing locally, otherwise anyone can edit anyone's profiles!
  if(User::get_current_user() == null){ return false; }
  elseif(User::get_current_user()->rcs_id == $rcs_id){ return true; }
  elseif(User::get_current_user()->is_admin == true){ return true; }
  return false;
}

//Does the current user have authority over the array of users given? (Only need to match one)
function has_auth_overs($users)
{
  foreach($users as $user) {
    if(has_auth_over($user)) { return true; }
  }
  return false;
}

//Is the current user someone else who has authority over this rcs_id?
function is_diff_auth_over($user)
{
  $rcs_id = $user->rcs_id;
  return has_auth_over($user) && (User::get_current_user() && User::get_current_user()->rcs_id != $rcs_id);
}

//Is the current user someone else who has authority over the other users?
function is_diff_auth_overs($users)
{
  foreach($users as $user) {
    if(!is_diff_auth_over($user)) { return false; }
  }
  return true;
}

//Convert a date to a string
function date_to_str($date)
{
  if($date == null){ return null; }
  return $date->format(Config::$DATE_FORMAT);
}

//Convert a string to a date
function str_to_date($s)
{
  if($s == null || $s == ""){ return null; }
  return date_create_from_format(Config::$DATE_FORMAT, $s);
}

//Convert a datetime to a string
function datetime_to_str($date)
{
  if($date == null){ return null; }
  return $date->format(Config::$DATETIME_FORMAT);
}

//Convert a string to a datetime
function str_to_datetime($s)
{
  if($s == null || $s == ""){ return null; }
  return date_create_from_format(Config::$DATETIME_FORMAT, $s);
}

//Format a datetime in a neat, user-friendly way
function format_datetime($datetime)
{
  return $datetime->format(Config::$USER_FRIENDLY_DATETIME);
}

//Return how many seconds, minutes, hours, days, etc. ago that $datetime was
function time_since($datetime)
{
  return format_datetime($datetime);
}

//Return true if file is image, false otherwise
function is_img($file)
{ 
  return exif_imagetype($file);
}

function create_dir($path) 
{
  if (!file_exists($path)) {
      mkdir($path, 0777, true);
  }
}

//Redirect to the page user is on.
//Do this after a post where post processing is same page. That way, pressing back won't give 
//"Confirm Form Submission" messages which ruin user experience
function redirect_to_self()
{
  header("Location: " . $_SERVER['REQUEST_URI']);
  die();
}

//Redirect after a post, but this time to a custom php file.
function redirect_to($php_file)
{
  header("Location: " . $php_file);
  die();  
}

//Redirect to previous page visited
function redirect_to_prev()
{
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  die();
}

function print_pdo_errors($sql,$dbh)
{
  echo "The following query caused error: <br>" . $sql . "<br>"; 
  echo "\nPDO::errorInfo():\n";
  print_r($dbh->errorInfo());
}

function display_if_true($str,$condition)
{
  if($condition) {
    echo $str;
  }
  else {
    echo "";
  }
}

function delete_dir($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!delete_dir($dir.DIRECTORY_SEPARATOR.$item)) return false;
    }
    return rmdir($dir);
}

function is_post_set($post_name) {
  if(!isset($_POST[$post_name]) || $_POST[$post_name] == "") { return false; }
  return true;
}

//Take in an array of users, then get their rcs ids
function get_users_rcs($users) {
  $to_ret = array();
  foreach($users as $user) {
    array_push($to_ret,$user->rcs_id);
  }
  return $to_ret;
}

//Take an array of strings, then output an HTML list
function strs_to_html($arr) {
  $html = "";
  $html .= "<ul>";
  foreach($arr as $str) {
    $html .= "<li>$str</li>";
  }
  $html .= "</ul>";
  return $html;
}

?>