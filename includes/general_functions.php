<?php

function Sh_LoadPage($page_url = ""){
    global $sc,  $db;

    $create_file = false;
    $page         = './themes/layout/' . $page_url . '.phtml';
    $page_content = '';
    ob_start();
    require($page);
    $page_content = ob_get_contents();
    ob_end_clean();
    return $page_content;


}


function Sh_GetConfig() {
    global $sqlConnect;
    $data  = array();
    $query = mysqli_query($sqlConnect, "SELECT * FROM " . T_CONFIG);
    while ($fetched_data = mysqli_fetch_assoc($query)) {
        $data[$fetched_data['name']] = $fetched_data['value'];
    }
    return $data;
}


function Sh_IsLogged() {
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        $id = Sh_GetUserFromSessionID($_SESSION['user_id']);
        if (is_numeric($id) && !empty($id)) {
            return true;
        }
    } else if (!empty($_COOKIE['user_id']) && !empty($_COOKIE['user_id'])) {
        $id = Sh_GetUserFromSessionID($_COOKIE['user_id']);
        if (is_numeric($id) && !empty($id)) {
            return true;
        }
    } else {
        return false;
    }
}
//
function Sh_Secure($string, $censored_words = 1, $br = true, $strip = 0) {
    global $sqlConnect;
    $string = trim($string);
    $string = cleanString($string);
    $string = mysqli_real_escape_string($sqlConnect, $string);
    $string = htmlspecialchars($string, ENT_QUOTES);
    if ($br == true) {
        $string = str_replace('\r\n', " <br>", $string);
        $string = str_replace('\n\r', " <br>", $string);
        $string = str_replace('\r', " <br>", $string);
        $string = str_replace('\n', " <br>", $string);
    } else {
        $string = str_replace('\r\n', "", $string);
        $string = str_replace('\n\r', "", $string);
        $string = str_replace('\r', "", $string);
        $string = str_replace('\n', "", $string);
    }
    if ($strip == 1) {
        $string = stripslashes($string);
    }
    $string = str_replace('&amp;#', '&#', $string);
    return $string;
}

function cleanString($string) {
    return $string = preg_replace("/&#?[a-z0-9]+;/i","", $string);
}

function Sh_GetUserFromSessionID($session_id, $platform = 'web') {
    global $sqlConnect, $db;
    if (empty($session_id)) {
        return false;
    }

    $session_id = Sh_Secure($session_id);
    $query      = mysqli_query($sqlConnect, "SELECT * FROM " . T_APP_SESSIONS . " WHERE `session_id` = '{$session_id}' LIMIT 1");
    $fetched_data = mysqli_fetch_assoc($query);
    if (empty($fetched_data['platform_details']) && $fetched_data['platform'] == 'web') {
        $ua = json_encode(getBrowser());
        if (isset($fetched_data['platform_details'])) {
            $update_session = $db->where('id', $fetched_data['id'])->update(T_APP_SESSIONS, array('platform_details' => $ua));
        }
    }
    return $fetched_data['user_id'];
}

function Sh_Link($string) {
    global $site_url;
    return $site_url . '/' . $string;
}

function Sh_Sql_Result($res, $row = 0, $col = 0) {
    $numrows = mysqli_num_rows($res);
    if ($numrows && $row <= ($numrows - 1) && $row >= 0) {
        mysqli_data_seek($res, $row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])) {
            return $resrow[$col];
        }
    }
    return false;
}

function Sh_GenerateKey($minlength = 20, $maxlength = 20, $uselower = true, $useupper = true, $usenumbers = true, $usespecial = false) {
    $charset = '';
    if ($uselower) {
        $charset .= "abcdefghijklmnopqrstuvwxyz";
    }
    if ($useupper) {
        $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }
    if ($usenumbers) {
        $charset .= "123456789";
    }
    if ($usespecial) {
        $charset .= "~@#$%^*()_+-={}|][";
    }
    if ($minlength > $maxlength) {
        $length = mt_rand($maxlength, $minlength);
    } else {
        $length = mt_rand($minlength, $maxlength);
    }
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $charset[(mt_rand(0, strlen($charset) - 1))];
    }
    return $key;
}



function Sh_UploadToS3($filename, $config = array()) {
    global $sc;

    if ($sc['config']['amazone_s3'] == 0 && $sc['config']['ftp_upload'] == 0 && $sc['config']['spaces'] == 0 && $sc['config']['cloud_upload'] == 0) {
        return false;
    }

    if ($sc['config']['ftp_upload'] == 1) {
        include_once('assets/libraries/ftp/vendor/autoload.php');
        $ftp = new \FtpClient\FtpClient();
        $ftp->connect($sc['config']['ftp_host'], false, $sc['config']['ftp_port']);
        $login = $ftp->login($sc['config']['ftp_username'], $sc['config']['ftp_password']);

        if ($login) {
            if (!empty($sc['config']['ftp_path'])) {
                if ($sc['config']['ftp_path'] != "./") {
                    $ftp->chdir($sc['config']['ftp_path']);
                }
            }
            $file_path = substr($filename, 0, strrpos( $filename, '/'));
            $file_path_info = explode('/', $file_path);
            $path = '';
            if (!$ftp->isDir($file_path)) {
                foreach ($file_path_info as $key => $value) {
                    if (!empty($path)) {
                        $path .= '/' . $value . '/' ;
                    } else {
                        $path .= $value . '/' ;
                    }
                    if (!$ftp->isDir($path)) {
                        $mkdir = $ftp->mkdir($path);
                    }
                }
            }
            $ftp->chdir($file_path);
            $ftp->pasv(true);
            if ($ftp->putFromPath($filename)) {
                if (empty($config['delete'])) {
                    if (empty($config['amazon'])) {
                        @unlink($filename);
                    }
                }
                $ftp->close();
                return true;
            }
            $ftp->close();
        }
    } else if ($sc['config']['amazone_s3'] == 1){
        if (empty($sc['config']['amazone_s3_key']) || empty($sc['config']['amazone_s3_s_key']) || empty($sc['config']['region']) || empty($sc['config']['bucket_name'])) {
            return false;
        }
        include_once('assets/libraries/s3/aws-autoloader.php');
        $s3 = new S3Client([
            'version'     => 'latest',
            'region'      => $sc['config']['region'],
            'credentials' => [
                'key'    => $sc['config']['amazone_s3_key'],
                'secret' => $sc['config']['amazone_s3_s_key'],
            ]
        ]);
        $s3->putObject([
            'Bucket' => $sc['config']['bucket_name'],
            'Key'    => $filename,
            'Body'   => fopen($filename, 'r+'),
            'ACL'    => 'public-read',
            'CacheControl' => 'max-age=3153600',
        ]);
        if (empty($config['delete'])) {
            if ($s3->doesObjectExist($sc['config']['bucket_name'], $filename)) {
                if (empty($config['amazon'])) {
                    @unlink($filename);
                }
                return true;
            }
        } else {
            return true;
        }
    } else if ($sc['config']['spaces'] == 1) {
        include_once("assets/libraries/spaces/spaces.php");
        $key = $sc['config']['spaces_key'];
        $secret = $sc['config']['spaces_secret'];
        $space_name = $sc['config']['space_name'];
        $region = $sc['config']['space_region'];
        $space = new SpacesConnect($key, $secret, $space_name, $region);
        $upload = $space->UploadFile($filename, "public");
        if ($upload) {
            if (empty($config['delete'])) {
                if ($space->DoesObjectExist($filename)) {
                    if (empty($config['amazon'])) {
                        @unlink($filename);
                    }
                    return true;
                }
            } else {
                return true;
            }
            return true;
        }
    }
    elseif ($sc['config']['cloud_upload'] == 1) {
        require_once 'assets/libraries/cloud/vendor/autoload.php';

        try {
            $storage = new StorageClient([
               'keyFilePath' => $sc['config']['cloud_file_path']
            ]);
            // set which bucket to work in
            $bucket = $storage->bucket($sc['config']['cloud_bucket_name']);
            $fileContent = file_get_contents($filename);

            // upload/replace file
            $storageObject = $bucket->upload(
                                    $fileContent,
                                    ['name' => $filename]
                            );
            if (!empty($storageObject)) {
                if (empty($config['delete'])) {
                    if (empty($config['amazon'])) {
                        @unlink($filename);
                    }
                }
                return true;
            }
        } catch (Exception $e) {
            // maybe invalid private key ?
            // print $e;
            // exit();
            return false;
        }
    }
    return false;
}

function Sh_ShareFile($data = array(), $type = 0, $crop = true) {
    global $sc, $sqlConnect, $s3;

    $allowed = '';

    if (!file_exists('upload/files/' . date('Y'))) {
        @mkdir('upload/files/' . date('Y'), 0777, true);
    }
    if (!file_exists('upload/files/' . date('Y') . '/' . date('m'))) {
        @mkdir('upload/files/' . date('Y') . '/' . date('m'), 0777, true);
    }
    if (!file_exists('upload/photos/' . date('Y'))) {
        @mkdir('upload/photos/' . date('Y'), 0777, true);
    }
    if (!file_exists('upload/photos/' . date('Y') . '/' . date('m'))) {
        @mkdir('upload/photos/' . date('Y') . '/' . date('m'), 0777, true);
    }
    if (!file_exists('upload/videos/' . date('Y'))) {
        @mkdir('upload/videos/' . date('Y'), 0777, true);
    }
    if (!file_exists('upload/videos/' . date('Y') . '/' . date('m'))) {
        @mkdir('upload/videos/' . date('Y') . '/' . date('m'), 0777, true);
    }
    if (!file_exists('upload/sounds/' . date('Y'))) {
        @mkdir('upload/sounds/' . date('Y'), 0777, true);
    }
    if (!file_exists('upload/sounds/' . date('Y') . '/' . date('m'))) {
        @mkdir('upload/sounds/' . date('Y') . '/' . date('m'), 0777, true);
    }
    if (isset($data['file']) && !empty($data['file'])) {
        $data['file'] = $data['file'];
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = Sh_Secure($data['name']);
    }
    if (empty($data)) {
        return false;
    }
    if ($sc['config']['fileSharing'] == 1) {

        if (isset($data['types'])) {
            $allowed = $data['types'];
        } else {
            $allowed = $sc['config']['allowedExtenstion'];
        }

    } else {
        $allowed = 'jpg,png,jpeg,gif,mp4,m4v,webm,flv,mov,mpeg,mp3,wav,doc,docx,xls,xlsx,csv,pptx,ppt';
    }

    $new_string        = pathinfo($data['name'], PATHINFO_FILENAME) . '.' . strtolower(pathinfo($data['name'], PATHINFO_EXTENSION));
    $extension_allowed = explode(',', $allowed);
    $file_extension    = pathinfo($new_string, PATHINFO_EXTENSION);
    if (!in_array($file_extension, $extension_allowed)) {
        return false;
    }
    if ($data['size'] > $sc['config']['maxUpload']) {
        return false;
    }
    if ($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png' || $file_extension == 'gif') {
        $folder   = 'photos';
        $fileType = 'image';
    } else if ($file_extension == 'mp4' || $file_extension == 'mov' || $file_extension == 'webm' || $file_extension == 'flv') {
        $folder   = 'videos';
        $fileType = 'video';
    } else if ($file_extension == 'mp3' || $file_extension == 'wav') {
        $folder   = 'sounds';
        $fileType = 'soundFile';
    } else {
        $folder   = 'files';
        $fileType = 'file';
    }
    if (empty($folder) || empty($fileType)) {
        return false;
    }
    $mime_types = explode(',', str_replace(' ', '', $sc['config']['mime_types'] . ',application/json,application/octet-stream'));
    // if (Sh_IsAdmin()) {
    //     $mime_types = explode(',', str_replace(' ', '', $sc['config']['mime_types'] . ',application/json,application/octet-stream,image/svg+xml'));
    // }

    if (!in_array($data['type'], $mime_types)) {
        return false;
    }
    $dir         = "upload/{$folder}/" . date('Y') . '/' . date('m');
    $filename    = $dir . '/' . Sh_GenerateKey() . '_' . date('d') . '_' . md5(time()) . "_{$fileType}.{$file_extension}";
    $second_file = pathinfo($filename, PATHINFO_EXTENSION);
    if (move_uploaded_file($data['file'], $filename)) {
        if ($second_file == 'jpg' || $second_file == 'jpeg' || $second_file == 'png' || $second_file == 'gif') {
            $check_file = getimagesize($filename);
            if (!$check_file) {
                unlink($filename);
            }
            if( $crop == true ){
                if ($type == 1) {
                    if ($second_file != 'gif') {
                        @Sh_CompressImage($filename, $filename, 50);
                    }
                    $explode2  = @end(explode('.', $filename));
                    $explode3  = @explode('.', $filename);
                    $last_file = $explode3[0] . '_small.' . $explode2;

                    if (Sh_Resize_Crop_Image(400, 400, $filename, $last_file, 60)) {

                        if (empty($data['local_upload'])) {
                            if (($sc['config']['amazone_s3'] == 1 || $sc['config']['ftp_upload'] == 1 || $sc['config']['spaces'] == 1 || $sc['config']['cloud_upload'] == 1) && !empty($last_file)) {
                                $upload_s3 = Sh_UploadToS3($last_file);
                            }
                        }

                    }
                } else {
                    if (!isset($data['compress']) && $second_file != 'gif') {
                        @Sh_CompressImage($filename, $filename, 10);

                        if ($sc['config']['watermark'] == 1) {
                          watermark_image($filename);
                        }

                    }
                }
            }
        }
        if (!empty($data['crop'])) {
            $crop_image = Sh_Resize_Crop_Image($data['crop']['width'], $data['crop']['height'], $filename, $filename, 60);
        }
        if (empty($data['local_upload'])) {
            if (($sc['config']['amazone_s3'] == 1 || $sc['config']['ftp_upload'] == 1 || $sc['config']['spaces'] == 1 || $sc['config']['cloud_upload'] == 1) && !empty($filename)) {
                $upload_s3 = Sh_UploadToS3($filename);
            }
        }
        $last_data             = array();
        $last_data['filename'] = $filename;
        $last_data['name']     = $data['name'];
        return $last_data;
    }
}

function watermark_image($target) {
    global $sc;
    include_once('assets/libraries/SimpleImage-master/src/claviska/SimpleImage.php');
    if ($sc['config']['watermark'] != 1) {
        return false;
    }
    try {
      $image = new \claviska\SimpleImage();

      $image
        ->fromFile($target)
        ->autoOrient()
        ->overlay("./themes/{$sc['config']['theme']}/img/icon.png", 'top left', 1, 30, 30)
        ->toFile($target, 'image/jpeg');

      return true;
    } catch(Exception $err) {
      return $err->getMessage();
    }
}

function Sh_CompressImage($source_url, $destination_url, $quality) {
    $imgsize = getimagesize($source_url);
    $finfof  = $imgsize['mime'];
    $image_c = 'imagejpeg';
    if ($finfof == 'image/jpeg') {
        $image = @imagecreatefromjpeg($source_url);
    } else if ($finfof == 'image/gif') {
        $image = @imagecreatefromgif($source_url);
    } else if ($finfof == 'image/png') {
        $image = @imagecreatefrompng($source_url);
    } else {
        $image = @imagecreatefromjpeg($source_url);
    }
    $quality = 50;
    if (function_exists('exif_read_data')) {
        $exif = @exif_read_data($source_url);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $image = @imagerotate($image, 180, 0);
                    break;
                case 6:
                    $image = @imagerotate($image, -90, 0);
                    break;
                case 8:
                    $image = @imagerotate($image, 90, 0);
                    break;
            }
        }
    }
    @imagejpeg($image, $destination_url, $quality);
    return $destination_url;
}


function Sh_Resize_Crop_Image($max_width, $max_height, $source_file, $dst_dir, $quality = 80) {
    $imgsize = @getimagesize($source_file);
    $width   = $imgsize[0];
    $height  = $imgsize[1];
    $mime    = $imgsize['mime'];
    $image   = "imagejpeg";
    switch ($mime) {
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            break;
        case 'image/png':
            $image_create = "imagecreatefrompng";
            break;
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            break;
        default:
            return false;
            break;
    }
    $dst_img = @imagecreatetruecolor($max_width, $max_height);
    $src_img = @$image_create($source_file);
    if (function_exists('exif_read_data')) {
        $exif          = @exif_read_data($source_file);
        $another_image = false;
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $src_img = @imagerotate($src_img, 180, 0);
                    @imagejpeg($src_img, $dst_dir, $quality);
                    $another_image = true;
                    break;
                case 6:
                    $src_img = @imagerotate($src_img, -90, 0);
                    @imagejpeg($src_img, $dst_dir, $quality);
                    $another_image = true;
                    break;
                case 8:
                    $src_img = @imagerotate($src_img, 90, 0);
                    @imagejpeg($src_img, $dst_dir, $quality);
                    $another_image = true;
                    break;
            }
        }
        if ($another_image == true) {
            $imgsize = @getimagesize($dst_dir);
            if ($width > 0 && $height > 0) {
                $width  = $imgsize[0];
                $height = $imgsize[1];
            }
        }
    }
    @$width_new = $height * $max_width / $max_height;
    @$height_new = $width * $max_height / $max_width;
    if ($width_new > $width) {
        $h_point = (($height - $height_new) / 2);
        @imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    } else {
        $w_point = (($width - $width_new) / 2);
        @imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }
    @imagejpeg($dst_img, $dst_dir, $quality);
    if ($dst_img)
        @imagedestroy($dst_img);
    if ($src_img)
        @imagedestroy($src_img);
    return true;
}
