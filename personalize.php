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
if(isset($_FILES['cvr'])){
    $cvr = $global->uploadImage($_FILES['cvr']);
    if(!empty($cvr)){
        $cvu = mysqli_query($mysql, "UPDATE users SET profileBanner = '$cvr' WHERE userID = $userID");
        header("Location: /personalize");
    }
}
if(isset($_FILES['pfp'])){
    $pfp = $global->uploadImage($_FILES['pfp']);
    if(!empty($pfp)){
         $c = mysqli_query($mysql, "UPDATE users SET profileImage = '$pfp' WHERE userID = $userID");
        header("Location: /personalize");
    }
}
if(isset($_POST['add_trailer'])){
    $videoGetID = $global->getMetubeID($_POST['url']);
    $video = $global->videoData($videoGetID);
    if(!empty($videoGetID)){
        if($video['videoBy'] == $userID){
            $videoGetIDU = $video['videoGetID'];
            $upd = mysqli_query($mysql, "UPDATE users SET trailer_video = '$videoGetIDU' WHERE userID = $userID");
            header("Location: /personalize");
        }
    }
}
if(isset($_POST['username'])){
    if(!empty($_POST['username']) && !ctype_space($_POST['username'])){
        if($_POST['username'] != $user['username']){
            $favusername = $_POST['username'];
            $favuser = $global->userData(2, $favusername);
            $favuserID = $favuser['userID'];
            if(mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM fav_channels WHERE favCh = $favuserID AND favTo = $userID")) <= 15){
                if(mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM fav_channels WHERE favCh = $favuserID AND favTo = $userID")) == 0){
                
                    $cfv = mysqli_query($mysql, "INSERT INTO fav_channels(favCh, favTo)  VALUES($favuserID, $userID)");
                    header("Location: /personalize");
                }
            }else{
                echo "<script>alert('На вашем канале должно быть не больше 15 избранных каналов!');Location.href = '/personalize'</script>";
            }
        }
    }
}
if(isset($_POST['add'])){
    $pla = $_POST['playlist'];
    if($global->check('playlists', 'playlistGetID', $pla) === true){
        $playlist = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM playlists WHERE playlistGetID = '$pla'"));
        if($playlist['playlistBy'] == $userID){
            $ch = mysqli_query($mysql, "SELECT * FROM channel_playlist WHERE chTo = $userID AND chPlaylist = '$pla'");
            if(mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM channel_playlist WHERE chTo = $userID") > 5)){
                die("<script>alert('На вашем канале должно быть не больше 5 плейлистов!');Location.href = '/personalize'</script>");
            }
            if(mysqli_num_rows($ch) == 0){
                $c = mysqli_query($mysql, "INSERT INTO channel_playlist(chTo, chPlaylist) VALUES($userID, '$pla')");
            }
        }
    }
    header("Location: /personalize");
}
if(isset($_POST['add_link'])){
    if(!empty($_POST['title']) && !empty($_POST['url'])){
        if(!ctype_space($_POST['title']) && !ctype_space($_POST['url'])){
            if($global->count('int', 'links', 'linkTo', $userID) < 10){
                $title = $_POST['title'];
                $url = $_POST['url'];
                $addlink = mysqli_query($mysql, "INSERT INTO links(linkTo, linkTitle, linkURL) VALUES($userID, '$title', '$url')");
                header("Location: /personalize");
            }
        }
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
        #container{
            margin-top: 0!important;
        }
    </style>
</head>
<body>
    <!-- metubee.xyz -->
    <div id="popups">

    </div>
    <div id="app">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/header.php'; ?>
        <div id="container">
            <div id="guide">
                <div class="guide_container">
                    <div>
                        <div class="guide-item-non-select global--link" data-link="/user/<?php echo $user['username'] ?>">
                            <span>
                                <?php echo $user['username'] ?>
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/watch_later">
                            <span>
                                Посмотреть позже
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/history">
                            <span>
                                История просмотра
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/my_playlists">
                            <span>
                                Плейлисты
                            </span>
                        </div>
                        <div class="guide-section-separator"></div>
                        <div class="guide-item-non-select global--link" data-link="/">
                            <span>
                                Главная
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/videos?o=P">
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
                        <div class="guide-item-non-select global--link" data-link="<?php echo $link ?>/browse_channels">
                            <img src="<?php echo $link ?>img/browse_channels.png">
                            <span>
                                Просмотреть каналы
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .channel_banner{
                    background: url("<?php echo $link; ?><?php echo $user['profileBanner'] ?>") center/cover!important;
                }
                .channel_banner button{
                    position: absolute;
                    right:0;
                    margin:10px;
                    border-radius: 0;
                    opacity: 0;
                }
                .channel_banner:hover button{
                    opacity: 1;
                }
                .channel_banner .dropdown{
                    margin-top:10px;
                }
            </style>
            <div id="channel">
                <div class="channel_branded">
                    <div class="channels_header">
                        <div class="channel_banner">
                            <div id="chlinks">
                                <?php
                                $links = mysqli_query($mysql, "SELECT * FROM links WHERE linkTo = $userID ORDER BY linkID");
                                while($link = mysqli_fetch_assoc($links)){
                                ?>
                                <div class="channel-link" id="link<?php echo $link['linkID'] ?>">
                                    <a href="<?php echo $link['linkURL'] ?>">
                                        <img src="http://www.google.com/s2/favicons?domain=<?php echo $link['linkURL'] ?>">
                                        <?php echo $link['linkTitle'] ?>
                                    </a>
                                    <a style="margin-left:5px;" href="javascript:void(0);" id="deletelink" data-link="<?php echo $link['linkID'] ?>">
                                        Удалить
                                    </a>
                                </div>
                            <?php } ?>
                                <div class="channel-link">
                                    <a href="javascript:void(0);" id="add_link_channel">
                                        +
                                    </a>
                                </div>
                            </div>
                            
                            <div class="channel_pfp_container">
                                <img src="<?php echo $link; ?><?php echo $user['profileImage'] ?>">
                            </div>
                            <button class="mt-uix-button mt-button-default mt-button-more" data-open="#dropdown_personalize"><img class="mt-uix-button-arrow" src="img/p.png" alt="" title=""></button>
                            <div class="dropdown hidden" id="dropdown_personalize">
                                <div class="dropdown_section" onclick="$('#pfp').click()">
                                    Изменить изображение канала
                                </div>
                                <div class="dropdown_section" onclick="$('#cvr').click()">
                                    Изменить обложку канала
                                </div>
                            </div>
                            <form method="post" class="hidden" enctype="multipart/form-data">
                                <input type="file" onchange="this.form.submit()" accept="image/*" name="pfp" id="pfp">
                                <input type="file" onchange="this.form.submit()" accept="image/*" name="cvr" id="cvr">
                            </form>
                        </div>
                        <div id="chmi">
                            <div>
                                <div class="channel-u-title global--link" data-link="<?php echo $link; ?>user/<?php echo $user['username'] ?>">
                                    <?php echo $user['username'] ?>
                                    <?php
                                    if($user['verifed'] == 1){ echo '<img src="img/p.png" class="verifed-icon">'; }
                                    ?>
                                </div>
                            </div>
                            <div class="mt-display-flex mt-margin-left">
                            
                            </div>
                        </div>
                        <div class="channel-actions" style="opacity: .5;">
                            <div class="channel-action channel-action-active" style="margin:0;">
                                <img src="img/p.png" class="channel-home">
                            </div>
                            <div class="channel-action">
                                Видео
                            </div>
                            <div class="channel-action">
                                Сообщество
                            </div>
                            <div class="channel-action">
                                О канале
                            </div>
                            <div class="channel-action" id="chs">
                                <img src="img/p.png" class="channel-search">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="choose-smth" id="trailer">
                            <div>
                                <div><img src="img/plus.png" width="72"></div>
                                <div>Изменить трейлер канала</div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if($global->check('channel_playlist', 'chTo', $userID) === false){
                    ?>
                    <div id="chpla">
                        <div class="mt-display-flex mt-align-center channel-playlist">
                            Недавние загрузки
                        </div>
                        <div id="oH">
                            <?php
                            $q = mysqli_query($mysql, "SELECT * FROM videos WHERE videoBy = $userID ORDER BY videoID DESC LIMIT 5");
                            while($video = mysqli_fetch_assoc($q)){
                            ?>
                            <div id="video-cell">
                                <div id="thumbmail-video">
                                    <a href="watch?v=<?php echo $video['videoGetID'] ?>"><img src="uploads/<?php echo $video['videoGetID'] ?>.jpg"></a>
                                    <span class="video-time"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                                </div>
                                <a id="title" style="margin:0!important;" href="watch?v=<?php echo $video['videoGetID'] ?>">
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
                            $channel_playlist = mysqli_query($mysql, "SELECT * FROM channel_playlist LEFT JOIN playlists ON playlistGetID = chPlaylist WHERE chTo = $userID ORDER BY chID DESC");
                            while($playlist = mysqli_fetch_assoc($channel_playlist)){
                    ?>
                    <div id="chpla" class="chpla<?php echo $playlist['playlistGetID'] ?>">
                        <div class="mt-display-flex mt-align-center channel-playlist">
                            <?php echo $playlist['playlistTitle'] ?>
                            <button type="button" id="deletechp" data-pl="<?php echo $playlist['playlistGetID'] ?>" class="mt-uix-button mt-button-default">Убрать</button>
                        </div>
                        <div id="oH">
                            <?php
                            $playlistID = $playlist['playlistID'];
                            $q = mysqli_query($mysql, "SELECT * FROM playlist_videos LEFT JOIN videos ON videoID = pvVideo LEFT JOIN users ON userID = videoBy WHERE pvTo = $playlistID ORDER BY pvID DESC LIMIT 5");
                            while($video = mysqli_fetch_assoc($q)){
                            ?>
                            <div id="video-cell">
                                <div id="thumbmail-video">
                                    <a href="watch?v=<?php echo $video['videoGetID'] ?>"><img src="uploads/<?php echo $video['videoGetID'] ?>.jpg"></a>
                                    <span class="video-time"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                                </div>
                                <a id="title" style="margin:0!important;" href="watch?v=<?php echo $video['videoGetID'] ?>">
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
                    <div>
                        <center><br>
                            <form method="post" class="hidden" id="playlists">
                                <select name="playlist" class="mt-uix-button mt-button-default" style="width:300px">
                                    <?php
                                    $pq = mysqli_query($mysql, "SELECT * FROM playlists WHERE playlistBy = $userID ORDER BY playlistID DESC");
                                    while($playlist = mysqli_fetch_assoc($pq)){
                                        echo '<option value="'.$playlist['playlistGetID'].'">'.$playlist['playlistTitle'].'</option>';
                                    }
                                    ?>
                                
                                </select>
                                <button class="mt-uix-button mt-button-primary" type="submit" name="add">Добавить</button>
                            </form>
                        </center>
                        <div class="choose-smth" onclick="$('#playlists').removeClass('hidden');$(this).remove()">
                            <div>
                                <div><img src="img/plus.png" width="48"></div>
                                <div>Добавить плейлист</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="channels_branded">
                    <div>
                        <span id="titlefav">
                            Избранные каналы
                        </span>
                    </div>
                    <div style="margin-top: 2px;">

                        <?php
                        $favq = mysqli_query($mysql, "SELECT * FROM fav_channels LEFT JOIN users ON userID = favCh WHERE favTo = $userID ORDER BY favID");
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
                                    <button type="button" id="deletefavch" data-favch="<?php echo $channel['favID'] ?>">Удалить</button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <form method="post" class="hidden" id="addchannel">
                            <div><input type="text" class="input" placeholder="Никнейм канала" name="username" style="width:146px;margin-right:10px;"></div>
                        </form>
                        <div class="choose-smth" style="margin:0;font-size:12px;" onclick="$('#addchannel').removeClass('hidden');$(this).remove()">
                            <div><img src="img/plus.png" width="28"></div>
                            <div>Добавить канал</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>