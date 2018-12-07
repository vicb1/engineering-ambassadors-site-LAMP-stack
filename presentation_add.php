<?php

//NOTE: On this page, don't post any posts with name set to a number string. Those are reserved for posting
//user ids.

require_once 'internal/helpers/includes_no_header.php';

$id = $_GET['id'];
$member = User::find_by_rcs($id);
if(!has_auth_over($member)) { 
  header('HTTP/1.0 401 Unauthorized'); echo("401 UNAUTHORIZED"); die(); 
}

//$ajax_url = "ajax/ajax_presentation_add.php?id=$id";

if(isset($_POST['submit']))
{
  if(!csrf_tokens_match()){ die(Config::$CSRF_DETECTED); }

  set_sticky("name",$_POST["name"]);
  set_sticky("desc",$_POST["desc"]);

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

  $validator = new Validator();

  $validator->val_str_presence("name","presentation name");
  $validator->val_uniqueness("name","presentation name","Presentation","name",null);
  $validator->val_str_presence("desc","description");
  $validator->val_str_max_length("desc","description",1000);
  $validator->val_img_presence("pic","a display image");

  if($validator->no_errors())
  {
    $new_pres = new Presentation(null);
    $new_pres->name = $_POST['name'];
    $new_pres->description = $_POST['desc'];
    $new_pres->created_at = datetime_to_str(new DateTime());  //new DateTime() returns now
    $result = $new_pres->save();

    if($result == true)
    {
      $new_pres->upload_pic("pic");
      $new_pres->add_user($member->id);
      $new_pres->add_users($added_users);

      if(is_post_set('users')) {
        $new_pres->add_users(explode(" ",$_POST['users'])); //Add the users entered
      }

      $success = '<span class="changesaved"><span style="color:green">&#10003;</span>&nbspPresentation Successfully Added.</span>';
      $validator->append_msg($success);
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
  
        <h1>Add Presentation for <?php echo $member->get_name(); ?></h1>

        <form id="form_id" class="submit_once" action="" method="post" enctype="multipart/form-data">

          <?php echo csrf_input(); ?>
          
          <table style="margin-top:10px">

            <tr>
              <td>Display Image</td> 
              <td><input type="file" name="pic" accept="image/*"></td> 
            </tr>

            <tr>
              <td>Presentation Name&nbsp</td> 
              <td><input type="text" name="name" value="<?php echo get_sticky('name'); ?>"/></td> 
            </tr>

            <tr>
              <td>Description</td> 
              <td><textarea id="desc" name="desc" maxlength="1000"><?php echo get_sticky('desc'); ?></textarea></td> 
            </tr>
          
          </table>

          <p>Other presenters <button id="add_presenter" type='button'>Add presenter</button> </p>
          <div id="presenters">

            <?php
              $users = get_sticky("users");
              if(isset($users) && is_array($users)) {
                foreach($users as $user) {
                  echo user_dropdown($user);
                  echo "<button class='remove' type='button'>Remove</button><br>";
                }
              }
            ?>

          </div>

          <div id="submit_area">
            <button id="submit_button" name="submit">Submit</button><br>
          </div>

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

/*
//Hook the form and submission button to AJAX.
set_ajax_form({
  ajax_form: $("#form_id"),
  url: "<? //php echo $ajax_url; ?>"
});
*/
</script>


