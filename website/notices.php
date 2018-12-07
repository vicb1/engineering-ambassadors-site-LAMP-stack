<?php

require_once 'internal/helpers/includes_no_header.php';

$id = $_GET['id'];
$member = User::find_by_rcs($id);
if(!has_auth_over($member)) { 
  header('HTTP/1.0 401 Unauthorized'); echo("401 UNAUTHORIZED"); die(); 
}

require_once 'internal/helpers/header.php';

?>

<?php

if(isset($_POST['img_takedown']))
{
  $member->img_takedown_msg = null;
  $member->save();
}

?>

<div id="content">
  <div id="content_cen">
    <div id="content_sup">

      <div id="welcom_pan" style="margin-top:200px; width:100%">
  
        <h1>Notices for <?php echo $member->get_name(); ?></h1>

        <?php 
          if($member->img_takedown_msg != null)
          {
            echo "A club officer has taken down your profile image for the following reason:<br>";
            if($member->img_takedown_msg == " "){ echo "<i>(No reason given)</i></br>"; }
            else{ echo "<p><i>". $member->img_takedown_msg ."</i></p>"; }
            ?>
            <form action="" method="post">
              <button name="img_takedown">OK</button>
            </form>
        <?php
          }
          else
            echo "<p>(No notices)</p>"; 

        ?>

      </div>

    </div>
  </div>
</div>

<?php
require_once 'internal/helpers/footer.php';
?>