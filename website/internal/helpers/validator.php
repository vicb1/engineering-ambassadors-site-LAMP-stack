<?php

/*

Contains methods for validating data that was POSTED, to streamline error handling.
Usage: 

  $validator = new Validator();
  $validator->validate_presence("name","username");
  $validator->validate_phone("phone","phone number");

If you wish to use multiple validations, but want it so that it only does the next validation if the previous has not failed: chain them like so:

  $validator->validate_presence("phone","phone number")->validate_phone("phone","phone number");

*/

class Validator
{
  public $msg = "";
  public $err = false;

  //Note: $name corresponds to the name of the post variable, i.e. "phone_num". $desc_name is the string you want displayed on the error 
  //message, i.e. "phone number"
  
  function val_rcs_array($name) {

    if(is_post_set($name)) {
      $rcses = explode(" ",$_POST[$name]);
      foreach($rcses as $rcs) {
        if(!User::find_by_rcs($rcs)) {
          $this->err = true;
          $this->msg .= "Error: rcs $rcs not found<br>";
          return null;
        }
      }
    }
    return $this;
  }

  //Validate the presence of $_POST[$name] (must not be empty)
  function val_str_presence($name,$desc_name) {
    if(!is_post_set($name)) {
      $this->err = true;
      $this->msg .= "Error: field \"$desc_name\" cannot be blank<br>";
      return null;
    }
    return $this;
  }

  //Validate the length of $_POST[$name] (must not be more than max)
  function val_str_max_length($name,$desc_name,$max) {

    if(is_post_set($name)) {
      if(strlen($_POST[$name]) > $max) {
        $this->err = true;
        $this->msg .= "Error: field \"$desc_name\" is too long (maximum " . (string)$max . " characters)<br>";
        return null;
      }
      else { return $this; }
    }
    return null;
  }

  //Validate the length of $_POST[$name] (must not be less than min)
  function val_str_min_length($name,$desc_name,$min) {}

  //Validate the length of $_POST[$name] (must not be between min and max, inclusive)
  function val_str_range($name,$desc_name,$min,$max) {}

  //Validate that $_POST[$name] represents an integer
  function val_integer($name,$desc_name,$min) {}

  //Validate that $_POST[$name] is a phone number
  function val_phone($name,$desc_name) {

  }

  //Validate that $_POST[$name] is an email
  function val_email($name,$desc_name) {

  }

  //Validate that a valid image was uploaded
  function val_img_presence($name,$desc_name) {
    if($_FILES[$name]["error"] > 0) {
      $this->err = true;
      $this->msg .= "Error: upload $desc_name<br>";
      return null;
    }
    else if(!is_img($_FILES[$name]['tmp_name'])) {
      $this->err = true;
      $this->msg .= "Error: file uploaded is not an image<br>";
      return null;
    }
    return $this;
  }

  //Validate that an uploaded image, if it exists, is a valid image with $max_size (in megabytes)
  function val_img_max_size($name,$desc_name,$max_size) {
    if($_FILES[$name]["error"] <= 0) { return $this; }
    if(!is_img($_FILES[$name]['tmp_name'])) {
      $this->err = true;
      $this->msg .= "Error: file uploaded is not an image<br>";
      return null;
    }
    $size = ($_FILES["pic"]["size"] / 1024);
    if($size > $max_size) {
      $this->err = true;
      $this->msg .= "Error: file uploaded is too large (max size is " . $max_size . " megabytes<br>";
      return null;
    }
    return $this;
  }

  //Validate uniqueness of an attribute in a certain model, i.e. $validator->validate_unique("username", "username", "User", "username",2)
  function val_uniqueness($name,$desc_name,$model_name,$attr_name,$model_id) {
    if(!is_post_set($name)){ return null; }
    $model = new $model_name(null);
    $results = $model->find_by($attr_name,$_POST[$name]);
    foreach($results as $result) {
      if($result->id != $model_id) {
        $this->err = true;
        $this->msg .= "Error: a " . lcfirst($model_name) . " already exists with $desc_name \"" . $_POST[$name] . "\"<br>";
        return null;
      }
    }
    return $this;
  }

  function has_errors() { return $this->err; }
  function no_errors() { return !$this->err; }

  function append_msg($msg) {
    $this->msg .= ($msg . "<br>");
  }

  function append_err_msg($msg) {
    $this->msg .= $msg . "<br>";
    $this->err = true;
  }

  //Call this when done with validation
  function set_msg() {
    if($this->err)
      set_error_msg($this->msg);
    else
      set_success_msg($this->msg);

  }

  function get_msg() {
    return $this->msg;
  }

  //Validate the presence of all of a passed array of attributes. Throws error if more than 0 but less than all of them are set, no error otherwise
  //Good for optional date fields with a month/day/year; you want them to fill in none of those fields, or all of them
  function val_strs_presence($names,$desc_names) {

    $total_val = 0;
    $not_val = array();

    for($i=0;$i<count($names);$i++) {
      $name = $names[$i];
      $desc = $desc_names[$i];
      if(!is_post_set($name))
        array_push($not_val,$desc);
    }

    if(count($not_val) > 0 && count($not_val) != count($names)) {
      $this->err = true;
      foreach($not_val as $desc) {
        $this->msg .= "ERROR: field \"$desc\" cannot be blank<br>";
      }
      return null;
    }

    return $this;

  }




}

?>