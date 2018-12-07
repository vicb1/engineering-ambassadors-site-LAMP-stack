<?php

Class UserToECA extends Model
{
  public $user_id;
  public $eca_name;

  //Must be defined in every model
  public static function get_table_name(){ return "user_to_ecas"; }

  public static function create_entry($user_id, $eca_name) {
    $obj = new self(null);
    $obj->user_id = $user_id;
    $obj->eca_name = $eca_name;
    $obj->save();
  }

  function get_name() {
    return htmlspecialchars($this->eca_name);
  }

}

?>