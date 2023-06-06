<?php
include 'config.php';
$mysql = mysqli_connect($host, $username, $pwd, $db);
if(!empty(mysqli_connect_error())){
	echo '<h1 style="font-family:sans-serif">Не удалось подключиться к базе данных...</h1>';
	die();
}
?>