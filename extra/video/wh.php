<?php
include $_SERVER['DOCUMENT_ROOT'].'/extra/init.php';
if(isset($_COOKIE['MeTubeUID'])){
	$userID = $_COOKIE['MeTubeUID'];
}else{
	echo json_encode('error');
}
if(isset($_POST['video']) && !empty($_POST['video'])){
	$video = $_POST['video'];
	if($global->check('videos', 'videoID', $video) === true){
		if($global->watchLaterCheck($userID, $video) == 'add-to-button-video-success'){
			$d = mysqli_query($mysql, "DELETE FROM watch_later WHERE wlTo = $userID AND wlVideo = $video");
		}else{
			$a = mysqli_query($mysql, "INSERT INTO watch_later(wlVideo, wlTo) VALUES($video, $userID)");
		}
	}else{
		echo json_encode('error');
	}
}
?>