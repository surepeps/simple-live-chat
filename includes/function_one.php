<?php

require_once('app_start.php');


function Sh_UserData($user_id, $password = true) {
    global $sc, $sqlConnect, $cache;
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $data = array();
    $user_id  = Sh_Secure($user_id);
    $query_one      = "SELECT * FROM " . T_USERS . " WHERE `user_id` = {$user_id}";
    $hashed_user_Id = md5($user_id);


    if ($sc['config']['cacheSystem'] == 1) {
        $fetched_data = $cache->read($hashed_user_Id . '_U_Data.tmp');
        if (empty($fetched_data)) {
            $sql          = mysqli_query($sqlConnect, $query_one);
            $fetched_data = mysqli_fetch_assoc($sql);
            $cache->write($hashed_user_Id . '_U_Data.tmp', $fetched_data);
        }
    } else {
        $sql          = mysqli_query($sqlConnect, $query_one);
        $fetched_data = mysqli_fetch_assoc($sql);
    }


    if (empty($fetched_data)) {
        return array();
    }
    if ($password == false) {
        unset($fetched_data['password']);
    }
    $fetched_data['avatar_org'] = $fetched_data['avatar'];

    $explode2                   = @end(explode('.', $fetched_data['avatar']));
    $explode3                   = @explode('.', $fetched_data['avatar']);

    if ($fetched_data['avatar'] != $sc['userDefaultAvatar']) {
        @$fetched_data['avatar_full'] = $explode3[0] . '_full.' . $explode2;
    }
    
    $fetched_data['avatar'] = Sh_GetMedia($fetched_data['avatar']) . '?cache=' . $fetched_data['date_created'];
    // $fetched_data['cover']  = Sh_GetMedia($fetched_data['cover']) . '?cache=' . $fetched_data['last_cover_mod'];
    $fetched_data['id']     = $fetched_data['user_id'];
    $fetched_data['name']   = '';
    if (!empty($fetched_data['first_name'])) {
        if (!empty($fetched_data['last_name'])) {
            $fetched_data['name'] = $fetched_data['first_name'] . ' ' . $fetched_data['last_name'];
        } else {
            $fetched_data['name'] = $fetched_data['first_name'];
        }
    } else {
        $fetched_data['name'] = $fetched_data['username'];
    }


    return $fetched_data;
}


function Sh_GetPlatformFromUser_ID($user_id = 0) {
    global $sqlConnect;
    if (empty($user_id)) {
        return false;
    }
    $user_id = Sh_Secure($user_id);
    $query   = mysqli_query($sqlConnect, "SELECT `platform` FROM " . T_APP_SESSIONS . " WHERE `user_id` = '{$user_id}' ORDER BY `time` DESC LIMIT 1");
    $mysqli  = mysqli_fetch_assoc($query);
    return $mysqli['platform'];
}

function Sh_UserActive($username) {
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Sh_Secure($username);
    $query    = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) FROM " . T_USERS . "  WHERE (`username` = '{$username}' OR `email` = '{$username}') AND `active` = '1'");
    return (Sh_Sql_Result($query, 0) == 1) ? true : false;
}
function Sh_UserInactive($username) {
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Sh_Secure($username);
    $query    = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) FROM " . T_USERS . "  WHERE (`username` = '{$username}' OR `email` = '{$username}') AND `active` = '2'");
    return (Sh_Sql_Result($query, 0) == 1) ? true : false;
}
function Sh_UserExists($username) {
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Sh_Secure($username);
    $query    = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `username` = '{$username}'");
    return (Sh_Sql_Result($query, 0) == 1) ? true : false;
}

function Sh_GetMedia($media) {
    global $sc;
    if (empty($media)) {
        return '';
    }
    if ($sc['config']['amazone_s3'] == 1) {
        if (empty($sc['config']['amazone_s3_key']) || empty($sc['config']['amazone_s3_s_key']) || empty($sc['config']['region']) || empty($sc['config']['bucket_name'])) {
            return $sc['config']['site_url'] . '/' . $media;
        }
        return $sc['config']['s3_site_url'] . '/' . $media;
    } else if ($sc['config']['spaces'] == 1) {
        if (empty($sc['config']['spaces_key']) || empty($sc['config']['spaces_secret']) || empty($sc['config']['space_region']) || empty($sc['config']['space_name'])) {
            return $sc['config']['site_url'] . '/' . $media;
        }
        return  'https://' . $sc['config']['space_name'] . '.' . $sc['config']['space_region'] . '.digitaloceanspaces.com/' . $media;
    } else if ($sc['config']['ftp_upload'] == 1) {
        return addhttp($sc['config']['ftp_endpoint']) . '/' . $media;

    } else if ($sc['config']['cloud_upload'] == 1) {
        return 'https://storage.cloud.google.com/'. $sc['config']['cloud_bucket_name'] . '/' . $media;
    }
    return $sc['config']['site_url'] . '/' . $media;
}

function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

function Sh_EmailExists($email) {
    global $sqlConnect;
    if (empty($email)) {
        return false;
    }
    $email = Sh_Secure($email);
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `email` = '{$email}'");
    return (Sh_Sql_Result($query, 0) == 1) ? true : false;
}

function Sh_RegisterUser($registration_data) {
    global $sc, $sqlConnect;

    if (empty($registration_data)) {
        return false;
    }

    $registration_data['password']   = Sh_Secure(password_hash($registration_data['password'], PASSWORD_DEFAULT));


    $fields  = '`' . implode('`,`', array_keys($registration_data)) . '`';
    $data    = '\'' . implode('\', \'', $registration_data) . '\'';
    $query   = mysqli_query($sqlConnect, "INSERT INTO " . T_USERS . " ({$fields}) VALUES ({$data})");
    $user_id = mysqli_insert_id($sqlConnect);

    if ($query) {
        return $user_id;
    } else {
        return 0;
    }

}


function Sh_SaveMessage($Fdata){

    global $sc, $sqlConnect;

    if (empty($Fdata)) {
        return false;
    }

    $fields  = '`' . implode('`,`', array_keys($Fdata)) . '`';
    $data    = '\'' . implode('\', \'', $Fdata) . '\'';
    $query   = mysqli_query($sqlConnect, "INSERT INTO " . T_MESSAGES . " ({$fields}) VALUES ({$data})");
    $msg_id = mysqli_insert_id($sqlConnect);

    if ($query) {
        return $msg_id;
    } else {
        return 0;
    }

}


function Sh_updateTypingStatus($type,$Fdata){
    global $sqlConnect;

    if (empty($Fdata)) {
        return false;
    }

    $from_id = $Fdata['from_id'];
    $to_id = $Fdata['to_id'];

    if($type == 1){
        // check if it is existing 
        if(!getypingStatus($from_id,$to_id)){

            $fields  = '`' . implode('`,`', array_keys($Fdata)) . '`';
            $data    = '\'' . implode('\', \'', $Fdata) . '\'';
            $query   = mysqli_query($sqlConnect, "INSERT INTO " . T_TYPING_STATUS . " ({$fields}) VALUES ({$data})");

        }
        
    }else{
    
        $query = mysqli_query($sqlConnect, "DELETE FROM " . T_TYPING_STATUS . " WHERE `from_id` = $from_id AND `to_id` = $to_id ");
    }

    return true;

}

function Sh_getWhoisTyping($from_id, $to_id){
    global $sqlConnect;

    if (empty($from_id)) {
        return false;
    }

    if (empty($to_id)) {
        return false;
    }

    $query = "SELECT * FROM " . T_TYPING_STATUS . "  WHERE ( (`from_id` = $from_id AND `to_id` = $to_id ) OR (`from_id` = $to_id AND `to_id` = $from_id) ) ORDER BY `id` DESC LIMIT 1 ";

    $result = mysqli_query($sqlConnect, $query);
    $fetched_data  = mysqli_fetch_assoc($result);

    return $fetched_data;
}

function getypingStatus($from_id, $to_id){
    global $sqlConnect;

    if (empty($from_id)) {
        return false;
    }

    if (empty($to_id)) {
        return false;
    }

    $query    = mysqli_query($sqlConnect, "SELECT COUNT(`id`) FROM " . T_TYPING_STATUS . "  WHERE `from_id` = $from_id AND `to_id` = $to_id ");
    return (Sh_Sql_Result($query, 0) == 1) ? true : false;
}

function Sh_Login($username, $password) {
    global $sqlConnect;
    if (empty($username) || empty($password)) {
        return false;
    }
    $username            = Sh_Secure($username);
    $query_hash          = mysqli_query($sqlConnect, "SELECT * FROM " . T_USERS . " WHERE (`username` = '{$username}' OR `email` = '{$username}')");
    $mysqli_hash_upgrade = mysqli_fetch_assoc($query_hash);
    $login_password = '';
    $hash                = 'md5';
    if (preg_match('/^[a-f0-9]{32}$/', $mysqli_hash_upgrade['password'])) {
        $hash = 'md5';
    } else if (preg_match('/^[0-9a-f]{40}$/i', $mysqli_hash_upgrade['password'])) {
        $hash = 'sha1';
    } else if (strlen($mysqli_hash_upgrade['password']) == 60) {
        $hash = 'password_hash';
    }
    if ($hash == 'password_hash') {
        if (password_verify($password, $mysqli_hash_upgrade['password'])) {
            return true;
        }
    } else {
        $login_password = Sh_Secure($hash($password));
    }		

    $query          = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE (`username` = '{$username}' OR `email` = '{$username}') AND `password` = '{$login_password}'");
    if (Sh_Sql_Result($query, 0) == 1) {
        if ($hash == 'sha1' || $hash == 'md5') {
            $new_password = Sh_Secure(password_hash($password, PASSWORD_DEFAULT));
            $query_       = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET password = '$new_password' WHERE (`username` = '{$username}' OR `email` = '{$username}')");
        }
        return true;
    }
    return false;
}

function Sh_UserIdForLogin($username) {
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username =   Sh_Secure($username);
    $query    = mysqli_query($sqlConnect, "SELECT `user_id` FROM " . T_USERS . " WHERE `username` = '{$username}' OR `email` = '{$username}' ");
    return Sh_Sql_Result($query, 0, 'user_id');
}

function Sh_CreateLoginSession($user_id = 0) {
    global $sqlConnect, $db;
    if (empty($user_id)) {
        return false;
    }
    $user_id   = Sh_Secure($user_id);
    $hash      = sha1(rand(111111111, 999999999)) . md5(microtime()) . rand(11111111, 99999999) . md5(rand(5555, 9999));
    $query_two = mysqli_query($sqlConnect, "DELETE FROM " . T_APP_SESSIONS . " WHERE `session_id` = '{$hash}'");
    if ($query_two) {
        $ua = json_encode(getBrowser());
        $delete_same_session = $db->where('user_id', $user_id)->where('platform_details', $ua)->delete(T_APP_SESSIONS);
        $query_three = mysqli_query($sqlConnect, "INSERT INTO " . T_APP_SESSIONS . " (`user_id`, `session_id`, `platform`, `platform_details`, `time`) VALUES('{$user_id}', '{$hash}', 'web', '$ua'," . time() . ")");
        if ($query_three) {
            return $hash;
        }
    }
}

function getBrowser() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
    // First get the platform?
    if (preg_match('/macintosh|mac os x/i', $u_agent)) {
      $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
      $platform = 'windows';
    } elseif (preg_match('/iphone|IPhone/i', $u_agent)) {
      $platform = 'IPhone Web';
    } elseif (preg_match('/android|Android/i', $u_agent)) {
      $platform = 'Android Web';
    } else if (preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $u_agent)) {
      $platform = 'Mobile';
    } else if (preg_match('/linux/i', $u_agent)) {
      $platform = 'linux';
    }
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
      $bname = 'Internet Explorer';
      $ub = "MSIE";
    } elseif(preg_match('/Firefox/i',$u_agent)) {
      $bname = 'Mozilla Firefox';
      $ub = "Firefox";
    } elseif(preg_match('/Chrome/i',$u_agent)) {
      $bname = 'Google Chrome';
      $ub = "Chrome";
    } elseif(preg_match('/Safari/i',$u_agent)) {
      $bname = 'Apple Safari';
      $ub = "Safari";
    } elseif(preg_match('/Opera/i',$u_agent)) {
      $bname = 'Opera';
      $ub = "Opera";
    } elseif(preg_match('/Netscape/i',$u_agent)) {
      $bname = 'Netscape';
      $ub = "Netscape";
    }
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
      // we have no matching number just continue
    }
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
      //we will have two since we are not using 'other' argument yet
      //see if version is before or after the name
      if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
        $version= $matches['version'][0];
      } else {
        $version= $matches['version'][1];
      }
    } else {
      $version= $matches['version'][0];
    }
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern,
        'ip_address' => get_ip_address()
    );
}

function get_ip_address() {
    if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
        return $_SERVER['HTTP_X_FORWARDED'];
    if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
        return $_SERVER['HTTP_FORWARDED_FOR'];
    if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
        return $_SERVER['HTTP_FORWARDED'];
    return $_SERVER['REMOTE_ADDR'];
}

function validate_ip($ip) {
    if (strtolower($ip) === 'unknown')
        return false;
    $ip = ip2long($ip);
    if ($ip !== false && $ip !== -1) {
        $ip = sprintf('%u', $ip);
        if ($ip >= 0 && $ip <= 50331647)
            return false;
        if ($ip >= 167772160 && $ip <= 184549375)
            return false;
        if ($ip >= 2130706432 && $ip <= 2147483647)
            return false;
        if ($ip >= 2851995648 && $ip <= 2852061183)
            return false;
        if ($ip >= 2886729728 && $ip <= 2887778303)
            return false;
        if ($ip >= 3221225984 && $ip <= 3221226239)
            return false;
        if ($ip >= 3232235520 && $ip <= 3232301055)
            return false;
        if ($ip >= 4294967040)
            return false;
    }
    return true;
}

function getAllLoggedInUsers($myId = ""){
    global $sc, $sqlConnect;


    // $query = "SELECT * FROM ". T_APP_SESSIONS. "  ";
    $query = "SELECT * FROM ". T_APP_SESSIONS. " WHERE `user_id` != $myId ";
    $result = mysqli_query($sqlConnect, $query);
    while($user_data = mysqli_fetch_assoc($result)){
        $users[] = array(
            "userdata" => Sh_UserData($user_data['user_id']),
            "latest_message" => getLastMessageBetweenTwo($myId, $user_data['user_id'])
        ); 
    }
    
    return $users;
  
  }

  function getLastMessageBetweenTwo($from,$to){
    global $sqlConnect;

    $query = "SELECT * FROM ". T_MESSAGES ." WHERE (`from_id` = $from AND `to_id` = $to) OR (`from_id` = $to AND `to_id` = $from )  AND `status` = 1 ORDER BY `msg_id` DESC LIMIT 1";
    $result = mysqli_query($sqlConnect, $query);
    $fetched_data  = mysqli_fetch_assoc($result);

    return $fetched_data;
    
  }

  function getAllMessagesFromUser($with_id,$my_id){
    global $sqlConnect;

    if (empty($with_id)) {
        return false;
    }

    if (empty($my_id)) {
        return false;
    }

    // $query = "SELECT * FROM ". T_MESSAGES ." a RIGHT JOIN ". T_USERS ." b ON a.`"
    // $query = "SELECT * FROM ". T_APP_SESSIONS. "  ";
    $query = "SELECT * FROM ". T_MESSAGES. " WHERE ( (`from_id` = $with_id AND `to_id` = $my_id) OR (`from_id` = $my_id AND `to_id` = $with_id)) AND `status` = 1";
    $result = mysqli_query($sqlConnect, $query);
    while($response_data = mysqli_fetch_assoc($result)){
        $messages[] = $response_data; 
    }
    
    return $messages;

  }

  function Sh_IsNameExist($username, $active = 0) {
    global $sc, $sqlConnect;
    $data = array();
 
    if (empty($username)) {
        return false;
    }
 
    $active_text = '';
    if ($active == 1) {
        $active_text = "AND `active` = 1 ";
    }
    $username = Sh_Secure($username);
 
    $query   = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) as users FROM " . T_USERS . " WHERE `username` = '{$username}' {$active_text}");
    $fetched_data = mysqli_fetch_assoc($query);
    
    if ($fetched_data['users'] > 0) {
        return array(
            true,
            'type' => 'user'
        );
    }
  
     return array(
        false
    );
 }

 function Sh_UserIdFromUsername($username) {
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Sh_Secure($username);
    $query    = mysqli_query($sqlConnect, "SELECT `user_id` FROM " . T_USERS . " WHERE `username` = '{$username}'");
    return Sh_Sql_Result($query, 0, 'user_id');
}
