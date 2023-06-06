<?php
if($_SERVER['REQUEST_URI'] == '/homepage'){
    include '404.php';
    die();
}
include 'extra/init.php';
if(!isset($_COOKIE['MeTubeUID'])){
    header("Location: /login?return=".$_SERVER['REQUEST_URI']);
}
$userID = $_COOKIE['MeTubeUID'];
$user = $global->userData(1, $userID);
if(!empty($user['ban'])){
    include $_SERVER['DOCUMENT_ROOT'].'/banned.php';
}
$profileUN = $_GET['v'];
if($global->check('users', 'username', $profileUN) === true){
    $profile = $global->userData(2, $profileUN);
    $profileID = $profile['userID'];
    if(!empty($profile['ban'])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="<?php echo $link; ?>styles/2013-metube.css" rel="stylesheet">
    <title>MeTube - Be Yourself.</title>
    <link rel="icon" type="image/png" href="<?php echo $link; ?>img/favicon_32-vflWoMFGx.png">
    <script src="<?php echo $link; ?>js/jquery.js"></script>
    <script src="<?php echo $link; ?>js/jquery.form.js"></script> 
    <script src="<?php echo $link; ?>js/main.js"></script>
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
            <div class="alert alert-error">
                <div>
                    <img src="<?php echo $link; ?>img/p.png">
                </div>
                <div class="alert-message">
                    Данный канал заблокирован за нарушение правил MeTube.
                </div>
            </div>
        </div>
        <br>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>
<?php
die();
    }
}else{
    header("Location: /?return=".$_SERVER['REQUEST_URI']);
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="<?php echo $link; ?>styles/2013-metube.css" rel="stylesheet">
    <title>MeTube - Be Yourself.</title>
    <link rel="icon" type="image/png" href="<?php echo $link; ?>img/favicon_32-vflWoMFGx.png">
    <script src="<?php echo $link; ?>js/jquery.js"></script>
    <script src="<?php echo $link; ?>js/main.js"></script>
    <style>
        #container{
            margin-top: 0!important;
        }
    </style>
</head>
<body>
    <!-- metubee.xyz -->
    <div id="app">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/header.php'; ?>
        <div id="container">
            <div id="guide">
                <div class="guide_container">
                    <div>
                        <div class="guide-item-non-select global--link" data-link="<?php echo $link; ?>user/<?php echo $user['username'] ?>">
                            <span>
                                <?php echo $user['username'] ?>
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="<?php echo $link; ?>watch_later">
                            <span>
                                Посмотреть позже
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="<?php echo $link; ?>history">
                            <span>
                                История просмотра
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="<?php echo $link; ?>my_playlists">
                            <span>
                                Плейлисты
                            </span>
                        </div>
                        <div class="guide-section-separator"></div>
                        <div class="guide-item-non-select global--link" data-link="<?php echo $link; ?>">
                            <span>
                                Главная
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="<?php echo $link; ?>videos?o=P">
                            <span>
                                Публичные видео
                            </span>
                        </div>
                        
                        <div class="guide-section-separator"></div>
                        <h3>подписки</h3>
                        <?php
                        if($global->check('subscriptions', 'subscribeBy', $userID) === false){
                        ?>
                        <div id="guide-item-gray">
                            <button style="margin-left: auto;" class="mt-uix-button mt-button-primary global--link" data-link="/browse_channels"><img src="<?php echo $link ?>img/p.png" id="mt-button-add">Добавить каналы</button>
                            <?php
                            $chpm = mysqli_query($mysql, "SELECT * FROM users WHERE userID != $userID ORDER BY rand() LIMIT 5");
                            while($channel = mysqli_fetch_assoc($chpm)){
                            ?>
                            <div class="guide-item-non-select global--link" data-link="/user/<?php echo $channel['username'] ?>">
                                <img src="<?php echo $link.$channel['profileImage'] ?>">
                                <span>
                                    <?php echo $channel['username'] ?>
                                </span>
                            </div>
                        <?php } ?>
                        </div>
                        <?php
                        }else{
                            $subs = mysqli_query($mysql, "SELECT * FROM subscriptions LEFT JOIN users ON userID = subscribeTo WHERE subscribeBy = $userID AND ban = '' ORDER BY subscribeID DESC");
                            while($sub = mysqli_fetch_assoc($subs)){
                        ?>
                        <div class="guide-item-non-select global--link" data-link="<?php echo $link; ?>user/<?php echo $sub['username'] ?>">
                            <img src="<?php echo $link; ?><?php echo $sub['profileImage'] ?>">
                            <span>
                                <?php echo $sub['username'] ?>
                            </span>
                        </div>
                        <?php
                            }
                        }
                        ?>
                        <div class="guide-section-separator"></div>
                        <div class="guide-item-non-select global--link" data-link="<?php echo $link; ?>browse_channels">
                            <img src="<?php echo $link; ?>img/browse_channels.png">
                            <span>
                                Просмотреть каналы
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .channel_banner{
                    background: url("<?php echo $link; ?><?php echo $profile['profileBanner'] ?>") center/cover!important;
                }
            </style>
            <div id="channel">
                <div class="channel_branded">
                    <div class="channels_header">
                        <div class="channel_banner">
                            <div id="chlinks">
                                <?php
                                $links = mysqli_query($mysql, "SELECT * FROM links WHERE linkTo = $profileID ORDER BY linkID");
                                while($linkAb = mysqli_fetch_assoc($links)){
                                ?>
                                <div class="channel-link">
                                    <a href="<?php echo $linkAb['linkURL'] ?>">
                                        <img src="http://www.google.com/s2/favicons?domain=<?php echo $linkAb['linkURL'] ?>">
                                        <?php echo $linkAb['linkTitle'] ?>
                                    </a>
                                </div>
                            <?php } ?>
                            </div>
                            <a class="channel_pfp_container" href="<?php echo $link; ?>user/<?php echo $profile['username'] ?>">
                                <img src="<?php echo $link; ?><?php echo $profile['profileImage'] ?>">
                            </a>
                        </div>
                        <div id="chmi">
                            <div>
                                <div class="channel-u-title global--link" data-link="<?php echo $link; ?>user/<?php echo $profile['username'] ?>">
                                    <?php echo $profile['username'] ?>
                                    <?php
                                    if($profile['verifed'] == 1){ echo '<img src="'.$link.'img/p.png" class="verifed-icon">'; }
                                    ?>
                                </div>
                            </div>
                            <div class="mt-display-flex mt-margin-left">
                                <?php
                                if($userID == $profileID){
                                ?>
                                    <div style="display: flex;">
                                        <button class="mt-uix-button mt-uix-button-subscribe global--edit-channel" data-user="<?php echo $profileID ?>">
                                            <span class="mt-subscribe-icon">
                                                <img src="<?php echo $link ?>img/p.png">
                                            </span>
                                            <span id="subscribe_text">
                                                
                                            </span>
                                        </button>
                                    </div>
                                <?php }else{ ?>
                                    <div style="display: flex;">
                                        <button class="mt-uix-button mt-uix-button-subscribe <?php if($global->checkSubscribe($profileID, $userID) === false){ echo 'global--subscribe'; }else{ echo 'global--subscribed'; } ?>" data-user="<?php echo $profileID ?>">
                                            <span class="mt-subscribe-icon">
                                                <img src="<?php echo $link ?>img/p.png">
                                            </span>
                                            <span id="subscribe_text">
                                                
                                            </span>
                                        </button>
                                        <span class="watch-page-subs-counter">
                                            <?php
                                            echo $global->count('int', 'subscriptions', 'subscribeTo', $profileID);
                                            ?>
                                        </span>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                        <div class="channel-actions">
                            <div class="channel-action channel-action-active global--link" data-link="<?php echo $link; ?>user/<?php echo $profile['username'] ?>" style="margin:0;">
                                <img src="<?php echo $link ?>img/p.png" class="channel-home">
                            </div>
                            <div class="channel-action global--link" data-link="<?php echo $link; ?>user/<?php echo $profile['username'] ?>/videos">
                                Видео
                            </div>
                            <div class="channel-action global--link" data-link="<?php echo $link; ?>user/<?php echo $profile['username'] ?>/community">
                                Сообщество
                            </div>
                            <div class="channel-action global--link" data-link="<?php echo $link; ?>user/<?php echo $profile['username'] ?>/about">
                                О канале
                            </div>
                            <div class="channel-action" id="chs">
                                <img src="<?php echo $link ?>img/p.png" class="channel-search">
                            </div>
                            <form class="chS hidden" action="/results">
                                <input type="text" name="query" class="input" placeholder="Поиск">
                                <input class="hidden" type="submit" name="user" value="<?php echo $profile['username'] ?>">
                            </form>
                        </div>
                    </div>
                    <div>
                        <?php
                        if(!isset($profile['trailer_video']) or empty($profile['trailer_video'])){
                            $ov = mysqli_query($mysql, "SELECT * FROM videos WHERE videoBy = $profileID ORDER BY videoID DESC LIMIT 1");
                            while($video = mysqli_fetch_assoc($ov)){
                        
                        ?>
                        <div class="container-trailer-video">
                            <div class="trailer-player">
                            <style>
                       
                    .vlPlayer2012:focus {
                      outline: none;
                    }
                    </style>
                    
                        <!-- Left side of the watch page where the video player is !-->
                            <!-- Video Player !-->
                            <!--<div style="width:480px;height:388px" class="videocontainer" id="video_height" oncontextmenu="return false;">-->
                                    <script type="text/javascript">function v_play(){player.ended||player.paused?(player.play(),document.getElementById("left").style.backgroundImage="url('/img/ply0.png')"):(player.pause(),document.getElementById("left").style.backgroundImage="url('/img/ply1.png')")}function v_mute(){player.muted?(document.getElementById("right").style.backgroundImage="url('/img/vol1.png')",player.muted=!1):(document.getElementById("right").style.backgroundImage="url('/img/vol0.png')",player.muted=!0)}</script>
                                                <script src="<?php echo $link; ?>player/main15.js"></script>
                                        
                                        <div id="player">
                        <noscript>
                                    No Javascript!
                                </noscript>
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
                                src: "<?php echo $link ?>uploads/<?php echo $video['videoGetID'] ?>.m4v",
                                img: "<?php echo $link ?>uploads/<?php echo $video['videoGetID'] ?>.jpg",
                                autoplay: false,
                                
                                
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
                                            skin: "<?php echo $link; ?>player/skins/",
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
                            <div class="trailer-details">
                                <h3>
                                    <a href="<?php echo $link.'watch?v='.$video['videoGetID'] ?>"><?php echo $video['videoTitle'] ?></a>
                                </h3>
                                <div>
                                    <?php echo $video['videoViews'] ?> просмотров
                                </div>
                                <div class="text-description">
                                    <?php echo $video['videoDescription'] ?>
                                </div>
                            </div>
                            </div>
                            <?php
                                }
                            }else{
                                $trailer = $profile['trailer_video'];
                                $ov = mysqli_query($mysql, "SELECT * FROM videos WHERE videoGetID = '$trailer' ORDER BY videoID DESC LIMIT 1");
                            while($video = mysqli_fetch_assoc($ov)){
                        
                        ?>
                        <div class="container-trailer-video">
                            <div class="trailer-player">
                            <style>
                       
                    .vlPlayer2012:focus {
                      outline: none;
                    }
                    </style>
                    
                        <!-- Left side of the watch page where the video player is !-->
                            <!-- Video Player !-->
                            <!--<div style="width:480px;height:388px" class="videocontainer" id="video_height" oncontextmenu="return false;">-->
                                    <script type="text/javascript">function v_play(){player.ended||player.paused?(player.play(),document.getElementById("left").style.backgroundImage="url('/img/ply0.png')"):(player.pause(),document.getElementById("left").style.backgroundImage="url('/img/ply1.png')")}function v_mute(){player.muted?(document.getElementById("right").style.backgroundImage="url('/img/vol1.png')",player.muted=!1):(document.getElementById("right").style.backgroundImage="url('/img/vol0.png')",player.muted=!0)}</script>
                                                <script src="<?php echo $link; ?>player/main15.js"></script>
                                        
                                        <div id="player">
                        <noscript>
                                    No Javascript!
                                </noscript>
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
                                src: "<?php echo $link ?>uploads/<?php echo $video['videoGetID'] ?>.m4v",
                                img: "<?php echo $link ?>uploads/<?php echo $video['videoGetID'] ?>.jpg",
                                autoplay: false,
                                
                                
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
                                            skin: "<?php echo $link ?>player/skins/",
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
                            <div class="trailer-details">
                                <h3>
                                    <a href="<?php echo $link.'watch?v='.$video['videoGetID'] ?>"><?php echo $video['videoTitle'] ?></a>
                                </h3>
                                <div>
                                    <?php echo $video['videoViews'] ?> просмотров
                                </div>
                                <div class="text-description">
                                    <?php echo $video['videoDescription'] ?>
                                </div>
                            </div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                    </div>
                    <?php
                    if($global->check('channel_playlist', 'chTo', $profileID) === false){
                    ?>
                    <div id="chpla">
                        <div class="mt-display-flex mt-align-center channel-playlist">
                            Недавние загрузки
                        </div>
                        <div id="oH">
                            <?php
                            if($global->check('videos', 'videoBy', $profileID) === false){
                                echo '<center class="mt-display-flex mt-align-center" style="justify-content:center;">
                                <img src="'.$link.'img/err.png" style="margin-right:10px;" width="100">
                                <span>Ничего не найдено );</span>
                                </center>';
                            }
                            ?>
                            <?php
                            $q = mysqli_query($mysql, "SELECT * FROM videos WHERE videoBy = $profileID ORDER BY videoID DESC LIMIT 5");
                            while($video = mysqli_fetch_assoc($q)){
                            ?>
                            <div id="video-cell">
                                <div id="thumbmail-video">
                                    <a href="<?php echo $link ?>watch?v=<?php echo $video['videoGetID'] ?>"><img src="<?php echo $link ?>uploads/<?php echo $video['videoGetID'] ?>.jpg"></a>
                                    <span class="video-time" style="display:block!important"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                                </div>
                                <a id="title" style="margin:0!important;" href="<?php echo $link ?>watch?v=<?php echo $video['videoGetID'] ?>">
                                    <?php echo $video['videoTitle'] ?>
                                </a>
                                <div id="metadata">
                                    <span style="margin-right: 5px;">
                                        <?php echo $video['videoViews'] ?> просмотров
                                    </span>
                                    <span>
                                        <?php echo $global->formatDate(date('F j, Y',strtotime($video['videoAdded']))); ?>
                                    </span>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                        }else{
                            $channel_playlist = mysqli_query($mysql, "SELECT * FROM channel_playlist LEFT JOIN playlists ON playlistGetID = chPlaylist WHERE chTo = $profileID ORDER BY chID DESC");
                            while($playlist = mysqli_fetch_assoc($channel_playlist)){
                    ?>
                    <div id="chpla">
                        <div class="mt-display-flex mt-align-center channel-playlist">
                            <?php echo $playlist['playlistTitle'] ?>
                        </div>
                        <div id="oH">
                            <?php
                            $playlistID = $playlist['playlistID'];
                            $q = mysqli_query($mysql, "SELECT * FROM playlist_videos LEFT JOIN videos ON videoID = pvVideo LEFT JOIN users ON userID = videoBy WHERE pvTo = $playlistID ORDER BY pvID DESC LIMIT 5");
                            if(mysqli_num_rows($q) == 0){
                                echo '<center class="mt-display-flex mt-align-center" style="justify-content:center;">
                                <img src="'.$link.'img/err.png" style="margin-right:10px;" width="100">
                                <span>Данный плейлист пуст</span>
                                </center>';
                            }
                            while($video = mysqli_fetch_assoc($q)){
                            ?>
                            <div id="video-cell">
                                <div id="thumbmail-video">
                                    <a href="<?php echo $link ?>watch?v=<?php echo $video['videoGetID'] ?>"><img src="<?php echo $link ?>uploads/<?php echo $video['videoGetID'] ?>.jpg"></a>
                                    <span class="video-time" style="display:block!important"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                                </div>
                                <a id="title" style="margin:0!important;" href="<?php echo $link ?>watch?v=<?php echo $video['videoGetID'] ?>">
                                    <?php echo $video['videoTitle'] ?>
                                </a>
                                <div id="metadata">
                                    <span style="margin-right: 5px;">
                                        <?php echo $video['videoViews'] ?> просмотров
                                    </span>
                                    <span>
                                        <?php echo $global->formatDate(date('F j, Y',strtotime($video['videoAdded']))); ?>
                                    </span>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        
                    </div>
                <?php
                    }
                }
                ?>
                </div>
                <div class="channels_branded">
                    <?php
                    if($global->count('int', 'fav_channels', 'favTo', $profileID) > 0){
                        echo '<div>
                        <span id="titlefav">
                            Избранные каналы
                        </span>
                    </div>';
                    }
                    ?>
                    <div style="margin-top: 2px;">
                        <?php
                        $favq = mysqli_query($mysql, "SELECT * FROM fav_channels LEFT JOIN users ON userID = favCh WHERE favTo = $profileID ORDER BY favID");
                        while($channel = mysqli_fetch_assoc($favq)){
                        ?>
                        <div class="mt-display-flex">
                            <div>
                                <a href="<?php echo $link ?>/user/<?php echo $channel['username'] ?>"><img src="<?php echo $link.$channel['profileImage'] ?>" id="ava"></a>
                            </div>
                            <div>
                                <div>
                                    <a href="<?php echo $link ?>/user/<?php echo $channel['username'] ?>"><?php echo $channel['username'] ?></a>
                                </div>
                                <div>
                                    <div style="display: flex;">
                                        <button class="mt-uix-button mt-button-default global--link" data-link="<?php echo $link.'/user/'.$channel['username'] ?>" style="font-size: 10px;padding: 0 3px;height: 20px;color: #888;">
                                            Подписаться
                                        </button>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>