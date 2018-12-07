<?php
require_once 'internal/helpers/includes_no_header.php';

if(!is_user_admin()) { 
  header('HTTP/1.0 401 Unauthorized'); echo("401 UNAUTHORIZED"); die(); 
}

$user_api = null;

if(isset($_POST['submit']))
{
  set_sticky('rcs',$_POST['rcs']);

  $validator = new Validator();
  $validator->val_str_presence('rcs', "RCS Id");

  if($validator->no_errors()) {
    
    $user_api = UserAPI::create_from_rcs($_POST['rcs']);
    if(!$user_api->is_found()) {
      $validator->append_err_msg("Error: RCS id not found. Are you sure you spelled it correctly?<br>");
      $validator->append_err_msg("Click <a href='force_register.php'>here</a> to force-add an RCS id without verification that it exists (not recommended)<br>");
    }
  }
  if($validator->no_errors()) 
  {
    $result = User::create_member($user_api, false); //false means we're creating regular non-privilidged member

    $user = User::find_by_rcs($_POST['rcs']);

    if($result == 0)
    {
      $msg = "";
      $msg .= "Successfully added " . $user->get_name() . " to Engineering Ambassadors.<br>";
      $msg .= "Click <a href='member_edit.php?id=".$user->rcs_id."'>here</a> to edit the user's fields.<br>";
      $validator->append_msg($msg);
    }
    else if($result == 1)
    {
      $msg .= "Notice: The student " . $user->get_name() . " is already registered! Updating...<br>";
      $validator->append_msg($msg);
    }
    else if($result == 2)
    {
      $msg .= "Error: The student " . $user->get_name() . " was not added. The server may be having technical problems. Please try again later.<br>";
      $validator->append_err_msg($msg);
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
        <h1>Ambassador Registration</h1>
        <p>Enter in the rcs id of the person you would like to register.</p>
        <form style="margin-top:120px" action="register.php" method="POST">
          <table>
            <tr>
              <td><b>RCS ID</b>&nbsp;&nbsp;</td>
              <td><input type="text" name="rcs" value="<?php echo get_sticky('rcs'); ?>" /></td>
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
