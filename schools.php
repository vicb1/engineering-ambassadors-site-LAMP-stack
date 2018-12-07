<?php
require_once 'internal/helpers/includes.php';
?>

<!--  STEP ONE: insert path to SWFObject JavaScript -->
<script type="text/javascript" src="js/swfobject.js"></script>
<!--  STEP TWO: configure SWFObject JavaScript and embed CU3ER slider -->
<script type="text/javascript">
  var flashvars = {};
  flashvars.xml = "flash/config.xml";
  var attributes = {};
  attributes.wmode = "transparent";
  attributes.id = "slider";
  swfobject.embedSWF("flash/cu3er.swf", "cu3er-container", "960", "640", "9", "flash/expressInstall.swf", flashvars, attributes);
</script>

<div class = "cube1">
  <div id="cu3er-container">
    <a href="http://www.adobe.com/go/getflashplayer">
    <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
    </a>
  </div>
</div>
<div id="content">
  <div id="content_cen">
    <div id="content_sup" class="head_pad">
      <div id="welcom_pan">
        <br>
        <br>
        <br>
        <br>
        <br>
        <h3><span>EA</span> Testimonials</h3>
        <div style ="width:25%; float:right;border:solid 0px black">
          <br>Call: (518) 276-6245
          <br>Email: <a href="mailto:herkee2@rpi.edu">herkee2@rpi.edu</a>
        </div>
        <p><b>Here are some testimonials from our previous trips:</b></p>
        <p>"<i>When can you come back?</i>"
        <p>"<i>Can we do this every semester?</i>"
        <p>"<i>The field trip (to RPI)  was probably the highlight of my Human Anatomy and Physiology course.  The kids actually said it was 
          the best field trip they had ever been on (they’re Seniors!).  I told them that was all due to your organization.</i>"
        <p><b>We also can do a Q&A panel in order to answer any questions about engineering or college in general that your students may have:</b></p>
        <p>"<i>The students enjoyed listening to the college students' panel. I really appreciated the variety of students, both male and female,
          various ages and differing interests. This really allowed our students to hear from at least one student who shared similar interests. 
          It was also nice for the students who are not planning to study engineering but who will be going to college. Much of what the students
           shared is relevant to any major. Hearing about the co-op opportunities was neat. Both of the young women had engineering co-ops that 
           were not what a high school student might think of as an engineering co-op. Their co-ops highlighted that engineering is part of many 
           facets in the business and private sector.</i>"
        <div id="welcom_pan">
          <h2><span>AREA SCHOOLS</span> Host a Program</h2>
          <a class="brochure" href="http://engineeringambassadors.union.rpi.edu/downloads/schools.pdf"><span>Register</span></a>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <h3><span>Schools</span> Visited</h3>
        <iframe width="800" height="450" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps/ms?msa=0&amp;msid=209017247025151739574.0004d9ceeec5c6a56aa18&amp;ie=UTF8&amp;t=m&amp;ll=42.803462,-73.749847&amp;spn=0.453406,1.098633&amp;z=10&amp;output=embed"></iframe>
      </div>
    </div>
  </div>
</div>

<?php
require_once 'internal/helpers/footer.php';
?>