<?php
require_once 'internal/helpers/includes.php';
?>

<?php
$users = User::all_current("order_by_position", false);
?>

<style>

#all_users { table-layout: fixed; }
#all_users td { width: 25%; text-align: center; }
#all_users img { width: 80%; height:160px; }
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

        <h2 style="width:100%"><span>All</span> Engineering Ambassadors</h2>

        <table id="all_users" style="width:100%; margin-left:-18px">
          <?php

            //We need to fill in the extra blanks for the grid format
            $count = count($users);
            $new_count = $count + ($count % 4 == 0 ? 0 : (4 - ($count % 4)));

            for($i = 0; $i < $new_count; $i++) {
              if($i % 4 == 0) { echo "<tr>\n"; }
              echo "<td>\n";
              if($i < count($users)) {
                echo '<img src="'. $users[$i]->get_pic_path() .'" alt="no-profile-pic" >'. "\n";
                echo '<br><a href="member_view.php?id='. $users[$i]->rcs_id .'">'. $users[$i]->get_name() ."</a>\n";
                echo '<br><span>'. $users[$i]->get_title() . "</span>\n";
                echo "</td>\n";
              }
              if( ($i != 0 && ($i + 1) % 4 == 0)) { echo "</tr>\n"; }
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