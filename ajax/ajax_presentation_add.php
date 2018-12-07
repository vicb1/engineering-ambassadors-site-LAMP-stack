<?php

require_once '../internal/helpers/includes_no_header.php';

$id = $_GET['id'];
$member = User::find_by_rcs($id);
if(!has_auth_over($member)) { 
  header('HTTP/1.0 401 Unauthorized'); echo("401 UNAUTHORIZED"); die(); 
}

//error_log("1\n");

if(isset($_POST['submit']))
{
  if(!csrf_tokens_match()){ die(Config::$CSRF_DETECTED); }

  $validator = new Validator();

  $validator->val_str_presence("name","name");
  $validator->val_str_presence("desc","description");
  $validator->val_img_presence("pic","a display image");

  if($validator->no_errors())
  {
    $new_pres = new Presentation(null);
    $new_pres->name = $_POST['name'];
    $new_pres->description = $_POST['desc'];
    $new_pres->created_at = datetime_to_str(new DateTime());  //new DateTime() returns now
    $new_pres->creator_id = $member->id;

    $result = $new_pres->save();

    if($result == true)
    {
      $new_pres->upload_pic("pic");
      $success = '<span class="changesaved"><span style="color:green">&#10003;</span>&nbspPresentation Successfully Added.</span>';
      $validator->append_msg($success);
    }
    else
    {
      $validator->append_err_msg("Some error occurred. Try again later.<br>");
    }
  }

  $status = ($validator->no_errors() ? "0" : "1");   //0 indicates form successfully sent, 1 indicates error
  $ajax_return = array("status_code" => $status, "msg" => $validator->get_msg());

  echo json_encode($ajax_return);

}

?>