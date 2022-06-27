<?php
if ($sc['loggedin'] == false) {
    header("Location: " . Sh_Link('login'));
    exit();
}

if (isset($_GET['u'])) {
  // check if username exist
  $check_user = Sh_IsNameExist($_GET['u'], 1);

  if (in_array(true, $check_user)) {
    // it means the username is found and most likely active or inactive

    $id = $user_id = Sh_UserIdFromUsername($_GET['u']);
    $sc['user_msg'] = Sh_UserData($user_id);

    $myuser_id = $sc['user']['user_id'];

    // get all conversation between this user
    $convers = getAllMessagesFromUser($user_id,$myuser_id);

    $type               = 'chat';
    $about              = "Conversation with ".$sc['user_msg']['name'];
    $name               = $sc['user_msg']['name'];
    $sc['user_msg']['conversation'] = $convers;
    
  }else {
    $type               = '404';
    $about              = "Error 404";
    $name               = "404";
  }

}else{

  header("Location: " . $sc['config']['site_url'].'/dashboard');
  exit();

}

if (!empty($_GET['type']) && in_array($_GET['type'], array('activities','mutual_friends','following','followers','videos','photos','likes','groups','family_list','requests'))) {
    $name = $name ." | ".Sh_Secure($_GET['type']);
}

$sc['description'] = $about;
$sc['keywords']    = '';
$sc['page']        = $type;
$sc['title']       = str_replace('&#039;', "'", $name);
$sc['content']     = Sh_LoadPage("$type/content");
