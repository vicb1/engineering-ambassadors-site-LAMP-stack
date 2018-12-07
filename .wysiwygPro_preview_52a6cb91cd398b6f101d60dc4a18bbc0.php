<?php
if ($_GET['randomId'] != "oHZ2UiDYQ33ex9zSJvYf3ee2KAD8B0Ho2e6leUYfPhSxtYxA93QfnkPsXRS8ZL7N") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
