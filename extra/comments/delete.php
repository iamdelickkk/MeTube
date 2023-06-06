<?php
include $_SERVER['DOCUMENT_ROOT'].'/extra/init.php';
if(isset($_POST['delete']) && !empty($_POST['delete'])){
	$comment = $_POST['delete'];
	$commentInfo = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM comments WHERE commentID = $comment"));
	$videoID = $commentInfo['videoID'];
	$video = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM videos WHERE videoID = $videoID"));
	if($_COOKIE['MeTubeUID'] == $commentInfo['commentBy'] or $video['videoBy'] == $userID){
		if($global->check('comments', 'commentID', $comment)){
			$delete = mysqli_query($mysql, "DELETE FROM comments WHERE commentID = $comment");
		}
	}
}
?>