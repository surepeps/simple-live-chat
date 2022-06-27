<?php

 ini_set('display_errors', 0);
 ini_set('display_startup_errors', 0);
error_reporting(1);

@ini_set('max_execution_time', 0);
require_once('config.php');
require_once('libraries/DB/vendor/autoload.php');

// get all sourceher values
$sc = array();

// database settings
$sqlConnect = $sc['sqlConnect'] = mysqli_connect($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name, 3306);

// Handling Server Errors
$ServerErrors = array();
if (mysqli_connect_errno()) {
    $ServerErrors[] = "Failed to connect to MySQL: " . mysqli_connect_error();
}
if (!function_exists('curl_init')) {
    $ServerErrors[] = "PHP CURL is NOT installed on your web server !";
}
if (!extension_loaded('gd') && !function_exists('gd_info')) {
    $ServerErrors[] = "PHP GD library is NOT installed on your web server !";
}
if (!extension_loaded('zip')) {
    $ServerErrors[] = "ZipArchive extension is NOT installed on your web server !";
}

// LoggediN checker
$sc['loggedin'] = true;

$query = mysqli_query($sqlConnect, "SET NAMES utf8mb4");
if (isset($ServerErrors) && !empty($ServerErrors)) {
    foreach ($ServerErrors as $Error) {
        echo "<h3>" . $Error . "</h3>";
    }
    die();
}

$config =  Sh_GetConfig();

$db = new MysqliDb($sqlConnect);

// config values
$sc['config']  = $config;


// database name
$sc['dbname'] = $sql_db_name;


// LoggediN checker
$sc['loggedin'] = false;

if (Sh_IsLogged() == true) {
    $session_id         = (!empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : $_COOKIE['user_id'];
    $sc['user_session'] = Sh_GetUserFromSessionID($session_id);
    $sc['user'] = Sh_UserData($sc['user_session']);
    if (!empty($sc['user']['language'])) {
        if (in_array($sc['user']['language'], $langs)) {
            $_SESSION['lang'] = $sc['user']['language'];
        }
    }
    if ($sc['user']['user_id'] < 0 || empty($sc['user']['user_id']) || !is_numeric($sc['user']['user_id']) || Sh_UserActive($sc['user']['username']) === false) {
        header("Location: " . Sh_Link("logout"));
    }
    $sc['loggedin'] = true;
}

if (!empty($_GET['c_id']) && !empty($_GET['user_id'])) {
    $application = 'windows';
    if (!empty($_GET['application'])) {
        if ($_GET['application'] == 'phone') {
            $application = Sh_Secure($_GET['application']);
        }
    }
    $c_id             = Sh_Secure($_GET['c_id']);
    $user_id          = Sh_Secure($_GET['user_id']);
    $check_if_session = Sh_CheckUserSessionID($user_id, $c_id, $application);
    if ($check_if_session === true) {
        $sc['user']          = Sh_UserData($user_id);
        $session             = Sh_CreateLoginSession($user_id);
        $_SESSION['user_id'] = $session;
        setcookie("user_id", $session, time() + (10 * 365 * 24 * 60 * 60));
        if ($sc['user']['user_id'] < 0 || empty($sc['user']['user_id']) || !is_numeric($sc['user']['user_id']) || Sh_UserActive($sc['user']['username']) === false) {
            header("Location: " . Sh_Link("logout"));
        }
        $sc['loggedin'] = true;
    }
}
