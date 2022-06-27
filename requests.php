<?php

require_once('includes/init.php');

$f = '';
$s = '';

if (isset($_GET['f'])) {
    $f = Sh_Secure($_GET['f'], 0);
}
if (isset($_GET['s'])) {
    $s = Sh_Secure($_GET['s'], 0);
}

$hash_id = '';
if (!empty($_POST['hash_id'])) {
    $hash_id = $_POST['hash_id'];
} else if (!empty($_GET['hash_id'])) {
    $hash_id = $_GET['hash_id'];
} else if (!empty($_GET['hash'])) {
    $hash_id = $_GET['hash'];
} else if (!empty($_POST['hash'])) {
    $hash_id = $_POST['hash'];
}

$data = array();

$allow_array     = array(
    'upgrade',
);
$non_login_array = array(
    'session_status',
    'get_welcome_users',
    'confirm_user_unusal_login',
    'confirm_user',
    'confirm_sms_user',
    'resned_code',
    'resned_code_ac',
    'resned_ac_email',
    'login',
    'register',
    'recover',
    'recoversms',
    'reset_password',
);

$files = scandir('api');
unset($files[0]);
unset($files[1]);

include 'api/' . $f . '.php';

mysqli_close($sqlConnect);
unset($sc);
exit();
