<?php
include $_SERVER['DOCUMENT_ROOT'].'/extra/init.php';
$userID = $_COOKIE['MeTubeUID'];
if(isset($_POST['playlist'])){
	$playlist = $_POST['playlist'];
	$del = mysqli_query($mysql, "DELETE FROM channel_playlist WHERE chPlaylist = '$playlist' AND chTo = $userID");
}
?>