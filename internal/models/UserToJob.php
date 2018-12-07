<?php

Class UserToJob extends Model
{
  public $user_id;
  public $job_name;

  //Must be defined in every model
  public static function get_table_name(){ return "user_to_jobs"; }

  public static function create_entry($user_id, $job_name) {
    $obj = new self(null);
    $obj->user_id = $user_id;
    $obj->job_name = $job_name;
    $obj->save();
  }

  function get_name() {
    return htmlspecialchars($this->job_name);
  }

}

?>