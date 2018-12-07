<?php
require_once 'internal/helpers/includes.php';
?>

<style>

.prof_pic
{
  float: left;
}
.info_block
{
  margin-left: 20px;
  float: left;
  width:40%;
}
.name
{

}
img { box-shadow: 0px 0px 4px 1px #DDDDDD; }

</style>

<?php

$id = $_GET['id'];
$pres = Presentation::find($id);

?>

<div id="content">
  <div id="content_cen">
    <div id="content_sup">

      <div id="welcom_pan" style="margin-top:200px; width:100%">
        
        <img class="prof_pic" src="<?php echo $pres->get_pic_path(); ?>" width="250px" alt="profile-pic">
        <div class="info_block">
          <h1 id="name"><?php echo $pres->get_name(); ?></h1>
          <span>Presenters: <?php echo $pres->get_users_href(); ?></span><br>
          <!--
          <span>Created at: <?php echo $pres->get_created_at(); ?></span><br>
          <span>Updated at: <?php echo $pres->get_updated_at(); ?></span><br>
          -->
          <?php if(has_auth_overs($pres->get_users())): ?>
            <a href="presentation_edit.php?id=<?php echo $pres->id; ?>"><button>Edit Presentation</button></a>
          <?php endif; ?>
          <?php if(has_auth_overs($pres->get_users())): ?>
            <form style="margin-top:5px" class="req_confirm" method="post"
            action="presentation_delete.php?id=<?php echo $pres->id; ?>&from_pres_page" >
              <div class="confirm_warning" style="display: none;">
								Warning: are you sure you want to DELETE this presentation? (This can't be undone!)
							</div>
							<?php echo csrf_input(); ?>
              <input type="hidden" value="from_pres_page" />
              <button>Delete Presentation</button>
            </form>
          <?php endif; ?>
        </div>

        <p style="font-size:20px; font-weight:bold;">Presentation Description</p>
        <p class="preformatted"><?php echo $pres->get_description(); ?></p>

      </div>

    </div>
  </div>
</div>

<?php
require_once 'internal/helpers/footer.php';
?>