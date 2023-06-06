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
<?php
if(!isset($_POST['sort'])){
    $_POST['sort'] = 'DESC';
}
if(!isset($_GET['limit'])){
    $limit = 15;
}else{
    if($_GET['limit'] < 15){
        $limit = 15;
    }else{
        $limit = $_GET['limit'];
    }
}
if(isset($_POST['limit'])){
    $limit = $limit + 15;
    header("Location: /user/".$profile['username']."/videos?limit=".$limit);
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
    <?php
    if($limit > 15){
    ?>
    <script type="text/javascript">
         function scrollToBottom() {
        window.scrollTo(0, document.body.scrollHeight);
    }
    history.scrollRestoration = "manual";
    window.onload = scrollToBottom;

    </script>
<?php } ?>
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
                                                <img src="<?php echo $link; ?>img/p.png">
                                            </span>
                                            <span id="subscribe_text">
                                                
                                            </span>
                                        </button>
                                    </div>
                                <?php }else{ ?>
                                    <div style="display: flex;">
                                        <button class="mt-uix-button mt-uix-button-subscribe <?php if($global->checkSubscribe($profileID, $userID) === false){ echo 'global--subscribe'; }else{ echo 'global--subscribed'; } ?>" data-user="<?php echo $profileID ?>">
                                            <span class="mt-subscribe-icon">
                                                <img src="<?php echo $link; ?>img/p.png">
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
                            <div class="channel-action global--link" data-link="<?php echo $link; ?>user/<?php echo $profile['username'] ?>" style="margin:0;">
                                <img src="<?php echo $link ?>img/p.png" class="channel-home">
                            </div>
                            <div class="channel-action channel-action-active global--link" data-link="<?php echo $link; ?>user/<?php echo $profile['username'] ?>/videos">
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
                        <div class="channel-actions" style="padding:10px;">
                            <form method="POST">
                                <select name="sort" class="mt-uix-button mt-button-default" onchange="this.form.submit()">
                                    <option value="DESC" <?php if($_POST['sort'] == 'DESC'){ echo 'selected'; } ?>>Сортировать: от новых к старым</option>
                                    <option value="ASC" <?php if($_POST['sort'] == 'ASC'){ echo 'selected'; } ?>>Сортировать: от старых к новым</option>
                                </select>
                            </form>
                        </div>
                        <div id="oH" style="padding:20px;">
                            <?php
                            $sort = $_POST['sort'];
                            $videos = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE videoBy = $profileID AND videoPrivate != 1 ORDER BY videoID $sort LIMIT $limit");
                            while($video = mysqli_fetch_assoc($videos)){
                            ?>
                            <div id="video-cell">
                                <div id="thumbmail-video">
                                    <a href="<?php echo $link ?>watch?v=<?php echo $video['videoGetID'] ?>"><img src="<?php echo $link ?>uploads/<?php echo $video['videoGetID'] ?>.jpg"></a>
                                    <span class="video-time" style="display: block!important;"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                                    
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
                        <?php
                        if($global->check('videos', 'videoBy', $profileID) === false){
                            echo '<center><img src="'.$link.'img/opdsj2.png" width="200"><br><h1 class="fn">У '.$profileUN.' нет видео</h1></center>';
                        }
                        ?>
                        <center>
                            <?php
                            if($global->count('int', 'videos', 'videoBy', $profileID) > $limit){
                            ?>
                            <form method="POST">
                                <button class="mt-uix-button mt-button-default" type="submit" name="limit">
                                    Загрузить больше
                                </button>
                            </form>
                            <?php } ?>
                        </center>
                    </div>
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