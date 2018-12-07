<?php

//List out a common set of php files that must be included at the top of each page. This doesn't include the header, however.
//If you want to add any new utility PHP files you need to specify them here.

require_once 'path.php';
require_once 'configuration.php';
require_once 'functions.php';
require_once 'session.php';
require_once 'user_api.php';
//require_once 'google_api.php';
require_once 'validator.php';

//Require every file in models
foreach (scandir(Path::model()) as $filename) {
    $path = Path::model() . $filename;
    if (is_file($path)) {
        require_once $path;
    }
}

require_once 'auth_setup.php';
require_once 'echoers.php';

?>