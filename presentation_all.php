<?php
require_once 'internal/helpers/includes.php';
?>

<?php
$preses = Presentation::all();
?>

<style>

#all_users { table-layout: fixed; }
#all_users td { width: 50%; text-align: center; padding-right:75px; }
#all_users img { width: 400px; height: 250px;}
#all_users td > p { display: inline-block; }
#all_users tr { display:inline-block; margin-top: 25px; }
#all_users img { box-shadow: 0px 0px 4px 1px #DDDDDD; }
#all_users td > span { font-size: 12px; }
#all_users td > a { font-size: 16px; }

</style>

<div id="content">
  <div id="content_cen">
    <div id="content_sup">

      <div id="welcom_pan" style="margin-top:200px;">
        
        <?php display_msgs(); ?>

        <?php if(User::is_logged_in()): ?>
          <a href="presentation_add.php?id=<?php echo User::get_current_rcs_id(); ?>"><button>Add New</button></a><br><br>
        <?php endif;?>

        <h2 style="width:100%"><span>All</span> Presentations</h2>

        <table id="all_users" style="width:100%;">
          <?php

            //We need to fill in the extra blanks for the grid format
            $count = count($preses);

            if($count == 0) {
              echo "<p>No presentations yet</p>";
            }
            else {

              $new_count = $count + ($count % 2 == 0 ? 0 : (2 - ($count % 2)));

              for($i = 0; $i < $new_count; $i++) {
                if($i % 2 == 0) { echo "<tr>\n"; }
                echo "<td>\n";
                if($i < count($preses)) {
                  echo '<img src="'. $preses[$i]->get_pic_path() .'" alt="no-profile-pic">'. "\n";
                  echo '<br>'. $preses[$i]->get_ahref() . "\n";
                echo "<br><span>Presenters: ". $preses[$i]->get_users_href() ."</span><br>";
                  echo "</td>\n";
                }
                if( ($i != 0 && ($i + 1) % 2 == 0)) { echo "</tr>\n"; }
              }

            }
          ?>
        </table>

      </div>
    </div>
  </div>
</div>

<?php
require_once 'internal/helpers/footer.php';
?>