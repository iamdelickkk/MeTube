<?php
include $_SERVER['DOCUMENT_ROOT'].'/extra/init.php';
$userID = $_COOKIE['MeTubeUID'];
if(isset($_POST['like']) && !empty($_POST['like'])){
	if($global->check('videos', 'videoID', $_POST['like']) === true){
		$video = $_POST['like'];
		$videoData = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE videoID = $video"));
		$videoBy = $videoData['userID'];
		$videoGetID = $videoData['videoGetID'];
		if($global->checkLike($video, $userID) === false){
			if($global->checkDislike($video, $userID) === true){
				$deldislike = mysqli_query($mysql, "DELETE FROM dislikes WHERE dislikeVid = $video AND dislikeFrom = $userID");
			}
			$like = mysqli_query($mysql, "INSERT INTO likes(likeFrom, likeVid) VALUES($userID, $video)");
			if($videoBy != $userID){
    			if(mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM notifications WHERE notificationBy = $userID AND notificationActionURL = '/watch?v=$video'")) == 0){
    			    $dateNotify = date('Y-m-d');
    			    $addnotify = mysqli_query($mysql, "INSERT INTO notifications(notificationTo, notificationBy, notificationAction, notificationActionURL, notificationAdded, notificationNew) VALUES($videoBy, $userID, 1, '/watch?v=$videoGetID', '$dateNotify', 1)");
    			}
			}
		}else{
			if($global->checkDislike($video, $userID) === true){
				$deldislike = mysqli_query($mysql, "DELETE FROM dislikes WHERE dislikeVid = $video AND dislikeFrom = $userID");
			}
			$dellike = mysqli_query($mysql, "DELETE FROM likes WHERE likeVid = $video AND likeFrom = $userID");
		}
	}
}
if(isset($_POST['dislike']) && !empty($_POST['dislike'])){
	if($global->check('videos', 'videoID', $_POST['dislike']) === true){
		$video = $_POST['dislike'];
		if($global->checkDislike($video, $userID) === false){
			if($global->checkLike($video, $userID) === true){
				$dellike = mysqli_query($mysql, "DELETE FROM likes WHERE likeVid = $video AND likeFrom = $userID");
			}
			$dislike = mysqli_query($mysql, "INSERT INTO dislikes(dislikeFrom, dislikeVid) VALUES($userID, $video)");
		}else{
			if($global->checkLike($video, $userID) === true){
				$dellike = mysqli_query($mysql, "DELETE FROM likes WHERE likeVid = $video AND likeFrom = $userID");
			}
			$deldislike = mysqli_query($mysql, "DELETE FROM dislikes WHERE dislikeVid = $video AND dislikeFrom = $userID");
		}
	}
}
?>