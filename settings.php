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
if(isset($_GET['act'])){
    if($_GET['act'] == 'ads'){
        if(isset($_POST['done'])){
            if($_POST['noshow'] == 1){
                setcookie('NoShowAd', 1, time()+7000000);
            }else{
                setcookie('NoShowAd','',time()-7000000,'/');
            }
            $alert = '<script>setTimeout(function(){ location.href = "/settings?act=ads" ; }, 1000)</script>Изменения вступят в силу через 1 секунду...';
        }
    }
    if($_GET['act'] == 'delete'){
        if(isset($_POST['delete'])){
            $vids = mysqli_query($mysql, "SELECT * FROM videos WHERE videoBy = $userID");
            while($video = mysqli_fetch_assoc($vids)){
                if($video['videoBy'] == $userID){
                    $del = mysqli_query($mysql, "DELETE FROM videos WHERE videoID = $id");
                    $delvids = mysqli_query($mysql, "DELETE FROM playlist_videos WHERE pvVideo = $id");
                    $dellikes = mysqli_query($mysql, "DELETE FROM likes WHERE likeVid = $id");
                    $deldislikes = mysqli_query($mysql, "DELETE FROM dislikes WHERE dislikeVid = $id");
                    $delcomments = mysqli_query($mysql, "DELETE FROM comments WHERE commentVideo = $id");
                    $delwatchlater = mysqli_query($mysql, "DELETE FROM watch_later WHERE wlVideo = $id");
                    $delhistory = mysqli_query($mysql, "DELETE FROM history WHERE historyVideoID = $id");
                }
            }
            $dlv = mysqli_query($mysql, "DELETE FROM videos WHERE videoBy = $userID");
            $dlw = mysqli_query($mysql, "DELETE FROM watch_later WHERE wlTo = $userID");
            $dlp = mysqli_query($mysql, "DELETE FROM playlists WHERE playlistBy = $userID");
            $dlc = mysqli_query($mysql, "DELETE FROM comments WHERE commentBy = $userID");
            $dll = mysqli_query($mysql, "DELETE FROM likes WHERE likeFrom = $userID");
            $dld = mysqli_query($mysql, "DELETE FROM dislikes WHERE dislikeFrom = $userID");
            $dlf = mysqli_query($mysql, "DELETE FROM flags WHERE flagBy = $userID");
            $dlfav = mysqli_query($mysql, "DELETE FROM favorites WHERE favoriteBy = $userID");
            $dllc = mysqli_query($mysql, "DELETE FROM likes_comments WHERE likeFrom = $userID");
            $dldc = mysqli_query($mysql, "DELETE FROM dislikes_comments WHERE dislikeFrom = $userID");
            $dldcom = mysqli_query($mysql, "DELETE FROM community WHERE communityBy = $userID");
            $dlchp = mysqli_query($mysql, "DELETE FROM channel_playlist WHERE chTo = $userID");
            $dls = mysqli_query($mysql, "DELETE FROM subscriptions WHERE subscribeBy = $userID");
            $dld = mysqli_query($mysql, "DELETE FROM users WHERE userID = $userID");
            header("Location: /extra/user/logout?deleting=true");
        }
    }
}
if(isset($_POST['chng'])){
    if(!empty($_POST['oldPwd']) && !empty($_POST['newPwd']) && !empty($_POST['reNewPwd'])){
        if(!ctype_space($_POST['oldPwd']) && !ctype_space($_POST['newPwd']) && !ctype_space($_POST['reNewPwd'])){
            if($_POST['newPwd'] == $_POST['reNewPwd']){
                if(strlen($_POST['newPwd']) < 8){
                    $error = 'Новый пароль короче восьми символов!';
                }else{
                    if(password_verify($_POST['oldPwd'], $user['password'])){
                        $password = password_hash($_POST['newPwd'], PASSWORD_DEFAULT);
                        $chng = mysqli_query($mysql, "UPDATE users SET password = '$password' WHERE userID = $userID");
                        header("Location: /?logout=true");
                    }else{
                        $error = 'Неправильный старый пароль!';
                    }
                }
            }else{
                $error = 'Пароли различаются!';
            }
        }else{
            $error = 'Пустые поля!';
        }
    }else{
        $error = 'Пустые поля!';
    }
}
if(isset($_POST['upd'])){
    $email = $_POST['email'];
    if($user['verifed'] == 1){
        $username = $user['username'];
    }else{
        $username = $_POST['username'];
    }
    if(!empty($username) && !empty($email)){
        if(!ctype_space($username) && !ctype_space($email)){
            if(strlen($username) > 72){
                $error = 'Ваш никнейм длинный!';
            }else if(preg_match("/[^a-zA-Z0-9\!]/", $username)){
                $error  = "В никнейме допустимо только латинские символы и числа!";
            }else{
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    if($user['username'] != $username && $global->check('users', 'username', $username) === true){
                        $error = 'Такой никнейм уже используется!';
                    }else{
                        $chng = mysqli_query($mysql, "UPDATE users SET username = '$username', email = '$email' WHERE userID = $userID");
                        header("Location: /settings");
                    }
                }else{
                    $error = 'Неправильная форма почты!';
                }
            }
        }else{
            $error = 'Пустые поля!';
        }
    }else{
        $error = 'Пустые поля!';
    }
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
    <script src="js/main.js"></script>
    <style>
        #homepage{
            padding:10px;
        }
    </style>
</head>
<body>
    <!-- metubee.xyz -->
    <div id="app">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/header.php'; ?>
        <?php
        if(isset($alert)){
        ?>
        <div class="alert alert-success">
            <div>
                <img src="img/p.png">
            </div>
            <div class="alert-message">
                <?php echo $alert; ?>  
            </div>
        </div><br>
        <?php } ?>
        <?php
        if(isset($error)){
        ?>
        <div class="alert alert-error">
            <div>
                <img src="img/p.png">
            </div>
            <div class="alert-message">
                <?php echo $error; ?>  
            </div>
        </div><br>
        <?php } ?>
        <div id="container">
            <div id="guide">
                <div class="guide_container">
                    <div>
                        <h3>настройки аккаунта</h3>
                        <div class="guide-item-non-select global--link" data-link="/settings">
                            <span>
                                Аккаунт
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/settings?act=password">
                            <span>
                                Пароль
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/settings?act=ads">
                            <span>
                                Реклама
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/settings?act=delete">
                            <span>
                                Удаление аккаунта
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <form method="POST" id="homepage">
                <?php
                if(isset($_GET['act']) && $_GET['act'] == 'ads'){
                ?>
                <h2>Настройки рекламы</h2>
                <h4>
                    Реклама будет показываться?
                </h4>
                <div style="display:flex;">
                    <div>
                        <input class="radio_form_input" type="radio" id="" name="noshow" value="0" <?php if(!isset($_COOKIE['NoShowAd'])){ echo 'checked'; } ?>>
                    </div>
                    <span>
                        Да
                    </span>
                </div>
                <div style="display:flex;">
                    <div>
                        <input class="radio_form_input" id="" type="radio" name="noshow" value="1" <?php if(isset($_COOKIE['NoShowAd']) && $_COOKIE['NoShowAd'] == 1){ echo 'checked'; } ?>>
                    </div>
                    <span>
                        Нет
                    </span>
                </div>
                <br>
                <button class="mt-uix-button mt-button-default" type="submit" name="done">Сохранить</button>
            <?php }else if(isset($_GET['act']) && $_GET['act'] == 'delete'){
                ?>
                <h2>Удаление аккаунта</h2>
                <center>
                    <strong>Постойте!</strong>
                    <br> Если вам нужно <a href="/settings">поменять ник</a> - необязательно удалять аккаунт.<br> Если вам нужно <a href="/my_videos">удалить видео</a> - необязательно удалять аккаунт.<br>Если вам нужно <a href="/view_all_playlists">удалить плейлисты</a> - необязательно удалять аккаунт.<br>
                    <strong>Вы уверены что вы хотите удалить аккаунт? Восстановить видео, плейлисты, комментарии и аккаунт у вас не получиться после удаления.</strong>
                </center><br>
                <center>
                    <form method="POST">
                        <button type="submit" name="delete" class="mt-uix-button mt-button-default">Да, я уверен...</button>
                        <button type="button" class="mt-uix-button mt-button-primary global--link" data-link="/">Нет, я передумал</button>
                    </form>
                </center>
                <?php }else if(isset($_GET['act']) && $_GET['act'] == 'password'){
                ?>
                <style type="text/css">
                    input{
                        width:300px;
                    }
                </style>
                <h2>Поменять пароль</h2>
                <div>
                    <div>
                        Старый пароль
                    </div>
                    <div>
                        <input type="password" name="oldPwd" class="input" placeholder="Старый пароль">
                    </div>
                </div><br>
                <div>
                    <div>
                        Новый пароль
                    </div>
                    <div>
                        <input type="password" name="newPwd" class="input" placeholder="Новый пароль">
                    </div>
                </div><br>
                <div>
                    <div>
                        Повторите новый пароль
                    </div>
                    <div>
                        <input type="password" name="reNewPwd" class="input" placeholder="Повторите новый пароль">
                    </div>
                </div><br>
                <button class="mt-uix-button mt-button-default" type="submit" name="chng">Сохранить</button>
                <span style="color:#666">После смены пароля, вы выйдете из аккаунта.</span>
                <?php }else{ ?>
                <style type="text/css">
                    input{
                        width:300px;
                    }
                </style>
                <h2>Настройки аккаунта</h2>
                <div>
                    <?php
                    if($user['verifed'] != 1){
                    ?>
                    <div>
                        Никнейм
                    </div>
                    <div>
                        <input type="text" name="username" class="input" placeholder="Введите Никнейм" value="<?php echo $user['username'] ?>">
                    </div>
                    <?php }else{ ?>
                        <div class="alert alert-error" style="margin:0;margin-left:0!important;">
                            <div>
                                <img src="img/p.png">
                            </div>
                            <div class="alert-message">
                                Вы не можете поменять никнейм так как вы подтверждённый пользователь
                            </div>
                        </div><br>
                    <?php } ?>
                </div><br>
                <div>
                    <div>
                        Email
                    </div>
                    <div>
                        <input type="email" name="email" class="input" placeholder="Введите Email" value="<?php echo $user['email'] ?>">
                    </div>
                </div><br>
                <button class="mt-uix-button mt-button-default" type="submit" name="upd">Сохранить</button>
                <span style="color:#666">Некоторые настройки есть в <a href="/personalize">кастомизации канала</a></span>
                <?php } ?>
            </form>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>