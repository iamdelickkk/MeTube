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
    <script>
        setInterval(function(){
            if($('#file_name').html() == '"err"'){
                $("#alerts").html(`<div class="alert alert-error">
                        <div>
                            <img src="img/p.png">
                        </div>
                        <div class="alert-message">
                            Произошла ошибка при добавлении видео. Попробуйте позже. (Error Code - #f3293U)
                        </div>
                    </div><br>`);
                $('#container').remove();
            }
            if($('.progress_bar_percent').html() == '100%'){
                $('.button_upload').removeClass('hidden');
            }
        }, 500);
    </script>
</head>
<body>
    <!-- metubee.xyz -->
    <div id="app">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/header.php'; ?>
        <div id="alerts">
            
        </div>
        <div id="container">
            <div id="file_name"></div>
            <div id="upload">
                <div class="my_videos_upload_top">
                    Добавить видео файл
                </div>
                <div class="upload_base">
                    <form action="/upload_file.php" id="uploadForm" name="frmupload" method="post" enctype="multipart/form-data">
                        <div id="step_1">
                            <div>
                                <div id="upload_button" onclick="$('#file_vid').click()">
                                    <div>
                                        <img src="img/upload.png"><br><br>
                                        <h2>
                                            Выберите видео файл для загрузки
                                        </h2>
                                    </div>
                                </div>
                                <input type="file" accept="video/*" class="hidden" onchange="$('.sumbitttt').click()" name="video_file" id="file_vid">
                                <input name="sumbit" id="submit1" onclick="uploadVid()" class="hidden sumbitttt" type="submit" >
                            </div>
                            <br>
                            <center id="gt">
                                <b>Важно!</b> Вы выкладываете тот контент, которым вы владеете. <a href="/copyright">Узнать больше</a>
                            </center>
                        </div>
                        <div id="step_2" class="hidden">
                            <div style="display: flex;align-items: center;padding:10px;">
                                <div class="upload_thump">
                                    <img id="loading" src="img/loading.gif">
                                </div>
                                <h3 id="vid_name">
                                    
                                </h3>
                                <div class="progress_bar">
                                    <div class="progress_bar_filled">
                                        
                                    </div>
                                    <div class="progress_bar_percent">
                                        
                                    </div>
                                </div>
                            </div><br>
                            <div class="upload_form">
                                <div class="upload_form_tabs">
                                    <div class="upload_form_tab">
                                        Основное
                                    </div>
                                </div>
                                <div style="display:flex;">
                                    <div class="upload_form_data">
                                        <span>
                                            Название
                                        </span><br>
                                        <input type="text" id="titlev" class="input" name="title">
                                        <br>
                                        <span>
                                            Описание
                                        </span><br>
                                        <textarea class="input" id="desc" name="description"></textarea>
                                        <br>
                                        <span>
                                            Теги
                                        </span><br>
                                        <input type="text" id="tags" class="input" name="tags">
                                    </div>
                                    <div class="upload_form_data" style="margin-left:auto;width:100%;">
                                        <span>
                                            Категория
                                        </span><br>
                                        <select name="category" id="category" class="mt-uix-button mt-button-default" style="width:100%;margin-bottom:10px;">
                                            <option value="film_and_animation" selected>Фильмы и анимация</option>
                                            <option value="gaming">Компьютерные игры</option>
                                            <option value="comedy">Комедия</option>
                                            <option value="sports">Спорт</option>
                                            <option value="entertainment">Развлечения</option>
                                            <option value="music">Музыка</option>

                                        </select>
                                        <br>
                                        
                                        <div style="display:flex;">
                                            <button class="mt-uix-button mt-button-default hidden button_upload" type="button">Опубликовать</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>