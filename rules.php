<?php
include 'extra/init.php';
if(isset($_COOKIE['MeTubeUID'])){
    $userID = $_COOKIE['MeTubeUID'];
    $user = $global->userData(1, $userID);
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
                Правила MeTube
            </h1>
            <h3>
                1. Вы и поведение
            </h3>
            <ul>
                <p>
                    1.1 Вам должно быть не менее 13 лет, чтобы пользоваться этим сайтом.
                </p>
                <p>
                    1.2 Вы несете ответственность за любые действия, которые происходят под вашим псевдонимом.
                </p>
                <p>
                    1.3 Вы не должны оскорблять, преследовать, угрожать, выдавать себя за других пользователей MeTube или запугивать их.
                </p>
                <p>
                    1.4 Вы не должны при использовании MeTube нарушать какие-либо законы в вашей стране (включая, но не ограничиваясь законами об авторском праве).
                </p>
                <p>
                    1.5 За безопастность вашего аккаунта отвечаете <strong>ВЫ</strong>!
                </p>
            </ul>
            <h3>
                2. Контент
            </h3>
            <ul>
                <p>
                    2.1 Вы не должны загружать или делиться ссылками, ведущими к какому-либо конфиденциальному контенту (например, акты жестокости, порнография и т.д.).
                </p>
                <p>
                    2.2 Вы не должны передавать никаких троянов, вирусов или какой-либо код разрушительного характера.
                </p>
                <p>
                    2.3 Не нарушайте авторские права.
                </p>
            </ul>
            <h3>
                3. Видео
            </h3>
            <ul>
                <p>
                    3.1 Запрещено накручивать себе просмотры и лайки.
                </p>
                <p>
                    3.2 Запрещено жаловаться на видео без причины
                </p>
            </ul>
            <h4>За нарушение правил - бан. Каждое правило имеет срок бана.</h4>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>