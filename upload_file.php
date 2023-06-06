<?php
include $_SERVER['DOCUMENT_ROOT'].'/extra/init.php';
$userID = $_COOKIE['MeTubeUID'];
$userData = $global->userData(1, $userID);
    $filename   = $_FILES['video_file']['name'];
$fileTmp    = $_FILES['video_file']['tmp_name'];
$fileSize   = $_FILES['video_file']['size'];
$errors     = $_FILES['video_file']['error'];
$ext = explode('.', $filename);
$ext = strtolower(end($ext));

$allowed_extensions  = array("mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg", "flv", "3gp");

if(in_array($ext, $allowed_extensions)){
      
      if($errors ===0){
          
          if($fileSize <= 3793747637236){
              $file_basename = substr($filename, 0, strripos($filename, '.'));
              $file_ext = substr($filename, strripos($filename, '.')); 
              $newfilename = md5($file_basename) . $file_ext;

           $root = 'uploads/' .$userData['username'].'_'.$userID.'_file_'.$newfilename;
                 move_uploaded_file($fileTmp,$_SERVER['DOCUMENT_ROOT'].'/'.$root);
               echo $root;

          }else{
              echo json_encode('err');
          }
      }
    }else{
     echo json_encode('err');
       }
?>