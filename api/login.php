<?php
if ($f == 'login') {

    $error_icon = "<i class='fa fa-close'></i>";

    if (!empty($_SESSION['user_id'])) {
        $_SESSION['user_id'] = '';
        unset($_SESSION['user_id']);
    }

    if (!empty($_COOKIE['user_id'])) {
        $_COOKIE['user_id'] = '';
        unset($_COOKIE['user_id']);
        setcookie('user_id', null, -1);
        setcookie('user_id', null, -1,'/');
    }

    if (isset($_POST['username']) && isset($_POST['password'])) {

        $username = Sh_Secure($_POST['username']);
        $password = $_POST['password'];
        $result   = Sh_Login($username, $password);

        if ($result === false) {
            $errors[] = $error_icon . " Incorrect Email or Password";
        } else if (Sh_UserInactive($_POST['username']) === true) {
            $errors[] = $error_icon . " Sorry your account has been disabled.";
        }
        
        if (empty($errors)) {
            $userid              = Sh_UserIdForLogin($username);
            $session             = Sh_CreateLoginSession(Sh_UserIdForLogin($username));
            $_SESSION['user_id'] = $session;
            setcookie("user_id", $session, time() + (10 * 365 * 24 * 60 * 60));
            setcookie('ad-con', htmlentities(json_encode(array(
                'date' => date('Y-m-d'),
                'ads' => array()
            ))), time() + (10 * 365 * 24 * 60 * 60));

            $data = array(
                'status' => 200,
                'message' => "Successfully Logged in",
                'location' => $sc['config']['site_url']."/dashboard",
            );
            
            $user_data = Sh_UserData($userid);

        }
    }


    header("Content-type: application/json");

    if (!empty($errors)) {
        echo json_encode(array(
            'errors' => $errors
        ));
    } else {
        echo json_encode($data);
    }

    exit();
}
