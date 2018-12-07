<?php

require_once 'internal/helpers/includes_no_header.php';

if(!isset($_GET['id'])){ die("Error in deleting presentation. It may not exist, or it may already have been deleted."); }
if(!csrf_tokens_match()){ die(Config::$CSRF_DETECTED); }

$id = $_GET['id'];
$pres = Presentation::find($id);
$name = $pres->get_name();
$pres->delete();

set_success_msg("Presentation \"$name\" successfully deleted.");

if(!isset($_GET['from_pres_page']))
  redirect_back();
else
  redirect_to("presentation_all.php");

?>