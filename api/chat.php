<?php
if ($f == "chat") {
    
    if ($s == "sendmessage") {
        
        $sender_id = Sh_Secure($_POST['sender_id']);
        $receiver_id = Sh_Secure($_POST['reciever_id']);
        $msg = Sh_Secure($_POST['message']);

        $messageData = array(
            'from_id' => $sender_id,
            'to_id' => $receiver_id,
            'message' => $msg,
            'status' => 1
        );

        $saveMessage = Sh_SaveMessage($messageData);
        if ($saveMessage > 0) {
            $data = array(
                "status" => 200,
                "message" => "Successfully sent"
            );
        }else {
            $data = array(
                "status" => 400,
                "message" => "Message could not send"
            );
        }

        header("Content-type: application/json");
        echo json_encode($data);
        exit();



    }

    if ($s == "getallmessages") {
        
        $my_id = Sh_Secure($_POST['sender_id']);
        $r_id = Sh_Secure($_POST['reciever_id']);

        $AllMessages = getAllMessagesFromUser($r_id,$my_id);

        if ($AllMessages) {
            $data = $AllMessages;
        }else{
            $data = [];
        }

        header("Content-type: application/json");
        echo json_encode($data);
        exit();


    }

}



?>