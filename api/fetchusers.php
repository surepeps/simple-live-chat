<?php
if ($f == 'fetchusers') {

    $myuser_id = $sc['user']['user_id'];

    $usersLogged = getAllLoggedInUsers($myuser_id);
 
    if ($usersLogged > 0) {
        
        $data = $usersLogged;

    }else{

        $data = array(
            "status" => 400,
            "message" => "Sorry No User Found"
        );

    }
    

    header("Content-type: application/json");
    echo json_encode($data);
    exit();

}