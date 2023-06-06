<?php
include 'extra/init.php';
if(!isset($_COOKIE['MeTubeUID'])){
    header("Location: /login?return=".$_SERVER['REQUEST_URI']);
}
$userID = $_COOKIE['MeTubeUID'];
$user = $global->userData(1, $userID);
if(!empty($user['ban'])){
    include $_SERVER['DOCUMENT_ROOT'].'/banned.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="styles/2013-metube.css" rel="stylesheet">
    <title>MeTube - Be Yourself.</title>
    <link rel="icon" type="image/png" href="img/favicon_32-vflWoMFGx.png">
    <script src="js/jquery.js"></script>
    <script src="js/jquery.form.js"></script> 
    <script src="js/main.js"></script>
    <style>
        h1{
            font-weight: normal;
        }
        #container{
            display: block;
        }
    </style>
</head>
<body>
    <!-- metubee.xyz -->
    <div id="app">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/header.php'; ?>
        <div id="alerts">
            
        </div>
        <div id="container">
            <h1>
                Пожертвовать
            </h1>
            <span>
                Разработать и опубликовать видеохостинг дело не простое...<br>
                Сервера и домен мне бесплатно не дают. Мне 14 лет и у меня денег нет, а просить у родителей, мне не очень хочется.<br>
                Надеюсь на вашу поддержку.<br>
            </span><br>
            <button class="mt-uix-button mt-button-primary global--link">
                Пожертвовать через DonationAlerts
            </button>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>