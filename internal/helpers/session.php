<?php

//This file contains code for managing sessions.

if(!defined("session_status")) {
  if(session_id() == '')
    session_start();
}

else if (session_status() == PHP_SESSION_NONE)
  session_start();

//Totally destroy the session. Use when logging the user out.
function clear_session()
{
  // Unset all of the session variables.
  $_SESSION = array();

  // If it's desired to kill the session, also delete the session cookie.
  // Note: This will destroy the session, and not just the session data!
  if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000,
          $params["path"], $params["domain"],
          $params["secure"], $params["httponly"]
      );
  }

  // Finally, destroy the session.
  session_destroy();
}

//CSRF Protection
/*Usage: put <?php echo csrf_input(); ?> into any forms you want protected from csrf
  Then, at the POST detection in that page, make sure that csrf_tokens_match() is true before proceeding.
*/
function csrf_tokens_match() {
  if(!get_csrf_token()){ return false; }
  if(!isset($_POST['csrf_token'])){ return false; }
  return get_csrf_token() == $_POST['csrf_token'];
}
function csrf_input() {
  return '<input type="hidden" name="csrf_token" value="'.get_csrf_token().'" />';
}
function set_csrf_token() {
  $_SESSION['csrf_token'] = md5(uniqid(rand(), TRUE));
  return $_SESSION['csrf_token'];
}
function get_csrf_token() {
  if(!isset($_SESSION['csrf_token']))
    return null;
  return $_SESSION['csrf_token'];
}
function clear_csrf_token() {
  $_SESSION['csrf_token'] = null;
}

/* MESSAGE SESSION */

//Error messages
function set_error_msg($msg){ $_SESSION['error'] = $msg; }
function get_error_msg() { 
  if(!isset($_SESSION['error']))
    return null;
  return $_SESSION['error']; 
}
function clear_error_msg(){ $_SESSION['error'] = null; }

//Success messages
function set_success_msg($msg){ $_SESSION['success'] = $msg; }
function get_success_msg() {
  if(!isset($_SESSION['success'])) 
    return null;
  return $_SESSION['success'];  
}
function clear_success_msg(){ $_SESSION['success'] = null; }

//Notice messages
function set_notice($msg){ $_SESSION['notice'] = $msg; }
function get_notice() {
  if(!isset($_SESSION['notice']))
    return null;
  return $_SESSION['notice'];
}
function clear_notice(){ $_SESSION['notice'] = null; }

//Clear all the messages.
function clear_all_msgs() 
{
  clear_error_msg();
  clear_success_msg();
  clear_notice();
}

//Display error, success or clear, depending on which was set
function display_msgs() {

  $err_msg = get_error_msg();
  $success_msg = get_success_msg();
  $notice = get_notice();

  if($err_msg) {
    echo "<span class='msg' style='color:red'><i>" . $err_msg . '</i></span>'; 
  }
  elseif($success_msg) {
    echo "<span class='msg' style='color:green'><i>" . $success_msg . '</i></span>';  
  }
  elseif($notice) {
    echo "<span class='msg' style='color:black'><i>" . $notice . '</i></span>';  
  }

}

/* DEBUG SESSION */

function set_debug_msg($msg) {
  $_SESSION['debug'] = $msg;
}

function debug($msg) {
  if(!isset($_SESSION['debug'])) {
    set_debug_msg($msg);
  }
  else {
    $_SESSION['debug'] .= ($msg . "<br>");
  }
}

function get_debug_msg() {
  if(isset($_SESSION['debug']))
    return $_SESSION['debug'];
  else
    return "";
}

function clear_debug_msg() {
  unset($_SESSION['debug']);
}

/* STICKY FORM SESSION */

function set_sticky($name,$value) {
  if(!isset($_SESSION['sticky'])) {
    $_SESSION['sticky'] = array();
  }
  $_SESSION['sticky'][$name] = serialize($value);
}

function get_sticky($name) {
  if(!isset($_SESSION['sticky'])) { return null; }
  return unserialize($_SESSION['sticky'][$name]);
}

function clear_sticky() {
  if(!isset($_SESSION['sticky'])) { return; }
  unset($_SESSION['sticky']);
}


?>