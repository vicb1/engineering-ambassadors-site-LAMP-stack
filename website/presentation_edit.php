<?php

//NOTE: On this page, don't post any posts with name set to a number string. Those are reserved for posting
//user ids.

require_once 'internal/helpers/includes_no_header.php';

$id = $_GET['id'];
$pres = Presentation::find($id);
$users = $pres->get_users();
if(!has_auth_overs($users)) { 
  header('HTTP/1.0 401 Unauthorized'); echo("401 UNAUTHORIZED"); die(); 
}

$msg = "";
$err = false;

if(isset($_POST['admin_rem_pic_msg']))
{
  if(!csrf_tokens_match()){ die(Config::$CSRF_DETECTED); }
  $pres->remove_pic(true,$_POST['admin_rem_pic_msg']);
  redirect_to_self();
}
elseif(isset($_POST['own_rem_pic']))
{
  if(!csrf_tokens_match()){ die(Config::$CSRF_DETECTED); }
  $pres->remove_pic(false,'');
  redirect_to_self();
}
elseif(isset($_POST['submit_button']))
{
  if(!csrf_tokens_match()){ die(Config::$CSRF_DETECTED); }

  $validator = new Validator();

  $validator->val_str_presence("name","presentation name");
  $validator->val_uniqueness("name","presentation name","Presentation","name",$pres->id);
  $validator->val_str_presence("desc","description");
  $validator->val_str_max_length("desc","description",1000);

  $added_users = array();

  //Loop through all posts
  foreach($_POST as $key => $val) {
    //A number was encountered, this represents a POSTED user
    if(is_numeric($key)) {
      $user = User::find($key);
      array_push($added_users,$user);
    }
  }

  set_sticky("users",$added_users);

  if(count($added_users) == 0) {
    $validator->append_err_msg("Error: no presenters specified.<br>");
  }

  if($validator->no_errors())
  {
    $pres->name = $_POST['name'];
    $pres->description = $_POST['desc'];
    $pres->updated_at = datetime_to_str(new DateTime());  //new DateTime() returns now
    $pres->upload_pic("pic");
    $pres->update_users($added_users);

    $result = $pres->save();

    if($result == true)
    {
      $msg .= '<span class="changesaved"><span style="color:green">&#10003;</span>&nbspPresentation Successfully Updated.</span>';
      $validator->append_msg($msg);
    }
    else
    {
      $validator->append_err_msg("Some error occurred. Try again later.<br>");
    }
  }

  $validator->set_msg();
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
  margin-top:5px;
}
#desc
{
  width:400px;
  height:100px;
}
small
{
  font-style: italic;
}

</style>

<div id="content">
  <div id="content_cen">
    <div id="content_sup">

      <div id="welcom_pan" style="margin-top:200px; width:100%">
  
        <h1>Edit Presentation "<?php echo $pres->get_name(); ?>"</h1>

        <h5>Display Image</h5>
        
        <?php

          if($pres->img_takedown_msg != null)
          {
            echo "A club officer has taken down this presentation's display image for the following reason:<br>";
            if($pres->img_takedown_msg == ' '){ echo "<i>(No reason given)</i><br>"; }
            else{ echo "<i>". $pres->img_takedown_msg ."</i><br>"; }
          }
        ?>

        <img id="profpic" src="<?php echo $pres->get_pic_path(); ?>" alt="profile picture"><br>

        <?php if($pres->has_profile_pic() && is_diff_auth_overs($users)) :?>
          <form id="myForm" action="" method="post">
            <?php echo csrf_input(); ?>
            <input id="msg" type="hidden" name="admin_rem_pic_msg" value="">
            <input id="uploadimage" type="button" onclick="myFunction()" value="Remove Display Image">
          </form>
        <?php elseif($pres->has_profile_pic() && has_auth_overs($users)) :?>
          <form id="myForm" action="" method="post">
            <?php echo csrf_input(); ?>
            <button id="uploadimage" name="own_rem_pic">Remove Display Image</button>
          </form>
        <?php endif; ?>
        
        <form class="infoblock" id="main_form" action="" method="post" enctype="multipart/form-data">

            <?php echo csrf_input(); ?>
            
            <table style="margin-top:10px">

            <tr>
              <td>Add/Edit Display Image &nbsp</td> 
              <td><input type="file" name="pic" accept="image/*"></td> 
            </tr>
            <tr>
              <td>Name</td> 
              <td><input type="text" name="name" value="<?php echo $pres->get_name(); ?>"/></td> 
            </tr>
            <tr>
              <td>Description</td> 
              <td><textarea id="desc" name="desc" maxlength="1000"><?php echo $pres->get_description(); ?></textarea></td> 
            </tr>

            </table>

            <p>Presenters <button id="add_presenter" type='button'>Add presenter</button> </p>

            <div id="presenters">
              <?php
                $sticky_users = get_sticky("users");
                if(isset($sticky_users) && is_array($sticky_users)) {
                  foreach($sticky_users as $user) {
                    echo user_dropdown($user);
                    echo "<button class='remove' type='button'>Remove</button><br>";
                  }
                }
                else {
                  foreach($users as $user) {
                    echo user_dropdown($user);
                    echo "<button class='remove' type='button'>Remove</button><br>";
                  }
                }

              ?>
            </div>

            <br><button id="submit_id" name="submit_button">Submit</button><br>

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

<script>

set_user_pres_dropdown("<?php echo user_dropdown(null); ?>");

$("#submit_id").on('click',function(e){

  <?php if(!is_diff_auth_overs($users)): ?>
    //If the current user is no longer on the list AND we are not an external admin, 
    var found = false;
    $(".user_select").each(function(){
      if( $(this).attr('name') == "<?php echo User::get_current_user()->id; ?>") {
        found = true;
      }
    });
    if(!found) {
      var r=confirm("You have removed yourself from this presentation group! Are you sure?");
      if (r==true) {
        return true;
      }
      return false;
    }
  <?php endif; ?>

});

function myFunction() {
  var text=prompt("Enter in a description on why you are taking this down");
  if(text != null) {

    if (!('trim' in String.prototype)) {
        String.prototype.trim= function() {
            return this.replace(/^\s+/, '').replace(/\s+$/, '');
        };
    }
    if (text.trim()==='') {
      text = " ";
    }

    $("#msg").val(text);
    document.getElementById("myForm").submit();
  }
}

</script>
