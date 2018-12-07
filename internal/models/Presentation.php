<?php

Class Presentation extends Model
{
  public $name;
  public $description;
  public $created_at;
  public $updated_at;
  public $img_takedown_msg;
  public $img_path;

  private $creator;

  public function update_users($users) {
    
    //Delete all UserToPres with current presentation id
    $links = UserToPres::find_by("pres_id",$this->id);
    foreach($links as $link) {
      $link->delete();
    }
    foreach($users as $user) {
      $this->add_user($user->id);
    }

  }

  public function add_users($users) {
    foreach($users as $user) {
      $this->add_user($user->id);
    }
  }

  //On deletion, we must remove the display image.
  public function on_delete() {
    $this->remove_dir();
  }

  //Must be defined in every model
  public static function get_table_name(){ return "presentations"; }

  public function get_name() {
    return htmlspecialchars($this->name);
  }

  public function get_description() {
    return htmlspecialchars($this->description);
  }

  public function get_ahref() {
    $ret_str = "<a href='presentation_view.php?id=" . $this->id . "'>". htmlspecialchars($this->get_name()) . "</a>";
    return $ret_str;
  }

  public function get_created_at() {
    return time_since(str_to_datetime($this->created_at));
  }

  public function get_updated_at() {
    if($this->updated_at == null){ return "(Never updated)"; }
    return time_since(str_to_datetime($this->updated_at));
  }

  public function get_users()
  {
    $users = [];
    $dbo = DB::get_dbo();
    $dbh = $dbo->prepare("SELECT * FROM user_to_pres
      INNER JOIN users ON user_to_pres.user_id = users.id
      WHERE user_to_pres.pres_id = ?");

    $dbh->execute(array($this->id)) or die('Failed to run get_users query for presentation');
    $result = $dbh->fetchAll(PDO::FETCH_OBJ);

    foreach($result as $obj) {
      array_push($users,new User($obj));
    }
    return $users;
  }

  //Return an html string of users
  public function get_users_href() {
    $users = $this->get_users();
    $ret_str = "";
    foreach($users as $index=>$user) {
      $ret_str .= $user->get_ahref();
      if($index != count($users)-1) {
        $ret_str .= " , ";
      }
    }
    return $ret_str;
  }

  //Add a user to this presentation
  public function add_user($id) {
    UserToPres::create_entry($id,$this->id);
  }

  /*
  //Takes in an array of RCS ids, then sets all the users.
  public function add_users($arrs)
  {
    error_log(var_dump($arrs));
    $no_dups = array_unique($arrs); //Strip out duplicate RCS ids
    foreach($no_dups as $rcs) {
    $user = User::find_by_rcs($rcs);
      UserToPres::create_entry($user->id,$this->id);
    }
  }
  */

  public function on_create() {
    create_dir($this->get_upload_pres_path());
  }

  /*---------------------------------------*/
  /*CODE RELATED TO IMAGE UPLOADING*/
  /*---------------------------------------*/

  private function get_upload_pres_path()
  { 
    return "uploads/presentations/" . $this->id . "/"; 
  }

  //Upload a profile picture. Will replace the existing one if it exists.
  public function upload_pic($file_identifier)
  {
    if($_FILES[$file_identifier]["error"] > 0) {
      return;
    }

    $file = $_FILES[$file_identifier]["tmp_name"];
    $name = $_FILES[$file_identifier]["name"];

    $this->remove_pic(false,"");
    $new_path = $this->get_upload_pres_path() . $name;
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

  //Remove the directory of this presentation. Do this upon deletion.
  public function remove_dir() {

    $path = $this->get_upload_pres_path();

    //append_debug_msg($path);

    if(!$path || !file_exists($path)){ return; }
    delete_dir($path);
  }

  public function has_profile_pic()
  {
    return $this->img_path && file_exists($this->img_path);
  }

  //Get path to the user's profile picture. If it doesn't exist, return the path to default picture.
  public function get_pic_path()
  {
    if(!$this->img_path || !file_exists($this->img_path)) {
      return Config::$NO_PRES_PIC_PATH;
    }
    else {
      return $this->img_path;
    }
  }

}

?>