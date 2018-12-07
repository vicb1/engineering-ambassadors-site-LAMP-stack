<?php
/* Put anything you will be writing out to HTML here. */

//Generates the dropdown menu of all users with a certain id.
function user_dropdown($sel_user) {
  $str = "";

  if($sel_user == null) {
    $str .= "<select class='user_select'>";
  }
  else {
    $str .= "<select class='user_select' name='$sel_user->id'>";
  }

  $str .= "<option></option>";

  foreach(User::all_current(null,true) as $user) {
    $val = $user->id;
    $sel = ($sel_user && $sel_user->id == $user->id) ? "selected" : "";
    $str .= "<option value='$val' $sel>".$user->get_name()."</option>";
  }

  $str .= "</select>";

  return $str;

}

?>