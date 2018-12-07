<?php

//Represents user_to_pres table. 
Class UserToPres extends Model
{
  public $user_id;
  public $pres_id;

  //Must be defined in every model
  public static function get_table_name(){ return "user_to_pres"; }

  public static function create_entry($user_id, $pres_id) {

    $obj = new self(null);
    $obj->user_id = $user_id;
    $obj->pres_id = $pres_id;
    $obj->save();
  }

}

?>