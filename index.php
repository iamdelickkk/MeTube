<?php
if(isset($_COOKIE['MeTubeUID']) && $_SERVER['REQUEST_URI'] != '/videos'){
    if(isset($_GET['logout']) && $_GET['logout'] == 'true'){
        header("Location: /extra/user/logout");
    }
    include 'homepage.php';
    die();
}
if($_SERVER['REQUEST_URI'] != '/videos'){
    include 'extra/init.php';
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
    <?php
    if(!isset($_COOKIE['MeTubeUID'])){
    ?>
    <script>
        $(function(){
            $(document).on('click', '.add-to-button-video', function(){
                $('body').html('');
                location.href = "/login";
            })
        })
    </script>
<?php } ?>
</head>
<body>
    <!-- metubee.xyz -->
    <div id="app">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/header.php'; ?>
        <?php
        if(!isset($_COOKIE['NoShowAd'])){
        ?>
        <div class="cad global--link" style="background-image: url('img/ads_commericals/<?php echo $ad ?>.jpg')" data-link="<?php echo $adlink[$ad]; ?>">
            <a href="settings?act=ads">
                Убрать рекламу
            </a>
        </div>
        <?php } ?>
        <div id="container">
            <div id="guide">
                <div class="guide_container">
                    <div>
                        <div class="guide-item global--link" data-link="/videos">
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
                        if(!isset($_COOKIE['MeTubeUID'])){
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
                            if(mysqli_num_rows($subs) == 0){
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
                            }
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
            <div id="videos">
                <div class="videos-new-videos">
                    <?php
                    $fvd = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE ban = '' ORDER BY rand() LIMIT 1"));
                    $fvdID = $fvd['videoID'];
                    ?>
                    <div class="videos-new-videos-large-shelf">
                        <div id="thumbmail-video">
                            <a href="watch?v=<?php echo $fvd['videoGetID'] ?>"><img src="uploads/<?php echo $fvd['videoGetID'] ?>.jpg"></a>
                            <span class="video-time"><?php echo $global->updateDuration($fvd['videoDuration']); ?></span>
                            <button title="Посмотреть позже" class="mt-uix-button hidden mt-button-default add-to-button-video">
                                <img src="img/p.png">
                            </button>
                        </div>
                        <a id="title" href="watch?v=<?php echo $fvd['videoGetID'] ?>">
                            <?php echo $fvd['videoTitle'] ?>
                        </a>
                        <div id="metadata">
                            <a id="mt-data-username" href="/user/<?php echo $fvd['username'] ?>">
                                <?php echo $fvd['username'] ?>
                            </a>
                            <span style="color:#000!important;margin-right: 5px;">
                                <?php echo $fvd['videoViews'] ?> просмотров
                            </span>
                        </div>
                    </div>
                    <div style="padding: 20px 20px 7px 20px;">
                        <?php
                        $vrw = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE videoID != $fvdID AND ban = '' ORDER BY rand() LIMIT 3");
                        while($video = mysqli_fetch_assoc($vrw)){
                        ?>
                        <div class="videos-new-videos-medium-shelf">
                            <div id="thumbmail-video">
                                <a href="watch?v=<?php echo $video['videoGetID'] ?>"><img src="uploads/<?php echo $video['videoGetID'] ?>.jpg"></a>
                                <span class="video-time"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                                <button title="Посмотреть позже" class="mt-uix-button hidden mt-button-default add-to-button-video">
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
                        <?php } ?>
                    </div>
                </div>
                <div class="videos-another-videos">
                    <div class="videos-categories">
                        <div class="videos-cell-container">
                            <div class="videos-cell-container-name">
                                Фильмы
                            </div>
                            <?php
                            $faa = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE ban = '' AND videoCategory = 'film_and_animation' ORDER BY rand() LIMIT 1");
                            if(mysqli_num_rows($faa) == 0){
                                 echo '<div style="width:205px;display:flex;align-items:center;justify-content:center;">Ничего не найдено );</div>';
                            }
                            while($video = mysqli_fetch_assoc($faa)){
                            ?>
                            <div id="video-cell">
                                <div id="thumbmail-video" style="width: 165px;height: 92px;">
                                    <a href="watch?v=<?php echo $video['videoGetID'] ?>"><img src="uploads/<?php echo $video['videoGetID'] ?>.jpg"></a>
                                    <span class="video-time"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                                    <button title="Посмотреть позже" class="mt-uix-button hidden mt-button-default add-to-button-video">
                                        <img src="img/p.png">
                                    </button>
                                </div>
                                <a id="title" style="margin:0!important;" href="watch?v=<?php echo $video['videoGetID'] ?>">
                                    <?php echo $video['videoTitle'] ?>
                                </a>
                                <div id="metadata">
                                    <span style="margin-right: 5px;">
                                        <?php echo $video['videoViews'] ?> просмотров
                                    </span>
                                    <span>
                                        <?php echo $video['videoAdded']; ?>
                                    </span>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                        <div class="videos-cell-container">
                            <div class="videos-cell-container-name">
                                Музыка
                            </div>
                            <?php
                            $mus = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE ban = '' AND videoCategory = 'music' ORDER BY rand() LIMIT 1");
                            if(mysqli_num_rows($mus) == 0){
                                 echo '<div style="width:205px;display:flex;align-items:center;justify-content:center;">Ничего не найдено );</div>';
                            }
                            while($video = mysqli_fetch_assoc($mus)){
                            ?>
                            <div id="video-cell">
                                <div id="thumbmail-video" style="width: 165px;height: 92px;">
                                    <a href="watch?v=<?php echo $video['videoGetID'] ?>"><img src="uploads/<?php echo $video['videoGetID'] ?>.jpg"></a>
                                    <span class="video-time"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                                    <button title="Посмотреть позже" class="mt-uix-button hidden mt-button-default add-to-button-video">
                                        <img src="img/p.png">
                                    </button>
                                </div>
                                <a id="title" style="margin:0!important;" href="watch?v=<?php echo $video['videoGetID'] ?>">
                                    <?php echo $video['videoTitle'] ?>
                                </a>
                                <div id="metadata">
                                    <span style="margin-right: 5px;">
                                        <?php echo $video['videoViews'] ?> просмотров
                                    </span>
                                    <span>
                                        <?php echo $video['videoAdded']; ?>
                                    </span>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                        <div class="videos-cell-container">
                            <div class="videos-cell-container-name">
                                Спорт 
                            </div>
                            <?php
                            $spo = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE ban = '' AND videoCategory = 'sports' ORDER BY rand() LIMIT 1");
                            if(mysqli_num_rows($spo) == 0){
                                echo '<div style="width:205px;display:flex;align-items:center;justify-content:center;">Ничего не найдено );</div>';
                            }
                            while($video = mysqli_fetch_assoc($spo)){
                            ?>
                            <div id="video-cell">
                                <div id="thumbmail-video" style="width: 165px;height: 92px;">
                                    <a href="watch?v=<?php echo $video['videoGetID'] ?>"><img src="uploads/<?php echo $video['videoGetID'] ?>.jpg"></a>
                                    <span class="video-time"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                                    <button title="Посмотреть позже" class="mt-uix-button hidden mt-button-default add-to-button-video">
                                        <img src="img/p.png">
                                    </button>
                                </div>
                                <a id="title" style="margin:0!important;" href="watch?v=<?php echo $video['videoGetID'] ?>">
                                    <?php echo $video['videoTitle'] ?>
                                </a>
                                <div id="metadata">
                                    <span style="margin-right: 5px;">
                                        <?php echo $video['videoViews'] ?> просмотров
                                    </span>
                                    <span>
                                        <?php echo $video['videoAdded']; ?>
                                    </span>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                        <?php
                        $vidsBy = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE ban = '' ORDER BY rand() LIMIT 3");
                        while($video = mysqli_fetch_assoc($vidsBy)){
                        ?>
                        <div class="videos-cell-container">
                            <div class="videos-cell-container-name">
                                <?php echo $video['username'] ?>
                            </div>
                            <div id="video-cell">
                                <div id="thumbmail-video" style="width: 165px;height: 92px;">
                                    <a href="watch?v=<?php echo $video['videoGetID'] ?>"><img src="uploads/<?php echo $video['videoGetID'] ?>.jpg"></a>
                                    <span class="video-time"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                                    <button title="Посмотреть позже" class="mt-uix-button hidden mt-button-default add-to-button-video">
                                        <img src="img/p.png">
                                    </button>
                                </div>
                                <a id="title" style="margin:0!important;" href="watch?v=<?php echo $video['videoGetID'] ?>">
                                    <?php echo $video['videoTitle'] ?>
                                </a>
                                <div id="metadata">
                                    <span style="margin-right: 5px;">
                                        <?php echo $video['videoViews'] ?> просмотров
                                    </span>
                                    <span>
                                        <?php echo $video['videoAdded']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                    <div style="width:175px;padding: 0 14px!important;border-bottom: 1px solid #e2e2e2;border-right: 1px solid #e2e2e2;">
                        <div>
                            <div>
                                <div class="videos-cell-container-name" style="margin:0;">
                                    Избранное
                                </div>
                                <?php
                                $vf = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE videoFeatured = 1 AND ban = '' ORDER BY videoID DESC LIMIT 3");
                                if(mysqli_num_rows($vf) == 0){
                                    echo '<center>Ничего не найдено );</center>';
                                }
                                while($video = mysqli_fetch_assoc($vf)){
                                ?>
                                <div id="videos-cell-videos-small">
                                    <div id="thumbmail-video">
                                        <a href="watch?v=<?php echo $video['videoGetID'] ?>"><img src="uploads/<?php echo $video['videoGetID'] ?>.jpg"></a>
                                        <span class="video-time"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                                        <button title="Посмотреть позже" class="mt-uix-button hidden mt-button-default add-to-button-video">
                                            <img src="img/p.png">
                                        </button>
                                    </div>
                                    <div style="margin-left:5px;">
                                        <a id="title" style="margin:0!important;font-size:12px;" href="watch?v=<?php echo $video['videoGetID'] ?>">
                                            <?php echo $video['videoTitle'] ?>
                                        </a>
                                        <div id="metadata">
                                            <a id="mt-data-username" href="/user/<?php echo $video['username'] ?>">
                                                <?php echo $video['username'] ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div>
                                <div class="videos-cell-container-name" style="margin:0;">
                                    Популярное сегодня
                                </div>
                                <?php
                                $today = date('Y-m-d');
                                $vp = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE ban = '' AND videoAdded = '$today' ORDER BY videoViews DESC LIMIT 3");
                                if(mysqli_num_rows($vp) == 0){
                                    echo '<center>Ничего не найдено );</center>';
                                }
                                while($video = mysqli_fetch_assoc($vp)){
                                ?>
                                <div id="videos-cell-videos-small">
                                    <div id="thumbmail-video">
                                        <a href="watch?v=<?php echo $video['videoGetID'] ?>"><img src="uploads/<?php echo $video['videoGetID'] ?>.jpg"></a>
                                        <span class="video-time"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                                        <button title="Посмотреть позже" class="mt-uix-button hidden mt-button-default add-to-button-video">
                                            <img src="img/p.png">
                                        </button>
                                    </div>
                                    <div style="margin-left:5px;">
                                        <a id="title" style="margin:0!important;font-size:12px;" href="watch?v=<?php echo $video['videoGetID'] ?>">
                                            <?php echo $video['videoTitle'] ?>
                                        </a>
                                        <div id="metadata">
                                            <a id="mt-data-username" href="/user/<?php echo $video['username'] ?>">
                                                <?php echo $video['username'] ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>