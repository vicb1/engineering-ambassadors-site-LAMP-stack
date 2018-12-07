<?php

//All "Model" objects inherit from this model class.
//A "Model" class is just an object that represents an entry from any database table (i.e. users, presentations,etc).
//You should never be using this class directly; inherit from it.
//Comes with useful generic methods, i.e. "save()"

//NOTES:

class Model
{
  public $id;

  //Must be defined in every model
  public static function get_table_name(){ return ""; }

  //Callbacks
  public function on_create(){}
  public function on_delete(){}
  public function on_update(){}
  public function on_construct(){}  //Note: called only when constructor is called and $obj isn't null

  //Delete this current model object.
  public function delete()
  {
    $dbo = DB::get_dbo();

    $dbh = $dbo->prepare("DELETE FROM " . static::get_table_name() . " WHERE id = ?");
    $result = $dbh->execute(array($this->id)) or (print_pdo_errors("delete",$dbh) || die('Failed to run delete query for model ' . get_class($this)));
    
    $this->on_delete();

    return $result;
  }

  //Delete a model object by id
  static function delete_by_id($id)
  {
    $dbo = DB::get_dbo();

    $dbh = $dbo->prepare("DELETE FROM " . static::get_table_name() . " WHERE id = ?");
    $result = $dbh->execute(array($id)) or (print_pdo_errors("delete",$dbh) || die('Failed to run delete query for model ' . get_class($this)));
    
    $this->on_delete();
    
    return $result;
  }

  //Find a model object by id
  static function find($id)
  {
    $dbo = DB::get_dbo();

    $dbh = $dbo->prepare("SELECT * FROM " . static::get_table_name() . " WHERE id = ?");
    $dbh->execute(array($id)) or (print_pdo_errors("insert",$dbh) || die('Failed to run find query for model ' . get_class($this)));
    $result = $dbh->fetchObject();
    $class = get_called_class();

    if($result != null)
      return new $class($result);
    else
      return null;
  }

  //Find a model object by attribute. Do not pass in $attribute as anything user-inputted, or you will be vulnerable to SQL injection!
  static function find_by($attribute, $value)
  {
    $models = [];
    $dbo = DB::get_dbo();
    $dbh = $dbo->prepare('SELECT * FROM ' . static::get_table_name() . " WHERE $attribute = ?");
    $dbh->execute(array($value)) or (print_pdo_errors("insert",$dbh) || die('Failed to run find query for model ' . get_class($this)));
    $result = $dbh->fetchAll(PDO::FETCH_OBJ);
    $class = get_called_class();

    foreach($result as $obj) {
      array_push($models,new $class($obj));
    }
    return $models;
  }

  //Find by for multiple attributes and values (they are passed in as arrays)
  static function find_by_multiple($attributes, $values) {

  }

  //Get all model objects that exist
  public static function all()
  {
    $models = [];
    $dbo = DB::get_dbo();
    $dbh = $dbo->prepare('SELECT * FROM ' . static::get_table_name());
    $dbh->execute();
    $result = $dbh->fetchAll(PDO::FETCH_OBJ);
    $class = get_called_class();

    foreach($result as $obj) {
      array_push($models,new $class($obj));
    }
    return $models;
  }

  //Create a new model object
  function __construct($obj)
  {
    if(!$obj){ return; }

    $vars = $this->get_vars();

    foreach($vars as $var) {
      $this->{$var} = $obj->{$var};
    }

    $this->on_construct();
  }

  //Save a model object to db
  function save()
  {
    $dbo = DB::get_dbo();

    $vars = $this->get_vars();

    //Does not exist: create from scratch
    if(static::find($this->id) == null)
    {
      $sql = "INSERT INTO " . static::get_table_name();
      $sql .= " (";

      for($i=0;$i<count($vars);$i++) {
        $sql .= $vars[$i];
        if($i != count($vars) - 1) { $sql .= ", "; }
      }

      $sql .= " )";
      $sql .= " VALUES(";

      for($i=0;$i<count($vars);$i++) {
        $sql .= "?";
        if($i != count($vars) - 1) { $sql .= ", "; }
      }

      $sql .= ");";

      //echo $sql;

      $dbh = $dbo->prepare($sql);

      for($i=0;$i<count($vars);$i++) {
        $dbh->bindParam($i+1,$this->{$vars[$i]});
      }

      $result = $dbh->execute() or (print_pdo_errors($sql,$dbh) || die('Failed to run insert query for model ' . get_class($this)));

      if($result)
      { 
        $this->id = $dbo->lastInsertId();
        $this->on_create(); 
      }

      return $result;
    }
    //Does exist: update entries
    else
    {
      $sql = "UPDATE " . static::get_table_name();
      $sql .= " SET ";

      for($i=0;$i<count($vars);$i++) {
        $sql .= $vars[$i] . "=?";
        if($i != count($vars) - 1) { $sql .= ", "; }
      }

      $sql .= " WHERE id=?;";

      //echo $sql;

      $dbh = $dbo->prepare($sql);

      $i=0;
      for($i;$i<count($vars);$i++) {
        $dbh->bindParam($i+1,$this->{$vars[$i]});
      }
      $dbh->bindParam($i+1,$this->id);

      $result = $dbh->execute() or (print_pdo_errors($sql,$dbh) || die('Failed to run update for model ' . get_class($this)));

      if($result){ $this->on_update(); }

      return $result;
    }

  }

  function get_vars() 
  {
    $to_ret = [];
    $vars = get_class_vars(get_class($this));
    foreach($vars as $var => $value) {
      array_push($to_ret,$var);
    }
    return $to_ret;
  }

}

?>

