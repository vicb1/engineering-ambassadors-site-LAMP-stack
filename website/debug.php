<?php
//Do experiments in this PHP file

require_once 'internal/helpers/includes_no_header.php';

//$user = User::find_by_rcs("herrmg3");

//echo ($user->save() ? "true" : "false");

//echo phpversion();

echo date('m/d/Y h:i:s a', time());

/*
$user = new User(null);

$user->rcs_id = "zhengk";
$user->rin = "660989046";
$user->title = "Webmaster";
$user->year = "Junior";
$user->major = "Computer Science";
$user->email = "zhengk@rpi.edu";
$user->first_name = "Kevin";
$user->middle_name = "K";
$user->last_name = "Zheng";
$user->entry_date = null;
$user->grad_date = null;
$user->is_admin = 0;
$user->is_disabled = 0;
$user->img_takedown_msg = null;
$user->img_path = null;

$user->save();
*/


?>

<form class="infoblock" action="presentation_add.php?id=mortoj2" method="post" enctype="multipart/form-data">

  <table style="margin-top:10px">

    <input type="hidden" name="csrf_token" value="asd" />

    <tr>
      <td>Display Image</td> 
      <td><input type="file" name="pic" accept="image/*"></td> 
    </tr>

    <tr>
      <td>Presentation Name&nbsp</td> 
      <td><input type="text" name="name" /></td> 
    </tr>

    <tr>
      <td>Description</td> 
      <td><input type="text" name="desc" /></td> 
    </tr>
  
  </table>

  <button name="submit">Submit</button><br>

</form>