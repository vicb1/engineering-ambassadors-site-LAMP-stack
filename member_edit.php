<?php

require_once 'internal/helpers/includes_no_header.php';

$id = $_GET['id'];
$member = User::find_by_rcs($id);
if(!has_auth_over($member)) { 
	header('HTTP/1.0 401 Unauthorized'); echo("401 UNAUTHORIZED"); die(); 
}

if(isset($_POST['admin_rem_pic_msg']))
{
	if(!csrf_tokens_match()){ die(Config::$CSRF_DETECTED); }
	$member->remove_pic(true,$_POST['admin_rem_pic_msg']);
	redirect_to_self();
}
elseif(isset($_POST['own_rem_pic']))
{
	if(!csrf_tokens_match()){ die(Config::$CSRF_DETECTED); }
	$member->remove_pic(false,'');
	redirect_to_self();
}
elseif(isset($_POST['submit_button']))
{
	if(!csrf_tokens_match()){ die(Config::$CSRF_DETECTED); }
	
	$jobs = json_decode($_POST['jobs']);
	$ecas = json_decode($_POST['ecas']);

	//debug(var_dump($jobs));
	//debug(var_dump($ecas));

	$e_month = $_POST['e_month'];
	$e_year = $_POST['e_year'];

	$g_month = $_POST['g_month'];
	$g_year = $_POST['g_year'];

	$validator = new Validator();

	$validator->val_strs_presence(array("e_month","e_year"),array("entry month","entry year"));
	$validator->val_strs_presence(array("g_month","g_year"),array("graduation month","graduation year"));

	if($validator->no_errors())
	{
		$member->update_jobs($jobs);
		$member->update_ecas($ecas);

		//Entry month/year
		if($e_month != "" && $e_year != "") { 
			$date = new DateTime();
			$date->setDate ( intval($e_year) , intval($e_month) , 28 ); //28 is the minimum last day that a month can have 
			$member->entry_date = date_to_str($date);
		}
		else {
			$member->entry_date = null;
		}

		//Grad month/year
		if($g_month != "" && $g_year != "") { 
			$date = new DateTime();
			$date->setDate ( intval($g_year) , intval($g_month) , 28 ); //28 is the minimum last day that a month can have 
			$member->grad_date = date_to_str($date);
		}
		else {
			$member->grad_date = null;
		}

		$member->major = $_POST['major'];
		$member->year = $_POST['year'];
		$member->hometown = $_POST['hometown'];
		$member->favorite_quote = $_POST['quote'];
		$member->title = $_POST['title'];

		$result = $member->save();

		if($result == true) {
			$validator->append_msg('<span class="changesaved"><span style="color:green">&#10003;</span>&nbspYour changes have been saved.</span>');
		}
		else {
			$validator->append_err_msg("Error.<br>");
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

</style>

<div id="content">
	<div id="content_cen">
		<div id="content_sup">

			<div id="welcom_pan" style="margin-top:200px; width:100%">
	
				<h1>Edit Profile for <?php echo $member->get_name(); ?></h1>

				<h5>Profile Picture</h5>
				
				<?php

					if($member->img_takedown_msg != null)
					{
						echo "A club officer has taken down your profile image for the following reason:<br>";
						if($member->img_takedown_msg == ' '){ echo "<i>(No reason given)</i><br>"; }
						else{ echo "<i>". $member->img_takedown_msg ."</i><br>"; }
					}
				?>

				<img id="profpic" src="<?php echo $member->get_pic_path(); ?>" alt="profile picture"><br>

				<a href="member_upload_pic.php?id=<?php echo $id; ?>">
					<button type='button' id="uploadimage">Upload Profile Image</button>
				</a>

				<br>

				<?php if($member->has_profile_pic() && is_diff_auth_over($member)) :?>
					<form id="myForm" action="" method="post">
						<?php echo csrf_input(); ?>
						<input id="msg" type="hidden" name="admin_rem_pic_msg" value="">
						<input id="uploadimage" type="button" onclick="myFunction()" value="Remove Profile Image">
					</form>
				<?php elseif($member->has_profile_pic() && has_auth_over($member->rcs_id)) :?>
					<form id="myForm" action="" method="post">
						<?php echo csrf_input(); ?>
						<button id="uploadimage" name="own_rem_pic">Remove Profile Image</button>
					</form>
				<?php endif; ?>
				<?php if(has_auth_over($member)): ?>
					<form style="margin-top:5px" class="req_confirm" method="post"
					action="member_delete.php?id=<?php echo $member->rcs_id; ?>&from_member_page" >
						<div class="confirm_warning" style="display: none;">
							Warning: are you sure you want to DELETE this member? (This can't be undone!)
						</div>
						<?php echo csrf_input(); ?>
						<input type="hidden" value="from_member_page" />
						<button id="uploadimage">Delete Member</button>
					</form>
				<?php endif; ?>
				
				<form id="the_form" class="infoblock" action="" method="post">

						<?php echo csrf_input(); ?>

						<table style="margin-top:10px">

						<tr>
							<td>RPI Entry Date</td> 
							<td>
								<select name="e_month">
									<option value=""></option>
									<?php 
										for($i=1;$i<=12;$i++) 
										{
											$sel = "";
											if($member->entry_date && str_to_date($member->entry_date)->format("m") == (string)$i){ $sel = "selected"; } 
											echo "<option value=$i $sel>".int_to_month($i)."</option>\n";
										} 
									?>
								</select>
								<select name="e_year">
									<option value=""></option>
									<?php 
										for($i=getdate()['year']+6;$i>=1950;$i--) 
										{
											$sel = "";
											if($member->entry_date && str_to_date($member->entry_date)->format("Y") == (string)$i){ $sel = "selected"; } 
											echo "<option $sel>$i</option>\n";
										} 
									?>
								</select>
							</td> 
						</tr>

						<tr>
							<td>RPI Graduation Date&nbsp</td> 
							<td>
								<select name="g_month">
									<option value=""></option>
									<?php 
										for($i=1;$i<=12;$i++) 
										{
											$sel = "";
											if($member->grad_date && str_to_date($member->grad_date)->format("m") == (string)$i){ $sel = "selected"; } 
											echo "<option value=$i $sel>".int_to_month($i)."</option>\n";
										} 
									?>
								</select>
								<select name="g_year">
									<option value=""></option>
									<?php 
										for($i=getdate()['year']+6;$i>=1950;$i--) 
										{ 
											$sel = "";
											if($member->grad_date && str_to_date($member->grad_date)->format("Y") == (string)$i){ $sel = "selected"; }
											echo "<option $sel>$i</option>\n";
										} 
									?>
								</select>
							</td> 
						</tr>

						<tr>
							<td>Major and Degree</td> 
							<td><input name="major" type="text" value="<?php echo $member->get_major(); ?>" /></td> 
						</tr>
						<tr>
							<td>Title</td> 
							<td><input name="title" type="text" value="<?php echo htmlspecialchars($member->title); ?>" /></td> 
						</tr>
						<tr>
							<td>Year</td>  
							<td>
								<select name="year">
									<option value="Freshman" <?php display_if_true("selected",$member->year == "Freshman"); ?> >Freshman</option>
									<option value="Sophomore" <?php display_if_true("selected",$member->year == "Sophomore"); ?>>Sophomore</option>
									<option value="Junior" <?php display_if_true("selected",$member->year == "Junior"); ?> >Junior</option>
									<option value="Senior" <?php display_if_true("selected",$member->year == "Senior"); ?> >Senior</option>
									<option value="n/a" <?php display_if_true("selected",$member->year == "n/a"); ?> >N/A</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Hometown</td>  
							<td><input name="hometown" type="text" value="<?php echo htmlspecialchars($member->hometown); ?>" /></td> 
						</tr>
						<tr>
							<td>Favorite Quote</td>  
							<td>
								<textarea style="width:300px;height:50px;" name="quote"><?php echo htmlspecialchars($member->favorite_quote); ?></textarea>
							</td> 
						</tr>

						</table>

						CO-OPs/Interns
						<button type="button" id="add_job">Add New</button>

						<div id="job_area">
							<?php
								foreach($member->get_jobs() as $job) {
									echo "<div>";
									echo "<input class='job' type='text' value='". $job->get_name() ."'>";
									echo "<button type='button' class='remove'>Remove</button>";
									echo "</div>";
								}
							?>
						</div>
						<br>
						
						Extracurricular Activites
						<button type="button" id="add_eca">Add New</button>
							<?php
								foreach($member->get_ecas() as $eca) {
									echo "<div>";
									echo "<input class='eca' type='text' value='". $eca->get_name() ."'>";
									echo "<button type='button' class='remove'>Remove</button>";
									echo "</div>";
								}
							?>
						<div id="eca_area">

						</div>						
						
						<br>

						<button id="submit_button" name="submit_button">Submit</button><br>

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

$('#add_job').on('click',function(){
	var html = "<div>";
	html += "<input class='job' type='text'>";
	html += "<button type='button' class='remove'>Remove</button>";
	html += "</div>";
	$("#job_area").append(html);
});

$('#add_eca').on('click',function(){
	var html = "<div>";
	html += "<input class='eca' type='text'>";
	html += "<button type='button' class='remove'>Remove</button>";
	html += "</div>";
	$("#eca_area").append(html);
});

$(document).on('click','.remove',function(){
	$(this).parent().remove();
});

$("#submit_button").click(function(e){

	//Create a javascript array of all jobs entered
	var jobs = [];
	var ecas = [];

	$(".job").each(function(){
		jobs.push($(this).val());
	});
	$(".eca").each(function(){
		ecas.push($(this).val());
	});

	$("#the_form").append("<input type='hidden' name='jobs' value='"+ JSON.stringify(jobs) +"'>");
	$("#the_form").append("<input type='hidden' name='ecas' value='"+ JSON.stringify(ecas) +"'>");

	$("#the_form").submit();

});

function myFunction()
{
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
