<?php
include $_SERVER['DOCUMENT_ROOT'].'/extra/init.php';
if(!isset($_COOKIE['MeTubeUID'])){
	die();
}
if(isset($_POST['comuid']) && !empty($_POST['comuid'])){
	$id = $_POST['comuid'];
	if($global->check('community', 'communityID', $id) === true){
		$comu = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM community WHERE communityID = $id"));
		if($comu['communityBy'] == $_COOKIE['MeTubeUID'] or $comu['communityTo'] == $_COOKIE['MeTubeUID']){
			$comudl = mysqli_query($mysql, "DELETE FROM community WHERE communityID = $id");
		}
	}
}
?>