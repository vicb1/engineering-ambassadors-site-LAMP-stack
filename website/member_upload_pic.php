<?php

require_once 'internal/helpers/includes_no_header.php';

$id = $_GET['id'];
$member = User::find_by_rcs($id);
if(!has_auth_over($member)) { 
  header('HTTP/1.0 401 Unauthorized'); echo("401 UNAUTHORIZED"); die(); 
}

$msg = "";
$err = false;

if(isset($_POST['submit']))
{
  if(!csrf_tokens_match()){ die(Config::$CSRF_DETECTED); }

  if($_FILES["pic"]["error"] > 0)
  {
    $err = true;
    $msg .= "Error, please try again<br>";
  }
  else
  {
    $size = ($_FILES["pic"]["size"] / 1024);
    $is_img = is_img($_FILES["pic"]['tmp_name']);
    
    //echo (string)$size;

    if($size > 2048) {
      $err = true;
      $msg .= "Error: image size too large (can't be more than 2 megabytes)<br>";
    }

    if(!$is_img) {
      $err = true;
      $msg .= "Error: file is not of type image.<br>";
    }

    if($err == false) {
      $member->upload_pic("pic");
      $msg .= "File successfully uploaded!<br>";
    }

  }

  if($err)
    set_error_msg($msg);
  else
    set_success_msg($msg);

  redirect_to_self();

}

require_once 'internal/helpers/header.php';

?>

<style type="text/css">
  
#profpic
{
  width:200px;
  height:200px;
  background-size: 200px 200px;
}
#profinfo
{
  position:absolute;
  bottom:0px;
  left:200px;
}
#profbar
{
  position:relative;
  height:200px;
}
#uploadimage
{
  width:150px;
  margin-left:25px;
}

</style>

<div id="content">
  <div id="content_cen">
    <div id="content_sup">

      <div id="welcom_pan" style="margin-top:200px; width:100%">
        
        <form class="infoblock" action="" method="post" enctype="multipart/form-data">

          <?php echo csrf_input(); ?>
          
          <h1>Upload Avatar Image</h1>
          <img id="profpic" src="<?php echo $member->get_pic_path(); ?>" alt="profile picture">
          <br>
          <input type="file" name="pic" accept="image/*"><br>

          <button name="submit">Submit</button>
          <a href="member_edit.php?id=<?php echo $id; ?>">
            <button type="button">Back</button><br>
          </a>

        </form>

        <?php
          display_msgs();
        ?>

      </div>

    </div>
  </div>
</div>

<?php
require_once 'internal/helpers/footer.php';
?>