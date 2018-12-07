<?php

require_once 'internal/helpers/includes_no_header.php';

if(!isset($_GET['id'])){ die("Error in deleting user. They may not exist, or they may already have been deleted."); }
if(!csrf_tokens_match()){ die(Config::$CSRF_DETECTED); }

$id = $_GET['id'];
$member = User::find_by_rcs($id);
$name = $member->get_name();
$member->delete();

set_success_msg("Ambassador \"$name\" successfully deleted.");

if(!isset($_GET['from_member_page']))
  redirect_back();
else
  redirect_to("member_all.php");


?>