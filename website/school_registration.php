<?php

require_once 'internal/helpers/includes_no_header.php';

if(isset($_POST['submit'])) {
  
  $name = $_POST['school'];
  $phone = $_POST['phone'];
  $contact = $_POST['contact'];
  $department = $_POST['department'];
  $school_address = $_POST['school_address'];
  $how_learn = $_POST['how_learn'];
  $desc = $_POST['desc'];

  $msg = "";
  $err = false;

  if($name == "") {
    $err = true;
    $msg .= "Error: Please enter in a school<br>";
  }
  if($phone == "") {
    $err = true;
    $msg .= "Error: Please enter in a phone number<br>";
  }
  if($contact == "") {
    $err = true;
    $msg .= "Error: Please enter in a contact<br>";
  }
  if($department == "") {
    $err = true;
    $msg .= "Error: Please enter in a department<br>";
  }
  if($school_address == "") {
    $err = true;
    $msg .= "Error: Please enter in a school_address<br>";
  }
  if($how_learn == "") {
    $err = true;
    $msg .= "Error: Please enter in how you learned about us<br>";
  }
  if($desc == "") {
    $err = true;
    $msg .= "Error: Please enter in a description<br>";
  }

  if($err)
    set_error_msg($msg);
  else
  {
    $msg = "Successfully saved<br>";

    $school_signup = new SchoolSignup(null);

    $school_signup->school_district = $name;
    $school_signup->school_contact = $contact;
    $school_signup->department =  $department;
    $school_signup->phone_num = $phone;
    $school_signup->address = $school_address;
    $school_signup->how_learn = $how_learn;
    $school_signup->description = $desc;
    $school_signup->created_at = datetime_to_str(new DateTime());

    $school_signup->save();
    
    set_success_msg($msg);
    //echo "test";
    //echo "<script>alert(\"I am an alert box!\");</script>";
    //window.location = ./$root_path;


  }

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

</style>

<div id="content">
  <div id="content_cen">
    <div id="content_sup">

      <div id="welcom_pan" style="margin-top:200px; width:100%">
        
        <h1>School Registration</h1>
        <?php
          display_msgs();
        ?>
        <form action="" method="post">
           <br>
          <b>Rensselaer Engineering Ambassador Program</b><br>
          Educational Outreach Request Form (Schedule developed in September for school year) <br><br><br>
          <!-- You should add the following line in every form, to protect against a CSRF (cross-site request forgery) attack -->
          <?php echo csrf_input(); ?>
          <table style="width:450px">
            <tr><td>School District: </td><td><input name="school" type="text" /></td></tr>
            <tr><td>Work or Cell phone: </td><td><input name="phone" type="text" /></td></tr>
            <tr><td>6 - 12 School's Contact:</td><td><input name="contact" type="text" /></td></tr>
            <tr><td>Department: </td><td><input name="department" type="text" /></td></tr>
            <tr><td>School Address: </td><td><input name="school_address" type="text" /></td></tr>
          </table>
          <br>
          How did you learn about the Rensselaer Engineering Ambassador program?<br>
          <textarea name="how_learn" rows="2" style="width:450px"></textarea><br>
          <br>
          Please describe level of participation in the school-wide event include classes, grade level, courses - science, 
          technology, and math, etc. Please include whether there will be a culminating activity such as a general panel 
          discussion for the younger audience to explore engineering education in general, with specific engineering 
          disciplines, college preparation, college life, etc. How will students be encouraged to attend? Any additional 
          information is greatly appreciated.<br>
          <textarea name="desc" rows="6" style="width:450px"></textarea><br>
          <br>
          <button name="submit">Submit</button>
        </form>

        A representative from the Education Outreach Department will be contact within one week of the inquiry. Thank 
          you for your interest in our program. 
        <br><br>
        Thank you,<br>
          Rensselaer Polytechnic Institute<br>
          School of Engineering – Outreach Office<br>
          518-276-6245<br>
        

      </div>

    </div>
  </div>
</div>

<?php
require_once 'internal/helpers/footer.php';
?>
