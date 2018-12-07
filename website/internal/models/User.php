<?php

//A User model represents a member of the Engineering Ambassadors
Class User extends Model
{
  public $rcs_id;
  public $rin;
  public $title;
  public $year;
  public $major;
  public $email;
  public $first_name;
  public $middle_name;
  public $last_name;
  public $entry_date;
  public $grad_date;
  public $is_admin;
  public $is_disabled;
  public $img_takedown_msg;
  public $img_path;
  public $favorite_quote;
  public $hometown;

  //Must be defined in every model
  public static function get_table_name(){ return "users"; }
  
  //Callbacks  
  function on_create() { 
    $this->create_dirs();
    if($this->email == null || $this->email == "") {
      $this->email = $this->rcs_id . "@rpi.edu";
      $this->save(); 
    }
  }

  /*---------------------------------------*/
  /*CODE RELATED TO CURRENT USER IN SESSION*/
  /*---------------------------------------*/

  private static $USER;

  public static function get_current_user() { return self::$USER; }
  public static function set_current_user($rcs){ self::$USER = self::find_by_rcs($rcs); }
  public static function is_logged_in(){ return self::$USER != null; }
  public static function get_current_rcs_id(){ return self::$USER != null ? self::$USER->rcs_id : ""; }

  /*---------------------------------------*/
  /*CODE RELATED TO IMAGE UPLOADING*/
  /*---------------------------------------*/

  private function get_upload_path() { return "uploads/users/". $this->rcs_id . "/"; }
  private function get_upload_pic_path() { return $this->get_upload_path() . "profile_picture/"; }
  public function get_upload_pres_path() { return $this->get_upload_path() . "presentations/"; }

  //Upload a profile picture. Will replace the existing one if it exists.
  public function upload_pic($file_identifier)
  {
    $file = $_FILES[$file_identifier]["tmp_name"];
    $name = $_FILES[$file_identifier]["name"];

    $this->remove_pic(false,"");
    $new_path = $this->get_upload_pic_path() . $name;
    move_uploaded_file($file, $new_path);
    $this->img_path = $new_path;
    $this->img_takedown_msg = null;
    $this->save();
  }

  //Take down a profile picture. Set $by_admin to true to specify it was someone else that took it down.
  public function remove_pic($by_admin,$desc)
  {
    if(!$this->img_path || !file_exists($this->img_path)){ return; }
    unlink($this->img_path);
    $this->img_path = null;
    if($by_admin) {
      $this->img_takedown_msg = $desc;
    }
    $this->save();
  }

  public function has_profile_pic()
  {
    return $this->img_path && file_exists($this->img_path);
  }

  //Get path to the user's profile picture. If it doesn't exist, return the path to default picture.
  public function get_pic_path()
  {
    if(!$this->img_path || !file_exists($this->img_path)) {
      return Config::$NO_PROF_PIC_PATH;
    }
    else {
      return $this->img_path;
    }
  }

  //Create the user's directory system
  function create_dirs() {
    create_dir($this->get_upload_path());
    create_dir($this->get_upload_pic_path());
    create_dir($this->get_upload_pres_path());
  }

  /*---------------------------------------*/
  /*CODE RELATED TO SORTING/ORDERING USERS*/
  /*---------------------------------------*/

  public function order_by_name($a, $b)
  {
    if($a->first_name == $b->first_name){ return 0; }
    return ($a->first_name > $b->first_name) ? 1 : -1; 
  }

  public function order_by_position($a, $b)
  {
    $a_rank = self::get_position_rank($a->title);
    $b_rank = self::get_position_rank($b->title);

    if($a_rank == $b_rank){ return 0; }
    return ($a_rank > $b_rank) ? 1 : -1;
  }

  //Define position ranks here (lower number indicates higher rank)
  public static function get_position_rank($pos)
  {
    if($pos == "President")                 { return 0; }
    if($pos == "VP - Events Coordinator")   { return 1; }
    if($pos == "Secretary")                 { return 2; }
    if($pos == "Treasurer")                 { return 3; }
    if($pos == "Secretary Elect")           { return 4; }
    if($pos == "Treasurer Elect")           { return 5; }
    if($pos == "Recruitment Chair")         { return 6; }
    if($pos == "EC - Outreach Elect")       { return 7; }
    if($pos == "Recruitment Chair - Elect") { return 8; }
    if($pos == "EC - School Visits Elect")  { return 9; }

    return 100;
  }

  /*---------------------------------------*/
  /*CODE RELATED TO USER DATA*/
  /*---------------------------------------*/

  public function get_ahref()
  {
    $ret_str = "<a href='member_view.php?id=" . $this->rcs_id . "'>". htmlspecialchars($this->get_name()) . "</a>";
    return $ret_str;
  }

  //Make sure you always use htmlspecialchars() on any third-party user input you're going to render to the HTML, otherwise
  //you are vulnerable to an XSS (cross-site scripting) attack!
  public function get_name()
  {
    return htmlspecialchars(ucwords($this->first_name . " " . $this->last_name));
  }
  public function get_year()
  {
    return htmlspecialchars(ucwords($this->year));
  }
  public function get_major()
  {
    if($this->major != null) { return htmlspecialchars(ucwords($this->major)); }
    return "";
  }
  public function get_title()
  {
    return htmlspecialchars(ucwords($this->title));
  }
  public function get_favorite_quote()
  {
    if($this->favorite_quote == null){ return "(Not specified)"; }
    return htmlspecialchars($this->favorite_quote);
  }
  public function get_hometown()
  {
    if($this->favorite_quote == null){ return "(Not specified)"; }
    return htmlspecialchars($this->hometown);
  }
  public function get_class()
  {
    if($this->grad_date != null) {
      $date = str_to_date($this->grad_date);
      return "Class of " . $date->format("Y");
    }
    return "";
  }
  public function is_aluminus()
  {
    if($this->grad_date != null && str_to_date($this->grad_date) < (new DateTime()) ) { return true; }
    else { return false; }
  }

  /*---------------------------------------*/
  /*CODE RELATED TO QUERYING USERS*/
  /*---------------------------------------*/
  
  //Return an array of this user's jobs
  public function get_jobs() {

    $ret_arr = array();
    $jobs = UserToJob::find_by("user_id",$this->id);

    foreach($jobs as $job) {
      array_push($ret_arr,$job);
    }
    return $ret_arr;
  }

  //Return an array of this user's extracurricular activities
  public function get_ecas() {

    $ret_arr = array();
    $ecas = UserToECA::find_by("user_id",$this->id);

    foreach($ecas as $eca) {
      array_push($ret_arr,$eca);
    }
    return $ret_arr;
  }

  public function update_jobs($jobs) {
    $jobs = array_unique($jobs);
    $links = UserToJob::find_by("user_id",$this->id);
    foreach($links as $link) {
      $link->delete();
    }
    foreach($jobs as $job) {
      if($job == null || $job == ""){ continue; }
      UserToJob::create_entry($this->id,$job);
    }
  }

  public function update_ecas($ecas) {
    $ecas = array_unique($ecas);
    $links = UserToECA::find_by("user_id",$this->id);
    foreach($links as $link) {
      $link->delete();
    }
    foreach($ecas as $eca) {
      if($eca == null || $eca == ""){ continue; }
      UserToECA::create_entry($this->id,$eca);
    }
  }

  public function get_presentations() {
    $preses = [];
    $dbo = DB::get_dbo();
    $dbh = $dbo->prepare("SELECT * FROM user_to_pres
      INNER JOIN presentations ON user_to_pres.pres_id = presentations.id
      WHERE user_to_pres.user_id = ?");

    $dbh->execute(array($this->id)) or die('Failed to run get_users query for presentation');
    $result = $dbh->fetchAll(PDO::FETCH_OBJ);

    foreach($result as $obj) {
      array_push($preses,new Presentation($obj));
    }
    return $preses;
  }

  //Retrieve all CURRENT users from the database. They must not be alumini, and they must not be disabled.
  //Set $order_by (string) to the method name you wish to use for comparison operator, or leave it blank for default (ABC name)
  //if $include_special is on, will include "special" roles such as Advisor and Webmaster
  public static function all_current($order_by, $include_special)
  {
    $users = User::all();
    $ret_arr = [];

    foreach($users as $user) {

      if(!$include_special && ($user->title == "Advisor" || $user->title == "Webmaster")){ continue; }
      if(!$user->is_disabled && !$user->is_aluminus()) {
        array_push($ret_arr,$user);
      }
    }

    if($order_by != null && $order_by != "") {
      usort($ret_arr,array("User", $order_by));
    }

    return $ret_arr;
  }

  //Find a user by rcs id
  public static function find_by_rcs($rcs)
  {
    $dbo = DB::get_dbo();

    $dbh = $dbo->prepare('SELECT * FROM users WHERE rcs_id = ?');
    $dbh->execute(array($rcs)) or die('Failed to run <b>find_by_rcs</b> query');
    $result = $dbh->fetchObject();

    if($result != null)
      return new User($result);
    else
      return null;
  }

  //This will create a member from a UserAPI class
  //  Returns 0 if success, 1 if already added, 2 if query failed for some reason
  public static function create_member($user_api, $is_officer)
  {
    $dbo = DB::get_dbo();

    //We first check if they already exist.
    $dbh = $dbo->prepare('SELECT * FROM users WHERE rcs_id = ?');
    $dbh->execute(array($user_api->get_rcs_id()));
    $res = $dbh->fetchObject();

    $title = "Member";

    if($is_officer) { 
      $title = $user_api->title;
    }

    //If they exist in the db, just update them.
    if($res != null)
    {
      //Update their title and year
      $user = User::find_by_rcs($user_api->get_rcs_id());
      $user->title = $title;
      $user->year = $user_api->get_year();

      if(!$user->save()){ echo "Something went wrong with updating the member below<br>"; }

      return 1;
    }

    //If they don't exist, create a new user.
    $user = new User(null);

    $user->rcs_id = $user_api->get_rcs_id();
    $user->rin = $user_api->get_rin();
    $user->title = $title;
    $user->year = $user_api->get_year();
    $user->major = $user_api->get_major();
    $user->email = $user_api->get_rcs_id() . "@rpi.edu";
    $user->first_name = $user_api->get_first_name();
    $user->middle_name = $user_api->get_middle_name();
    $user->last_name = $user_api->get_last_name();
    $user->is_admin = 0;
    $user->is_disabled = 0;
    
    if($user->save())
      return 0;
    else
      return 2;
  }

}

?>