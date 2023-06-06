<?php
if($_SERVER['REQUEST_URI'] != '/videos'){
    header("Location: /videos");
}
if(!isset($_COOKIE['MeTubeUID'])){
    header("Location: /?return=".$_SERVER['REQUEST_URI']);
}
include 'extra/init.php';
$userID = $_COOKIE['MeTubeUID'];
$user = $global->userData(1, $userID);
include 'index.php';
die();
?>