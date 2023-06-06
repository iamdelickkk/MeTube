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
        h1, h2, h3{
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
                Мы не хотим нарушать закон о Авторских правах
            </h1>
            <h3>
                Пожалуйста, выкладывайте те видео, которые вы сами и сделали.<br>На нашем видеохостинге мы запрещаем выкладывать <strong>телешоу, фильмы, сериалы, музыку, программы</strong> которые находятся под защитой авторских прав.
            </h3>
            <h1>Меня заблокировали, за что?!</h1>
            <h3>
                Наверное, вы выложили видео в котором были авторские права.
            </h3>
            <h1>О запросах удаления контента</h1>
            <h3>
                Если на MeTube нарушаются ваши права - например, кто-то выложил видео которое защищено авторским правом, вам нужно:<br><br>
                <strong>
                    Для частных лиц:<br>
                    Написать на наш электронный адрес - <a href="mailto:copyright@metubee.xyz">copyright@metubee.xyz</a>:<br>
                    1. ФИО полностью<br>
                    2. Фото с вашим лицом и паспортом в руках - для подтверждения, что это вы. Серию, номер и другие сведения закрыть или замазать<br>
                    3. Согласие на обработку персональных данных.<br>
                    4. Информацию о правах на контент - ссылки на оригинальную публикацию или т.д<br>
                    5. Cпособ связаться с вами (соц. сети, email и т.п)<br>
                </strong><br>
                <strong>
                    Для юридических лиц:<br>
                    Написать на наш электронный адрес - <a href="mailto:copyright@metubee.xyz">copyright@metubee.xyz</a>:<br>
                    1. Наименование компании<br>
                    2. Местонахождение и адрес компании<br>
                    3. Номер телефона или факса компании, e-mail<br>
                    4. Информацию о правах на контент - ссылки на оригинальную публикацию или т.д<br>

                </strong><br>
                <strong>
                    Мы можем вам отказать если:
                </strong>
                <div>
                    Письмо составлено не грамотно и/или используются поддельные сайты или используются поддельные документы
                </div>
            </h3>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>