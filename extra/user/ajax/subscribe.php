<?php
include $_SERVER['DOCUMENT_ROOT'].'/extra/init.php';
if(isset($_POST['subscribe']) && !empty($_POST['subscribe'])){
	if(!isset($_COOKIE['MeTubeUID'])){
		echo json_encode('error');
	}else if($_COOKIE['MeTubeUID'] == $_POST['subscribe']){
		echo json_encode('error');
	}else{
		if($global->checkSubscribe($_POST['subscribe'], $_COOKIE['MeTubeUID']) === false){
			if($global->check('users', 'userID', $_POST['subscribe']) === true){
				$userID = $_COOKIE['MeTubeUID'];
				$to = $_POST['subscribe'];
				$adds = mysqli_query($mysql, "INSERT INTO subscriptions(subscribeTo, subscribeBy) VALUES($to, $userID)");
				if(mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM notifications WHERE notificationBy = $userID AND notificationAction = 1 AND notificationTo = $to")) == 0){
    			    $dateNotify = date('Y-m-d');
    			    $addnotify = mysqli_query($mysql, "INSERT INTO notifications(notificationTo, notificationBy, notificationAction, notificationActionURL, notificationAdded, notificationNew) VALUES($to, $userID, 2, '', '$dateNotify', 1)");
    			}
			}
		}
	}
}
if(isset($_POST['unsubscribe']) && !empty($_POST['unsubscribe'])){
	if(!isset($_COOKIE['MeTubeUID'])){
		echo json_encode('error');
	}else if($_COOKIE['MeTubeUID'] == $_POST['unsubscribe']){
		echo json_encode('error');
	}else{
		if($global->checkSubscribe($_POST['unsubscribe'], $_COOKIE['MeTubeUID']) === true){
			if($global->check('users', 'userID', $_POST['unsubscribe']) === true){
				$userID = $_COOKIE['MeTubeUID'];
				$to = $_POST['unsubscribe'];
				$adds = mysqli_query($mysql, "DELETE FROM subscriptions WHERE subscribeTo = $to AND subscribeBy = $userID");
			}
		}
	}
}
?>