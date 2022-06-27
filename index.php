<?php

require_once('includes/init.php');

// if (!empty($_GET['ref']) && $sc['loggedin'] == false && !isset($_COOKIE['src'])) {
//     $get_ip = get_ip_address();
//     if (!isset($_SESSION['ref']) && !empty($get_ip)) {
//         $_GET['ref'] = Sh_Secure($_GET['ref']);
//         $ref_user_id = Sh_UserIdFromUsername($_GET['ref']);
//         $user_data = Sh_UserData($ref_user_id);
//         if (!empty($user_data)) {
//             if (ip_in_range($user_data['ip_address'], '/24') === false && $user_data['ip_address'] != $get_ip) {
//                 $_SESSION['ref'] = $user_data['username'];
//             }
//         }
//     }
// }

if (!isset($_COOKIE['src'])) {
    @setcookie('src', '1', time() + 31556926, '/');
}
$page = '';

if ($sc['loggedin'] == true && !isset($_GET['link1'])) {
    $page = 'dashboard';
} elseif (isset($_GET['link1'])) {
    $page = $_GET['link1'];
}



if ((!isset($_GET['link1']) && $sc['loggedin'] == false) || (isset($_GET['link1']) && $sc['loggedin'] == false && $page == 'home')) {
    $page = 'login';
}


if ($sc['loggedin'] == true) {

    switch ($page) {
      case 'dashboard':
        include('sources/dashboard.php');
        break;
      case 'chat':
        include('sources/chat.php');
        break;
      case 'logout':
        include('sources/logout.php');
        break;
      case '404':
        include('sources/404.php');
        break;
    }

}else{

  switch ($page) {
    case 'register':
      include('sources/register.php');
      break;
    case 'login':
      include('sources/login.php');
      break;
    case '404':
      include('sources/404.php');
      break;
    case 'dashboard':
      include('sources/dashboard.php');
      break;
  }

}

if ( empty($sc['content']) ) {
  include('sources/404.php');
}


if ($page == "register" || $page == "login"){
  echo Sh_LoadPage('login_container');
}else{
  echo Sh_LoadPage('container');
} 





mysqli_close($sqlConnect);
unset($sc);
?>
