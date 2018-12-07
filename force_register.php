<?php
require_once 'internal/helpers/includes_no_header.php';

if(!is_user_admin()) { 
  header('HTTP/1.0 401 Unauthorized'); echo("401 UNAUTHORIZED"); die(); 
}

$user_api = null;

if(isset($_POST['submit']))
{
  set_sticky('rcs',$_POST['rcs']);
  set_sticky('fname',$_POST['fname']);
  set_sticky('lname',$_POST['lname']);

  $validator = new Validator();
  $validator->val_str_presence('rcs', "RCS Id");
  $validator->val_str_presence('fname', "first name")->val_str_max_length('fname',"first name", 25);
  $validator->val_str_presence('lname', "last name")->val_str_max_length('lname',"last name", 25);

  if($validator->no_errors()) {
    $user = new User(null);
    $user->rcs_id = $_POST['rcs'];
    $user->first_name = $_POST['fname'];
    $user->last_name = $_POST['lname'];
    $user->title = "Member";
    $user->year = "n/a";
    $user->major = "n/a";
    $user->email = $_POST['rcs'] . "@rpi.edu";
    $user->is_admin = 0;
    $user->is_disabled = 0;

    if($user->save()) {
      $msg = "";
      $msg .= "Successfully added " . $user->get_name() . " to Engineering Ambassadors.<br>";
      $msg .= "Click <a href='member_edit.php?id=".$user->rcs_id."'>here</a> to edit the user's fields.<br>";
      $validator->append_msg($msg);
    }
    else {
      $validator->append_err_msg("Error: user was not saved<br>");
    }
  }

  $validator->set_msg();

  redirect_to_self();
  
}

require_once 'internal/helpers/header.php';

?>

<div id="content">
  <div id="content_cen">
    <div id="content_sup">
      <div id="welcom_pan" style="margin-top:200px">
        <h1>Ambassador Force Registration</h1>
        <p>Enter in the rcs id, first name and last name of the person you would like to register.</p>
        <form style="margin-top:120px" action="" method="POST">
          <table>
            <tr>
              <td><b>RCS ID</b>&nbsp;&nbsp;</td>
              <td><input type="text" name="rcs" value="<?php echo get_sticky('rcs'); ?>" /></td>
            </tr>
            <tr>
              <td><b>First Name</b></td>
              <td><input type="text" name="fname" value="<?php echo get_sticky('fname'); ?>" /></td>
            </tr>
            <tr>
              <td><b>Last Name</b></td>
              <td><input type="text" name="lname" value="<?php echo get_sticky('lname'); ?>" /></td>
            </tr>
          </table>
          <button name="submit">Submit</button>
        </form>

        <?php
          display_msgs();
        ?>

      </div>
    </div>
</div>

<?php
require_once 'internal/helpers/footer.php';
?>
