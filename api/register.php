<?php
  if ($f == "register") {

    if ($s == "submit_register") {

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

      $error = 0;

      $newForm  = array();
      foreach ($_POST as $field) {
        if (empty($field) || !isset($field)) {
          $errors = "Please fill all fields";
          $error = 1;
        }else{
          $newForm[] = $field;
        }
      }

      if (Sh_EmailExists($_POST['email']) === true) {
          $errors = "Email address already exist";
      }

      if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
          $errors = "Invalid Email address";
      }


      // upload image
      $filename_cb = "";
      if (isset($_FILES['profile_img']) && !empty($_FILES['profile_img'])) {

           if (!empty($_FILES['profile_img']["tmp_name"])) {

               $orignalname_cb = $_FILES['profile_img']["name"];
               $fileInfo_cb = array(
                 'file' => $_FILES["profile_img"]["tmp_name"],
                 'name' => $_FILES['profile_img']['name'],
                 'size' => $_FILES["profile_img"]["size"],
                 'type' => $_FILES["profile_img"]["type"],
                 'types' => 'jpeg,jpg,png,gif',
               );

               $media_cb = Sh_ShareFile($fileInfo_cb, 0, false);
               if (!empty($media_cb)) {

                 $filename_cb = $media_cb['filename'];

               }

           }else {
             $errors = "Please provide your profile image";
           }

       }else{
         $errors = "Please provide your profile image";
       }

      if (empty($errors)) {


          $code = md5(rand(1111, 9999) . time());

           $re_data  = array(
               'first_name' => Sh_Secure($_POST['first_name']),
               'last_name' => Sh_Secure($_POST['last_name']),
               'email' => Sh_Secure($_POST['email'], 0),
               'username' => Sh_Secure($_POST['username'], 0),
               'password' => $_POST['password'],
               'email_code' => Sh_Secure($code, 0),
               'avartar' => $filename_cb,
           );

          $register = Sh_RegisterUser($re_data);

          if ($register > 0) {

            $data  = array(
                'status' => 200,
                'user_id' => $register,
                'location' => Sh_Link('login'),
                'message' => "Account Successfully created "
            );

          }else {

            $errors = "Sorry System could not process your request ";

          }


        }


        header("Content-type: application/json");
        if (isset($errors) || !empty($errors)) {
            echo json_encode(array(
                'errors' => $errors
            ));
        } else {
            echo json_encode($data);
        }
        exit();


    }

  }

 ?>
