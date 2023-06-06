<?php
include $_SERVER['DOCUMENT_ROOT'].'/extra/init.php';
$userID = $_COOKIE['MeTubeUID'];
if(isset($_POST['like']) && !empty($_POST['like'])){
	if($global->check('comments', 'commentID', $_POST['like']) === true){
		$comment = $_POST['like'];
		if($global->checkCommentLike($comment, $userID) === false){
			if($global->checkCommentDislike($comment, $userID) === true){
				$dellike = mysqli_query($mysql, "DELETE FROM dislikes_comments WHERE dislikeTo = $comment AND dislikeFrom = $userID");
			}
			$like = mysqli_query($mysql, "INSERT INTO likes_comments(likeFrom, likeTo) VALUES($userID, $comment)");
		}else{
			if($global->checkCommentDislike($comment, $userID) === true){
				$dellike = mysqli_query($mysql, "DELETE FROM dislikes_comments WHERE dislikeTo = $comment AND dislikeFrom = $userID");
			}
			$dellike = mysqli_query($mysql, "DELETE FROM likes_comments WHERE likeTo = $comment AND likeFrom = $userID");
		}
	}
}
if(isset($_POST['dislike']) && !empty($_POST['dislike'])){
	if($global->check('comments', 'commentID', $_POST['dislike']) === true){
		$comment = $_POST['dislike'];
		if($global->checkCommentDislike($comment, $userID) === false){
			if($global->checkCommentLike($comment, $userID) === true){
				$dellike = mysqli_query($mysql, "DELETE FROM likes_comments WHERE likeTo = $comment AND likeFrom = $userID");
			}
			$dislike = mysqli_query($mysql, "INSERT INTO dislikes_comments(dislikeFrom, dislikeTo) VALUES($userID, $comment)");
		}else{
			if($global->checkCommentLike($comment, $userID) === true){
				$dellike = mysqli_query($mysql, "DELETE FROM likes_comments WHERE likeTo = $comment AND likeFrom = $userID");
			}
			$deldislike = mysqli_query($mysql, "DELETE FROM dislikes_comments WHERE dislikeTo = $comment AND dislikeFrom = $userID");
		}
	}
}
?>