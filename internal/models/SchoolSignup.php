<?php

//A User model represents a member of the Engineering Ambassadors
Class SchoolSignup extends Model
{
  public $school_district;
  public $school_contact;
  public $department;
  public $phone_num;
  public $email;
  public $address;
  public $how_learn;
  public $description;
  public $created_at;

  //Must be defined in every model
  public static function get_table_name(){ return "school_signups"; }

}

?>