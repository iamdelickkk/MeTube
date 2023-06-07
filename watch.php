<?php
if(!isset($_COOKIE['MeTubeUID'])){
    $_COOKIE['MeTubeUID'] = 0;
}
if(isset($_GET['v']) && !empty($_GET['v'])){
    include 'extra/init.php';
    if($global->check('videos', 'videoGetID', $_GET['v']) === false){
        header("Location: /watch");
    }else{
        $videoGetID = $_GET['v'];
        $video = $global->videoData($videoGetID);
        $videoID = $video['videoID'];
        if(isset($_COOKIE['MeTubeUID']) && $_COOKIE['MeTubeUID'] != 0){
            $userID = $_COOKIE['MeTubeUID'];
            $user = $global->userData(1, $userID);
            if(!empty($user['ban'])){
                include $_SERVER['DOCUMENT_ROOT'].'/banned.php';
            }
            if(!empty($video['ban'])){
                header("Location: /user/".$video['username']);
            }
            if($global->checkLike($videoID, $userID) === true && $global->checkDislike($videoID, $userID) === true){
                $dellike = mysqli_query($mysql, "DELETE FROM likes WHERE likeVid = $videoID AND likeFrom = $userID");
                $deldislike = mysqli_query($mysql, "DELETE FROM dislikes WHERE dislikeVid = $videoID AND dislikeFrom = $userID");
                header("Location: /watch?v=".$video['videoGetID']);
            }
            if(mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM history WHERE historyTo = $userID AND historyVideoID = $videoID")) != 0){
                $dth = mysqli_query($mysql, "DELETE FROM history WHERE historyTo = $userID AND historyVideoID = $videoID");
            }
            $ath = mysqli_query($mysql, "INSERT INTO history(historyTo, historyVideoID) VALUES($userID, $videoID)");
        }
        if(isset($_POST['add_comment']) && $_COOKIE['MeTubeUID'] != 0){
            $comment = nl2br($global->text($_POST['comment']));
            if(!empty($comment)){
                if(!ctype_space($comment)){
                    if(strlen($comment) >= 300){
                
                    }else{
                        $date = date('Y-m-d');
                        $createcomment = mysqli_query($mysql, "INSERT INTO comments(commentBy, commentVideo, commentContent, commentAdded) VALUES($userID, $videoID, '$comment', '$date')");
                        header("Location:".$_SERVER['REQUEST_URI']);
                    }
                }
            }
        }
    }
}else{
    include '404.php';
    die();
}
if(isset($_POST['add'])){
    if($_POST['addtoo'] == 'watch_later'){
        if($global->watchLaterCheck($userID, $videoID) != 'add-to-button-video-success'){
            $awl = mysqli_query($mysql, "INSERT INTO watch_later(wlTo, wlVideo) VALUES($userID, $videoID)");
            $msg = 'Успешно было добавлено видео в плейлист <a href="/watch_later">"Посмотреть позже"</a>';
        }else{
            $error = 'Такое видео и так есть в плейлисте!';
        }
    }else if($_POST['addtoo'] == 'favorites'){
        if(mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM favorites WHERE favoriteTo = $userID AND favoriteVideoID = $videoID")) == 0){
            $awl = mysqli_query($mysql, "INSERT INTO favorites(favoriteTo, favoriteVideoID) VALUES($userID, $videoID)");
            $msg = 'Успешно было добавлено видео в плейлист <a href="/my_favorites">"Избранное"</a>';
        }else{
            $error = 'Такое видео и так есть в плейлисте!';
        }
    }else{
        $pla = $_POST['addtoo'];
        if($global->check('playlists', 'playlistGetID', $pla) === true){
            $playlist = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM playlists WHERE playlistGetID = '$pla'"));
            $playlistID = $playlist['playlistID'];
            $ch = mysqli_query($mysql, "SELECT * FROM playlist_videos WHERE pvVideo = $videoID AND pvTo = $playlistID");
            if(mysqli_num_rows($ch) == 0){
                if($playlist['playlistBy'] == $userID){
                    $a = mysqli_query($mysql, "INSERT INTO playlist_videos(pvTo, pvVideo) VALUES($playlistID, $videoID)");
                    $up = mysqli_query($mysql, "UPDATE playlists SET playlistImage = '$videoGetID' WHERE playlistID = $playlistID");
                     $msg = 'Успешно было добавлено видео в плейлист <a href="/playlist?list='.$playlist['playlistGetID'].'">"'.$playlist['playlistTitle'].'"</a>';
                }else{
                    $error = 'undefined';
                }
            }else{
                $error = 'Такое видео и так есть в плейлисте!';
            }
        }else{
            $error = 'undefined';
        }
    }
}
if(isset($_POST['flag'])){
    $cf = mysqli_query($mysql, "INSERT INTO flags(flagTo, flagFrom) VALUES($videoID, $userID)");
    $msg = 'Вы успешно пожаловались на видео';
}
if(isset($_GET['flag']) && $_GET['flag'] == 'true'){
    $cf = mysqli_query($mysql, "INSERT INTO flags(flagTo, flagFrom) VALUES($videoID, $userID)");
    $msg = 'Вы успешно пожаловались на видео';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="styles/2013-metube.css" rel="stylesheet">
    <link href="player/css/style.css" rel="stylesheet">
    <title><?php echo $video['videoTitle'] ?> - MeTube</title>
    <link rel="icon" type="image/png" href="img/favicon_32-vflWoMFGx.png">
    <script src="js/jquery.js"></script>
    <script src="js/main.js"></script>
    <script>
        setInterval(function(){
            if ($('.alert').length > 0) {
                $('.alert').fadeOut(300);
                setTimeout(() => $('.alert').remove(), 350);
            }
        }, 5000)
        setTimeout(function(){ addView(<?php echo $videoID ?>) }, 30000)
    </script>
    <script>
        <?php
        if($_COOKIE['MeTubeUID'] == 0){
        ?>
        $(document).on('click', '.add-to-button-video', function(){
            $('body').html('');
            location.href = '/login';
        })
        $(document).on('click', '.like-button', function(){
            $('body').html('');
            location.href = '/login';
        })
        $(document).on('click', '.dislike-button', function(){
            $('body').html('');
            location.href = '/login';
        })
        $(document).on('click', '.like-comment', function(){
            $('body').html('');
            location.href = '/login';
        })
        $(document).on('click', '.dislike-comment', function(){
            $('body').html('');
            location.href = '/login';
        })
        <?php
        }
        ?>
    </script>
</head>
<body>
    <!-- metubee.xyz -->
    <div id="app">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/header.php'; ?>
        <div id="alerts">
            <?php
            if(isset($error)){
                echo '<div class="alert alert-error">
                        <div>
                            <img src="img/p.png">
                        </div>
                        <div class="alert-message">
                           '.$error.'
                        </div>
                    </div><br>';
            }
            ?>
            <?php
            if(isset($msg)){
                echo '<div class="alert alert-success">
                        <div>
                            <img src="img/p.png">
                        </div>
                        <div class="alert-message">
                           '.$msg.'
                        </div>
                    </div><br>';
            }
            ?>
        </div>
        <style>
            #container{
                margin-top:15px;
            }
        </style>
        <div id="container">
            <div id="guide">
                <div class="guide-toggle">
                    <img src="img/p.png">
                    <span>
                        гид
                    </span>
                </div>
                <div class="guide_container hidden">
                    <div>
                        <div class="guide-item-non-select global--link" data-link="/videos">
                            <img src="img/popular.jpg">
                            <span>
                                Популярные на MeTube
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/user/music">
                            <img src="img/music.jpg">
                            <span>
                                Музыка
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/user/sports">
                            <img src="img/sports.jpg">
                            <span>
                                Спорт
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/user/gaming">
                            <img src="img/gaming.jpg">
                            <span>
                                Компьютерные игры
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/user/movies">
                            <img src="img/movies.png">
                            <span>
                                Фильмы
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/user/tvshows">
                            <img src="img/tvshows.png">
                            <span>
                                Телепередачи
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/user/news">
                            <img src="img/news.jpg">
                            <span>
                                Новости
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/user/metube">
                            <img src="img/spotlight.jpg">
                            <span>
                                В центре внимания
                            </span>
                        </div>
                        <div class="guide-section-separator"></div>
                        <?php
                        if($_COOKIE['MeTubeUID'] == 0){
                            $chs = mysqli_query($mysql, "SELECT * FROM users WHERE ban = '' ORDER BY rand() LIMIT 5");
                            echo '<h3>Каналы для вас</h3>';
                            while($user = mysqli_fetch_assoc($chs)){
                        ?>
                        <div class="guide-item-non-select global--link" data-link="/user/<?php echo $user['username'] ?>">
                            <img src="<?php echo $user['profileImage'] ?>">
                            <span>
                                <?php echo $user['username'] ?>
                            </span>
                        </div>
                        <?php
                            }
                        }else{
                            $subs = mysqli_query($mysql, "SELECT * FROM subscriptions LEFT JOIN users ON userID = subscribeTo WHERE subscribeBy = $userID AND ban = '' ORDER BY subscribeID DESC");
                            echo '<h3>подписки</h3>';
                            while($sub = mysqli_fetch_assoc($subs)){
                        ?>
                        <div class="guide-item-non-select global--link" data-link="/user/<?php echo $sub['username'] ?>">
                            <img src="<?php echo $sub['profileImage'] ?>">
                            <span>
                                <?php echo $sub['username'] ?>
                            </span>
                        </div>
                        <?php
                            }
                        }
                        ?>
                        <div class="guide-section-separator"></div>
                        <div class="guide-item-non-select global--link" data-link="/browse_channels">
                            <img src="img/browse_channels.png">
                            <span>
                                Просмотреть каналы
                            </span>
                        </div>
                        <?php
                        if(!isset($_COOKIE['MeTubeUID'])){
                        ?>
                        <div class="guide-promo">
                            <p>
                                Войдите, чтобы добавить каналы и просмотреть интересные рекомендации.
                            </p>
                            <button style="margin-left: auto;" class="mt-uix-button mt-button-primary global--link" data-link="/ServiceLogin">Войти ›</button>
                        </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
            <div id="watchvideo">
                <div id="player">
                        
                    <style>
                       
                    .vlPlayer2012:focus {
                      outline: none;
                    }
                    </style>
                    
                    <!-- Left side of the watch page where the video player is !-->
                        <!-- Video Player !-->
                        <!--<div style="width:480px;height:388px" class="videocontainer" id="video_height" oncontextmenu="return false;">-->
                                <script type="text/javascript">function v_play(){player.ended||player.paused?(player.play(),document.getElementById("left").style.backgroundImage="url('/img/ply0.png')"):(player.pause(),document.getElementById("left").style.backgroundImage="url('/img/ply1.png')")}function v_mute(){player.muted?(document.getElementById("right").style.backgroundImage="url('/img/vol1.png')",player.muted=!1):(document.getElementById("right").style.backgroundImage="url('/img/vol0.png')",player.muted=!0)}</script>
                                            <script src="player/main15.js"></script>
                                    
                                    <div id="player">
                    <div id="vtbl_pl">
                    <script id="heightAdjust">
                        if (!window.videoInfo)
                            var videoInfo = {};
                    
                        function adjustHeight(n) {
                            var height;
                            var par = $("#heightAdjust").parent();
                            if (par[0].style.height) {
                                height = par.height();
                                par.height(height+n);
                            }
                        }
                        
                        // Easier way of setting cookies
                        function setCookie(name, value) {
                            var CookieDate = new Date;
                            CookieDate.setFullYear(CookieDate.getFullYear() + 10);
                            document.cookie = name+'='+value+'; expires=' + CookieDate.toGMTString( ) + '; path=/';
                        }
                    
                        // Easier way of getting cookies
                        function getCookie(cname) {
                            var name = cname + "=";
                            var decodedCookie = decodeURIComponent(document.cookie);
                            var ca = decodedCookie.split(';');
                            for(var i = 0; i <ca.length; i++) {
                                var c = ca[i];
                                while (c.charAt(0) == ' ') {
                                    c = c.substring(1);
                                }
                                if (c.indexOf(name) == 0) {
                                    return c.substring(name.length, c.length);
                                }
                            }
                            return "";
                        }
                        
                        function getTimeHash() {
                            var h = 0;
                            var st = 0;
                            
                            if ((h = window.location.href.indexOf("#t=")) >= 0) {
                                st = window.location.href.substr(h+3);
                                return parseInt(st);
                            }
                            
                            return 0;
                        }
                        
                        
                        
                                var viValues = {
                            variable: "vlp",
                            src: "<?php echo 'uploads/'.$video['videoGetID'].'.m4v'; ?>",
                            img: "<?php echo 'uploads/'.$video['videoGetID'].'.jpg'; ?>",
                            autoplay: true,
                            
                            
                            adjust: true,
                            start: getTimeHash()
                        };
                        
                        for (var i in viValues) {
                            if (videoInfo[i] === void(0)) {
                                videoInfo[i] = viValues[i];
                            }
                        }
                        </script>
                    
                    <div class="vlPlayer">
                    <script>
                                    window[videoInfo.variable] = new VLPlayer({
                                        id: videoInfo.id,
                                        src: videoInfo.src,
                                        hdsrc: null,
                                        preview: videoInfo.img,
                                        
                                        duration: videoInfo.duration,
                                        autoplay: videoInfo.autoplay,
                                        skin: "player/skins/",
                                        adjust: videoInfo.adjust,
                                        start: videoInfo.start,
                                        expand: videoInfo.expand,
                                        complete: videoInfo.complete,
                                        ended: videoInfo.ended
                                    });
                                    
                                    $(window).on('hashchange', function() {
                                        var t = getTimeHash();
                                        vlp.play();
                                        vlp.seek(t);
                                        $(window).scrollTop(0);
                                    });
                                </script>
                    </div>
                    </div></div>
                </div>
                <div class="watch-page-container">
                    <div id="video-info">
                        <div class="watch-page-title">
                            <h1><span><?php echo $video['videoTitle']; ?></span></h1>
                        </div>
                        <div class="watch-page-channel-author">
                            <div>
                                <a href="/user/<?php echo $video['username']; ?>"><img src="<?php echo $video['profileImage']; ?>"></a>
                            </div>
                            <div class="watch-page-username">
                                <div style="height: 22px;">
                                    <a href="/user/<?php echo $video['username']; ?>">
                                        <?php echo $video['username']; ?>
                                        <?php
                                    if($video['verifed'] == 1){ echo '<img src="img/p.png" class="verifed-icon">'; }
                                    ?>
                                    </a>
                                    <span class="mt-user-separator">·</span>
                                    <span class="mt-user-videos-counter">
                                        <?php
                                        echo $global->count('int', 'videos', 'videoBy', $video['videoBy']);
                                    ?> видео
                                    </span>
                                </div>
                                <?php
                                if($_COOKIE['MeTubeUID'] == $video['videoBy']){
                                ?>
                                <div style="display: flex;">
                                    <button class="mt-uix-button mt-uix-button-subscribe global--edit-channel" data-user="<?php echo $video['videoBy'] ?>">
                                        <span class="mt-subscribe-icon">
                                            <img src="img/p.png">
                                        </span>
                                        <span id="subscribe_text">
                                            
                                        </span>
                                    </button>
                                </div>
                            <?php }else{ ?>
                                <div style="display: flex;">
                                    <button class="mt-uix-button mt-uix-button-subscribe <?php if($global->checkSubscribe($video['videoBy'], $_COOKIE['MeTubeUID']) === false){ echo 'global--subscribe'; }else{ echo 'global--subscribed'; } ?>" data-user="<?php echo $video['videoBy'] ?>">
                                        <span class="mt-subscribe-icon">
                                            <img src="img/p.png">
                                        </span>
                                        <span id="subscribe_text">
                                            
                                        </span>
                                    </button>
                                    <span class="watch-page-subs-counter">
                                        <?php
                                        echo $global->count('int', 'subscriptions', 'subscribeTo', $video['userID']);
                                        ?>
                                    </span>
                                </div>
                            <?php } ?>
                            </div>
                            <?php
                            if($global->count('int', 'likes', 'likeVid', $video['videoID']) == 0 && $global->count('int', 'dislikes', 'dislikeVid', $video['videoID']) == 0) {
                                $likeswidth = 0;
                                $dislikeswidth = 0;
                            }else{
                                $likeswidth = $global->count('int', 'likes', 'likeVid', $video['videoID']) / ($global->count('int', 'likes', 'likeVid', $video['videoID']) + $global->count('int', 'dislikes', 'dislikeVid', $video['videoID'])) * 100;
                                $dislikeswidth = 100 - $likeswidth;
                            }
                            ?>
                            <div style="margin-left:auto;text-align: right;">
                                <span class="watch-page-views-count"><?php echo $video['videoViews'] ?></span>
                                <div class="video-extras-sparkbars">
                                    <div class="video-extras-sparkbar-likes" style="width: <?php echo $likeswidth ?>%"></div>
                                    <div class="video-extras-sparkbar-dislikes" style="width: <?php echo $dislikeswidth ?>%"></div>
                                </div>
                                <div class="video-extras-counter">
                                    <img src="img/p.png" class="icon-watch-stats-like">
                                    <?php
                                        echo $global->count('int', 'likes', 'likeVid', $video['videoID']);
                                        ?>
                                    &nbsp;&nbsp;&nbsp;
                                    <img src="img/p.png" class="icon-watch-stats-dislike">
                                    <?php
                                    echo $global->count('int', 'dislikes', 'dislikeVid', $video['videoID']);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div id="watch-action-button">
                            <button class="mt-uix-button mt-button-default like-button <?php if($global->checkLike($video['videoID'], $_COOKIE['MeTubeUID']) === true){ echo 'liked-button'; } ?>" data-vid="<?php echo $video['videoID'] ?>">
                                <img src="img/p.png">
                                Нравится
                            </button>
                            <button class="mt-uix-button mt-button-default dislike-button <?php if($global->checkDislike($video['videoID'], $_COOKIE['MeTubeUID']) === true){ echo 'disliked-button'; } ?>" data-vid="<?php echo $video['videoID'] ?>">
                                <img src="img/p.png">
                            </button>
                            <div class="mt-display-flex mt-align-center mt-margin-left">
                                <div class="mt-panel-trigger-selected mt-panel-trigger" data-panel="description">
                                    О видео
                                </div>
                                <div class="mt-panel-trigger" data-panel="share">
                                    Поделиться
                                </div>
                                <div class="mt-panel-trigger" data-panel="addto">
                                    Добавить в
                                </div>
                                <div class="mt-panel-trigger" data-panel="flag">
                                    <img src="img/p.png" class="report-icon">
                                </div>
                            </div>
                        </div>
                        <div id="panel-description" class="panel">
                            <div id="mt-description">
                                <div id="info" class="text-description">
                                    <b>
                                        Добавлено <?php echo $global->formatDate(date('F j, Y',strtotime($video['videoAdded']))); ?>
                                    </b><br>
                                    <?php echo nl2br($video['videoDescription']); ?>
                                </div>
                                <?php
                                $category = array(
                                    'film_and_animation' => 'Фильмы и анимация',
                                    'gaming' => 'Компьютерные игры',
                                    'comedy' => 'Комедия',
                                    'sports' => 'Спорт',
                                    'entertainment' => 'Развлечения',
                                    'music' => 'Музыка'
                                );
                                ?>
                                <div id="another_info" class="hidden">
                                    <div class="mt-display-flex">
                                        <h4 class="title-etc">
                                            Категория
                                        </h4>
                                        <a href="/user/<?php echo $video['videoCategory']; ?>" style="font-size:11px;">
                                            <?php echo $category[$video['videoCategory']]; ?>
                                        </a>
                                    </div>
                                    <?php
                                    if(!empty($video['videoTags'])){
                                    ?>
                                    <div class="mt-display-flex">
                                        <h4 class="title-etc">
                                            Теги
                                        </h4>
                                        <a href="/results?query=<?php echo $video['videoTags']; ?>" style="font-size:11px;">
                                            <?php echo $video['videoTags']; ?>
                                        </a>
                                    </div>
                                <?php } ?>
                                </div>
                            </div>
                            <div style="position: relative;" class="mt-display-flex mt-align-center mt-justify-center">
                                <button class="mt-uix-button mt-button-default show_more" style="z-index:1;height: 18px;background:#fff!important;">
                                    Развернуть
                                </button>
                                <div class="background-divider"></div>
                            </div>
                        </div>
                        <div id="panel-share" class="panel hidden">
                            <input type="text" class="input" value="<?php echo $link ?>watch?v=<?php echo $video['videoGetID']; ?>" readonly><br><br>
                            <div>
                                <a href="https://plus.moogle.sbs/?act=share&share=<?php echo $link ?>watch?v=<?php echo $video['videoGetID']; ?>">
                                    <img src="img/socials/moogleplus.png">
                                </a>

                                <a href="https://z.moogle.sbs/share?value=<?php echo $link ?>watch?v=<?php echo $video['videoGetID']; ?>">
                                    <img src="img/socials/zohan.png">
                                </a>
                            </div>
                        </div>

                        <div id="panel-addto" class="panel hidden" style="padding:10px;">
                            <?php
                            if($_COOKIE['MeTubeUID'] != 0){
                            ?>
                            <h2 id="vm_h">Добавить в плейлист</h2>
                            <form style="padding:10px;" method="POST">
                                <select name="addtoo" class="mt-uix-button mt-button-default" style="width:40%">
                                    <?php
                                    $pq = mysqli_query($mysql, "SELECT * FROM playlists WHERE playlistBy = $userID ORDER BY playlistID DESC");
                                    while($playlist = mysqli_fetch_assoc($pq)){
                                        echo '<option value="'.$playlist['playlistGetID'].'">'.$playlist['playlistTitle'].'</option>';
                                    }
                                    ?>
                                    <option value="watch_later">Посмотреть позже</option>
                                    <option value="favorites">Избранное</option>
                                </select>
                                <button class="mt-uix-button mt-button-primary" type="submit" name="add">Добавить</button>
                            </form>
                            <?php
                            }else{
                            ?>
                            <h2 id="vm_h">Войдите в аккаунт чтобы продолжить</h2>
                            <form action="/login?return=<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST" style="padding:10px;">
                                <div class="b">
                                    <b>Email</b>
                                </div>
                                <div style="margin: 0 0 1.5em;">
                                    <input type="text" class="input" name="email" style="width:40%">
                                </div>
                                <div class="b">
                                    <b>Пароль</b>
                                </div>
                                <div style="margin: 0 0 1.5em;">
                                    <input type="password" class="input" name="pwd" style="width:40%">
                                </div>
                                <div class="mt-display-flex mt-align-center">
                                    <button class="mt-uix-button mt-button-primary" name="login" type="submit">Войти</button>
                                </div><br>
                                <div>
                                    <a href="/register">
                                        Зарегистроваться
                                    </a>
                                </div>
                            </form>
                            <?php } ?>
                        </div>
                        <div id="panel-flag" class="panel hidden" style="padding:10px;">
                            <?php
                            if($_COOKIE['MeTubeUID'] != 0){
                            ?>
                            <h2 id="vm_h">Пожаловаться</h2>
                            <form style="padding:10px;" method="POST">
                                <h3 style="color:#666;">Вы уверены то что данное видео нарушает <a href="/rules?o=wUV">правила MeTube</a>?</h3>
                                <h5 style="color:#999;">Если оно не нарушает, то мы вам ограничим доступ к MeTube на 2 дня (3.2 пункт в <a href="/rules?o=wUV">правилах MeTube</a>)</h5>
                                <button class="mt-uix-button mt-button-primary" type="submit" name="flag">Да, я уверен</button>
                            </form>
                            <?php
                            }else{
                            ?>
                            <h2 id="vm_h">Войдите в аккаунт чтобы продолжить</h2>
                            <form action="/login?return=<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST" style="padding:10px;">
                                <div class="b">
                                    <b>Email</b>
                                </div>
                                <div style="margin: 0 0 1.5em;">
                                    <input type="text" class="input" name="email" style="width:40%">
                                </div>
                                <div class="b">
                                    <b>Пароль</b>
                                </div>
                                <div style="margin: 0 0 1.5em;">
                                    <input type="password" class="input" name="pwd" style="width:40%">
                                </div>
                                <div class="mt-display-flex mt-align-center">
                                    <button class="mt-uix-button mt-button-primary" name="login" type="submit">Войти</button>
                                </div><br>
                                <div>
                                    <a href="/register">
                                        Зарегистроваться
                                    </a>
                                </div>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                    <div id="watch-discussion">
                        <h4 class="ch4">
                            <a href="#"><b>Все комментарии</b> (<?php echo $global->count('int', 'comments', 'commentVideo', $video['videoID']) ?>)</a>
                        </h4>
                        <?php
                        if(isset($_COOKIE['MeTubeUID']) && $_COOKIE['MeTubeUID'] != 0){
                            $userID = $_COOKIE['MeTubeUID'];
                            $user = $global->userData(1, $userID);
                        ?>
                        <div class="comment">
                            <div>
                                <a href="/user/<?php echo $user['username'] ?>">
                                    <img src="<?php echo $user['profileImage'] ?>">
                                </a>
                            </div>
                            <form class="comment-area" method="POST">
                                <textarea name="comment" onfocus="$('#addbut').removeClass('hidden');" placeholder="Поделитесь своими впечатлениями"></textarea>
                                <div class="mt-display-flex hidden" id="addbut">
                                    <button class="mt-uix-button mt-button-default" type="button" onclick="$('#addbut').addClass('hidden');$('textarea').val('')">Отмена</button>
                                    <button class="mt-uix-button mt-button-primary" type="submit" name="add_comment">Оставить Комментарий</button>
                                </div>
                            </form>
                        </div>
                        <?php }else{ ?>
                        <div class="comment-alert">
                            <a href="/ServiceLogin">Войдите в аккаунт</a> прямо сейчас чтобы написать комментарий
                        </div>
                        <?php } ?>
                        <?php
                        $vidik = $video['videoID'];
                        if(isset($_GET['comments_limit'])){
                            $limit = intval($_GET['comments_limit']);
                        }else{
                            $limit = 15;
                        }
                        $comments = mysqli_query($mysql, "SELECT * FROM comments LEFT JOIN users ON userID = commentBy WHERE commentVideo = $vidik ORDER BY commentID DESC LIMIT $limit");
                        while($comment = mysqli_fetch_assoc($comments)){
                        ?>
                        <div class="comment" id="comment_<?php echo $comment['commentID'] ?>">
                            <div>
                                <a href="/user/<?php echo $comment['username'] ?>">
                                    <img src="<?php echo $comment['profileImage'] ?>">
                                </a>
                            </div>
                            <div style="margin-left:12px; width:100%;position: relative;">
                                <div class="mt-display-flex mt-align-center">
                                    <a href="/user/<?php echo $comment['username'] ?>" class="author">
                                        <?php echo $comment['username'] ?>
                                    </a>
                                    <a href="#" id="added">
                                        <?php echo $global->formatDate(date('F j, Y',strtotime($comment['commentAdded']))); ?>
                                    </a>
                                    <?php
                                    if($_COOKIE['MeTubeUID'] == $comment['commentBy'] or $userID == $video['videoBy']){
                                        ?>
                                    <img src="img/p.png" data-open="#dropdown_comment<?php echo $comment['commentID'] ?>" class="arrow-action-menu">
                                    <?php } ?>
                                    <div class="dropdown hidden" id="dropdown_comment<?php echo $comment['commentID'] ?>">
                                        <?php
                                        if($_COOKIE['MeTubeUID'] == $comment['commentBy'] or $userID == $video['videoBy']){
                                            echo '<div class="dropdown_section delete_c" data-comment="'.$comment['commentID'].'">
                                            Удалить
                                        </div>';
                                        }else{
                                            echo '';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <p><?php echo $global->getMention($comment['commentContent']) ?></p>
                                <div class="mt-display-flex mt-align-center">
                                    <a id="reply" data-author="<?php echo $comment['username'] ?>"><b>Ответить</b></a>
                                    <span class="mt-user-separator"><b>·</b></span>
                                    <span class="comments-like" id="comment_likes<?php echo $comment['commentID'] ?>"><?php
                                        echo $global->count('int', 'likes_comments', 'likeTo', $comment['commentID']);
                                    ?></span>
                                    <img src="img/p.png" class="<?php if($global->checkCommentLike($comment['commentID'], $_COOKIE['MeTubeUID']) === true){ echo 'liked-comment'; }else{ echo 'like-comment'; } ?>" id="likecom<?php echo $comment['commentID'] ?>" data-comment="<?php echo $comment['commentID'] ?>">
                                    <span class="comments-dislike" id="comment_dislikes<?php echo $comment['commentID'] ?>"><?php
                                        echo $global->count('int', 'dislikes_comments', 'dislikeTo', $comment['commentID']);
                                    ?></span>
                                    <img src="img/p.png" class="<?php if($global->checkCommentDislike($comment['commentID'], $_COOKIE['MeTubeUID']) === true){ echo 'disliked-comment'; }else{ echo 'dislike-comment'; } ?>" id="dislikecom<?php echo $comment['commentID'] ?>"data-comment="<?php echo $comment['commentID'] ?>">
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <?php
                        if($global->count('int', 'comments', 'commentVideo', $video['videoID']) > $limit){
                        ?>
                        <center><button type="button" class="mt-uix-button mt-button-default global--link" data-link="/watch?v=<?php echo $video['videoGetID'] ?>&comments_limit=<?php echo $limit + 15; ?>">Загрузить больше</button></center>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div id="related">
                <?php
                $videoTitle = $video['videoTitle'];
                $tags = $video['videoTags'];
                $related = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE (videoTags LIKE '%$tags%' AND videoID != $videoID) AND ban = '' ORDER BY rand() LIMIT 15");
                while($video = mysqli_fetch_assoc($related)){
                ?>
                <div class="related-video">
                    <div id="thumbmail-video">
                        <a href="watch?v=<?php echo $video['videoGetID'] ?>"><img src="uploads/<?php echo $video['videoGetID'] ?>.jpg" width="129" height="74"></a>
                        <span class="video-time"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                        <button title="Посмотреть позже" class="mt-uix-button hidden mt-button-default add-to-button-video <?php echo $global->watchLaterCheck($userID, $video['videoID']) ?>" type="button" data-vid="<?php echo $video['videoID'] ?>">
                            <img src="img/p.png">
                        </button>
                    </div>
                    <div style="padding-left: 9px;">
                        <a id="title" style="margin:0!important;" href="watch?v=<?php echo $video['videoGetID'] ?>">
                            <?php echo $video['videoTitle'] ?>
                        </a>
                        <div id="metadata">
                            <a id="mt-data-username" href="/user/<?php echo $video['username'] ?>">
                                <?php echo $video['username'] ?>
                            </a><br>
                            <span style="color:#000!important;margin-right: 5px;">
                                <?php echo $video['videoViews'] ?> просмотров
                            </span>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>