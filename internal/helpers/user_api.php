<?php

//Grabbing data from APIs can be complicated code-wise, so it's neatly organized into a single class here
//instead of being spread out everywhere in the code-base.

//This class represents the results of a user API query. It's agnostic to the type of API and stores data related to the user
//attributes (i.e. name, title, etc.)

class UserAPI
{
  public $obj;
  public $title;
  public $is_union_api = false;
  public $rcs;

  function __construct(){}

  //Create a user API object from an RCS
  static function create_from_rcs($rcs)
  {
    $instance = new self();
    //If RPI directory app stops working or is unreliable, use the one commented out instead
    $json = file_get_contents('http://rpidirectory.appspot.com/api?q=' . $rcs);
    //$json = file_get_contents('http://api.union.rpi.edu/query.php?task=GetUser&rcsid='. $_POST['rcs'] .'&apikey=' . Config::$API_KEY);
    $decode = json_decode($json);

    foreach($decode->data as $user) {
      if ($user->rcsid == $rcs) {
        $instance->obj = $user;
        break;
      }
    }

    //User wasn't found? Resort to the other API
    if($instance->obj == null) {
      $instance->is_union_api = true;
      $json = file_get_contents('http://api.union.rpi.edu/query.php?task=GetUser&rcsid='. $rcs .'&apikey=' . Config::$API_KEY);
      $decode = json_decode($json);

      if($decode->status->statusCode == 0) {
        $instance->obj = $decode;
        $instance->rcs = $rcs;
      }

    }

    return $instance;
  }

  //Create a user API object from a first and last name pair
  static function create_from_name($first,$last)
  {
    $instance = new self();
    //If RPI directory app stops working or is unreliable, use the one commented out instead
    $json = file_get_contents('http://rpidirectory.appspot.com/api?q=' . $first . '%20' . $last);
    //$json = file_get_contents('http://api.union.rpi.edu/query.php?task=GetUser&rcsid='. $_POST['rcs'] .'&apikey=' . Config::$API_KEY);
    $decode = json_decode($json);

    foreach($decode->data as $user) {
      if (strcasecmp($user->first_name,$first) == 0 && strcasecmp($user->last_name,$last) == 0) {
        $instance->obj = $user;
        break;
      }
    }
    return $instance;
  }

  function is_found() {
    return isset($this->obj);
  }

  function get_rcs_id() { 
    if(!$this->is_union_api)
      return $this->obj->rcsid; 
    else
      return $this->rcs;
  }
  function get_first_name() { 
    if(!$this->is_union_api)
      return $this->obj->first_name;
    else {
      //For Union API, name comes in first + middle + last so need to split it up
      $pieces = explode(" ",$this->obj->result->name);
      return $pieces[0];
    }
  }
  function get_last_name() { 
    if(!$this->is_union_api)
      return $this->obj->last_name; 
    else {
      $pieces = explode(" ",$this->obj->result->name);
      return $pieces[count($pieces)-1];
    }
  }
  function get_email() { 
    if(!$this->is_union_api)
      return $this->obj->email; 
    else {
      return "";
    }
  }
  function get_title() { 
    return $this->title; 
  }

  function get_middle_name() {
    if(!$this->is_union_api) {
      if(isset($this->obj->middle_name))
        return $this->obj->middle_name; 
      else
        return "";
    }
    else {
      $pieces = explode(" ",$this->obj->result->name);
      if(count($pieces) == 3)
        return $pieces[1];
      else
        return "";
    }
  }
  function get_year() {
    if(!$this->is_union_api) {
      if(isset($this->obj->year))
        return $this->obj->year; 
      else
        return "n/a";
    }
    else {
      switch($this->obj->result->class) {
        case "FR": { return "Freshman"; }
        case "SO": { return "Sophomore"; }
        case "JR": { return "Junior"; }
        case "SR": { return "Senior"; }
        default: { return "n/a"; }
      }
      return "n/a";
    }
  }
  function get_major() { 
    if(!$this->is_union_api) {
      if(isset($this->obj->major))
        return $this->obj->major; 
      else
        return "n/a";
    }
    else {
      return "";
    }
  }

  //Can't be found by RPI directory app. Need Union API for this
  function get_rin() {
    $json = file_get_contents('http://api.union.rpi.edu/query.php?task=GetUser&rcsid='. $this->get_rcs_id() .'&apikey=' . Config::$API_KEY);
    $decode = json_decode($json);
    return $decode->result->rin;
  }

}

?>