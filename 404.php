<?php
include $_SERVER['DOCUMENT_ROOT'].'/extra/init.php';
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="<?php echo $link ?>/404page/index.css" rel="stylesheet">
    <title>404</title>
    <style>
        .masthead{
            display:none!important;
        }
    </style>
</head>
<body class="noise_background" style="display:flex;align-items:center;justify-content:center;height:100vh">
    <center style="">
        <img src="<?php echo $link ?>img/404.png" style="margin-bottom: -25px;">
        <div style="width: 500px;text-shadow: 0 0 0 transparent,0 1px 1px #fff;font-size:16px;">
            We're sorry, the page you requested cannot be found. Try searching for something else. 
        </div>
        <form style="margin: 15px auto;width: 500px;display:flex;align-items:center;" action="<?php echo $link ?>results">
            <a href="<?php echo $link ?>" style="margin-right: 10px;">
                <img src="<?php echo $link ?>img/logo.png" class="logo">
            </a>
            <input type="search" class="input input_search_head" name="query">
            <button class="button button_search">
                <img src="<?php echo $link ?>img/search.png" width="14" height="14">
            </button>
        </form>
    </center>
</body>
</html>