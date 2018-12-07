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
.data_header {
	margin:0px;
	font-size:16px;
	font-weight: bold;
}

</style>

<?php

$id = $_GET['id'];
$member = User::find_by_rcs($id);
$preses = $member->get_presentations();
$ecas = $member->get_ecas();
$jobs = $member->get_jobs();

?>

<div id="content">
	<div id="content_cen">
		<div id="content_sup">

			<div id="welcom_pan" style="margin-top:200px; width:100%">
				
				<?php display_msgs(); ?>

				<img class="prof_pic" src="<?php echo $member->get_pic_path(); ?>" width="250px" alt="profile-pic">
				<div class="info_block">
					<h1 id="name"><?php echo $member->get_name(); ?></h1>
					<span><?php echo $member->get_title(); ?></span><br>
					<span><?php echo $member->get_major(); ?></span><br>
					<span><?php echo $member->get_class(); ?></span><br>
					<?php if(has_auth_over($member)): ?>
						<a href="member_edit.php?id=<?php echo $member->rcs_id; ?>"><button>Edit Profile</button></a>
					<?php endif; ?>

						<?php if(has_auth_over($member)): ?>
							<form style="margin-top:5px" class="req_confirm" method="post"
							action="member_delete.php?id=<?php echo $member->rcs_id; ?>&from_member_page" >
								<div class="confirm_warning" style="display: none;">
									Warning: are you sure you want to DELETE this member? (This can't be undone!)
								</div>
								<?php echo csrf_input(); ?>
								<input type="hidden" value="from_member_page" />
								<button>Delete Member</button>
							</form>
						<?php endif; ?>

				</div>

			</div>

			<p class="data_header">Hometown</p>
			<ul><li><?php echo $member->get_hometown(); ?></li></ul>

			<?php if(count($ecas) > 0) :?>
				<p class="data_header">Extracurricular Activities</p>
				<?php
					echo "<ul>"; 
					foreach($ecas as $eca) {
						echo "<li>" . $eca->get_name() . "</li>";
					}
					echo "</ul>";
				?>
			<?php endif; ?>

			<?php if(count($jobs) > 0) :?>
				<p class="data_header">Internships/Co-Ops</p>
				<?php
					echo "<ul>"; 
					foreach($jobs as $job) {
						echo "<li>" . $job->get_name() . "</li>";
					}
					echo "</ul>";
				?>
			<?php endif; ?>
			
			<p class="data_header">Favorite Quote</p>
			<ul><li><?php echo $member->get_favorite_quote(); ?></li></ul>

			<hr>

			<h1><?php echo $member->get_name(); ?>'s Presentations</h1>

			<?php if(has_auth_over($member)): ?>
				<a href="presentation_add.php?id=<?php echo $id; ?>"><button>Add New</button></a><br><br>
			<?php endif; ?>
			
			<?php if(count($preses) == 0) :?>
				<p>(No presentations)</p>
			<?php else :?>
				<ul>
					<?php
						foreach($preses as $pres) {
							echo '<li>&bull;&nbsp' . $pres->get_ahref() . '</li>';
						}
					?>
				</ul>
			<?php endif; ?>

		</div>
	</div>
</div>

<?php
require_once 'internal/helpers/footer.php';
?>