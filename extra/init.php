<?php
include 'db/connection.php';
include 'classes/global.php';
$global = new GlobalClass($mysql);
$link = 'http://localhost/'; //URL сайта, обязательно с http:// или https:// и с / в конце!
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(0);
setlocale(LC_TIME, "ru_RU.CP1251");
setlocale(LC_TIME, "rus");
if(isset($_COOKIE['MeTubeUID']) && $_COOKIE['MeTubeUID'] != 0){
  $uid = $_COOKIE['MeTubeUID'];
  if(mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM users WHERE userID = $uid")) == 0){
    header("Location: /extra/user/logout");
  }
}
$ad = rand(1, 7);
$adlink = array(
  1 => 'https://t.me/openvkfun',
  2 => 'https://vepurovk.xyz/cho',
  3 => 'https://discord.gg/APwScbUTkR',
  4 => 'https://muramiha.github.io/mtclassic/',
  5 => 'https://vepurovk.xyz/mems',
  6 => 'http://samirkon12.neonarod.com',
  7 => 'https://vepurovk.xyz/'
);
if(isset($_COOKIE['MeTubeUID']) && $_COOKIE['MeTubeUID'] != 0){
    $userTemp = $global->userData(1, $_COOKIE['MeTubeUID']);
    if(!isset($_COOKIE['MeTubeUPassword'])){
        header("Location: /extra/user/logout");
    }
    if($_COOKIE['MeTubeUPassword'] != $userTemp['password']){
        header("Location: /extra/user/logout");
    }
}
?>