<?php

class Config
{
  public static $API_KEY = 'ZW5nYW1iK2M1M2MyMGM4MGE1MmMwODljM2M3YThhYTM5NWRkYjNiZWI3NjM3ZTY=';
  public static $SECRET_CODE = "password";
  public static $CLUB_ID = "2254";
  public static $DATE_FORMAT = "Y-m-d";
  public static $DATETIME_FORMAT = "Y-m-d H:i:s";
  public static $USER_FRIENDLY_DATETIME = "m/d/Y g:i A";
  public static $NO_PROF_PIC_PATH = "media/no-profile-pic.jpg";
  public static $NO_PRES_PIC_PATH = "media/no-pres-pic.png";
  public static $CSRF_DETECTED = "A CSRF attack attempt was detected! The attack has been prevented.";
}

class DB
{
  private static $dbo;

  public static function get_dbo(){ return self::$dbo; }

  public static function set_dbo()
  {
    $user = "";
    $password = ""; //Don't show this to anyone, keep the code secret.

    try
    {
      /* First is used for server, second is used for local testing */
      
      //$dbo = new PDO('mysql:host=localhost;dbname=engamb_db', $user, $password);
      self::$dbo = new PDO('mysql:host=localhost;dbname=','root','');
    } 
    catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

}

date_default_timezone_set('America/New_York');
DB::set_dbo();

?>