<?php

@ini_set('session.cookie_httponly',1);
@ini_set('session.use_only_cookies',1);
@header("X-FRAME-OPTIONS: SAMEORIGIN");
if (!version_compare(PHP_VERSION, '5.5.0', '>=')) {
    exit("Required PHP_VERSION >= 5.5.0 , Your PHP_VERSION is : " . PHP_VERSION . "\n");
}

date_default_timezone_set('UTC');
session_start();

@ini_set('gd.jpeg_ignore_warning', 1);



require_once('general_functions.php');
require_once('tables.php');
require_once('function_one.php');


// if (!empty($_GET['ref']) && $sc['loggedin'] == false) {
//     $get_ip = get_ip_address();
//     if (!isset($_SESSION['ref']) && !empty($get_ip)) {
//         $_GET['ref'] = Sh_Secure($_GET['ref']);
//         $ref_user_id = Sh_UserIdFromUsername($_GET['ref']);
//         $user_data = Sh_UserData($ref_user_id);
//         if (!empty($user_data)) {
//                 $_SESSION['ref'] = $user_data['username'];
//         }
//     }
// }
