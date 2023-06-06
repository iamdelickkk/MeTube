<?php
include $_SERVER['DOCUMENT_ROOT'].'/extra/init.php';
if(!isset($_COOKIE['MeTubeUID'])){
    die('ACCESS DENIED');
}
ini_set('max_execution_time', '300');
error_reporting(0);
set_time_limit(300);
$userID = $_COOKIE['MeTubeUID'];
$userData = $global->userData(1, $userID);
if(!empty($userData['ban'])){
    die(json_encode('err'));
}
if(!empty($_POST['title']) && !empty($_POST['category'])){
    $title = $global->text($_POST['title']);
    $description = $global->text($_POST['description']);
    $tags = $global->text($_POST['tags']);
    $file = $_POST['file'];
    $category = $_POST['category'];
    $date = date('Y-m-d');
    if($global->check('videos', 'videoFile', $file) === true){
        die(json_encode('err'));
    }
    if(!ctype_space($_POST['title']) or !ctype_space($_POST['description']) or !ctype_space($_POST['tags'])){
        $getID = $global->randText(10);
        $thumbmail = $global->generateThumbnails($_SERVER['DOCUMENT_ROOT'].'/'.$file, 'uploads/'.$getID.'.jpg');
        $mp4 = $global->convertVideoToMp4($_SERVER['DOCUMENT_ROOT'].'/'.$file, 'uploads/'.$getID.'.m4v');
        if($mp4 == 'err' or $flv == 'err'){
            echo json_encode('err');
        }else{
            if($global->check('videos', 'videoGetID', $getID) === true){
                echo json_encode('err');
            }else{
                $duration = $global->getVideoDuration($file);
                $c = mysqli_query($mysql, "INSERT INTO videos(videoGetID, videoTitle, videoBy, videoDuration, videoAdded, videoCategory, videoTags, videoDescription, videoFile) VALUES('$getID', '$title', $userID, $duration, '$date', '$category', '$tags', '$description', '$file')");
                echo json_encode($getID);
            }
        }
    }else{
        echo json_encode('err');
    }
}else{
    echo json_encode('err');
}
?>