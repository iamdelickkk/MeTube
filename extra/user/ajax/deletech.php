<?php
include $_SERVER['DOCUMENT_ROOT'].'/extra/init.php';
if(!isset($_COOKIE['MeTubeUID'])){
	die();
}else{
	$userID = $_COOKIE['MeTubeUID'];
}
if(isset($_POST['id']) && !empty($_POST['id'])){
	$favID = $_POST['id'];
	$fav = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM fav_channels WHERE favID = $favID"));
	if($fav['favTo'] == $userID){
		$delfch = mysqli_query($mysql, "DELETE FROM fav_channels WHERE favID = $favID");
	}
}
?>