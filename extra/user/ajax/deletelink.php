<?php
include $_SERVER['DOCUMENT_ROOT'].'/extra/init.php';
if(!isset($_COOKIE['MeTubeUID'])){
	die();
}else{
	$userID = $_COOKIE['MeTubeUID'];
}
if(isset($_POST['linkid']) && !empty($_POST['linkid'])){
	if($global->check('links', 'linkID', $_POST['linkid'])){
		$linkID = $_POST['linkid'];
		$link = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM links WHERE linkID = $linkID"));
		if($link['linkTo'] == $userID){
			$dellink = mysqli_query($mysql, "DELETE FROM links WHERE linkID = $linkID");
		}
	}
}
?>