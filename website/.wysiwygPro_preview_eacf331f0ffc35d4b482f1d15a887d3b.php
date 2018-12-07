<?php
if ($_GET['randomId'] != "CpLfIHDPaLHrceo4rnUjNo_vzAwLvFKgPnjwFJbqf9cSLeWJ0ZSlKnlPaZoixRYW") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
