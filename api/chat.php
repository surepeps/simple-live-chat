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

    if ($s == "typingstatus") {

        $from_id = Sh_Secure($_POST['from_id']);
        $to_id = Sh_Secure($_POST['to_id']);
        $type = Sh_Secure($_POST['is_type']);

        $pData = array(
            'from_id' => $from_id,
            'to_id' => $to_id
        );

        Sh_updateTypingStatus($type,$pData);

        $data = array(
            'typing_status' => $type,
            'from_id' => $from_id,
            'to_id' => $to_id
        );

        header("Content-type: application/json");
        echo json_encode($data);
        exit();

    }

    if ($s == "gettypingstatus") {

        $from_id = Sh_Secure($_POST['from_id']);
        $to_id = Sh_Secure($_POST['to_id']);

        $typingData = Sh_getWhoisTyping($from_id,$to_id);

        if ($typingData) {

            $data = array(
                'typing_status' => 1,
                'from_id' => $typingData['from_id'],
                'to_id' => $typingData['to_id']
            );
            
        }else{
            $data = array(
                'typing_status' => 0,
                'from_id' => 0,
                'to_id' => 0
            );
        }

        header("Content-type: application/json");
        echo json_encode($data);
        exit();

    }

}



?>