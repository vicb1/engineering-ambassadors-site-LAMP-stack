<?php

require_once 'internal/helpers/includes_no_header.php';

if(!User::is_logged_in()) { 
  header('HTTP/1.0 401 Unauthorized'); echo("401 UNAUTHORIZED"); die(); 
}

require_once 'internal/helpers/header.php';

?>

<link href="css/fullcalendar.css" rel="stylesheet">
<link href="css/fullcalendar.print.css" rel="stylesheet">

<div id="content">
  <div id="content_cen">
    <div id="content_sup">

      <div id="welcom_pan" style="margin-top:200px; width:100%">
  
        <h1>Engineering Ambassadors Full Calendar</h1>
        
        <div id='calendar' style="width:100%;height:640px;"></div>

      </div>

    </div>
  </div>
</div>

<?php
require_once 'internal/helpers/footer.php';
?>

<script src="js/fullcalendar.js"></script>
<script src="js/fullcalendar.min.js"></script>
<script src="js/gcal.js"></script>

<script>

$(document).ready(function() {

    $('#calendar').fullCalendar({
      header: 
      {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      height: 640,
      events: 'https://www.google.com/calendar/feeds/rpiengineeringambassadors%40gmail.com/private-aabb3935c81c19f60f11dbcce04e305c/basic'
    });
});

</script>
