<?php
include 'extra/init.php';
if(!isset($_GET['query'])){
    header("Location: /");
}
if(isset($_COOKIE['MeTubeUID'])){
    $userID = $_COOKIE['MeTubeUID'];
    $user = $global->userData(1, $userID);
}
$query = htmlspecialchars($_GET['query']);
if(!isset($_GET['limit'])){
    $limit = 15;
}else{
    $limit = intval($_GET['limit']);
}
if(isset($_GET['user'])){
    $userGET = $global->userData(2, $_GET['user']);
    $userID = $userGET['userID'];
    $searchQuery = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE (videoTitle LIKE '%$query%' OR videoDescription LIKE '%$query%' OR videoTags LIKE '%$query%') AND (videoBy = $userID AND ban = '') ORDER BY videoID DESC LIMIT $limit");
    $searchQueryNoLimit = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE (videoTitle LIKE '%$query%' OR videoDescription LIKE '%$query%' OR videoTags LIKE '%$query%') AND (videoBy = $userID AND ban = '') ORDER BY videoID DESC");
}else{
    $searchQuery = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE (videoTitle LIKE '%$query%' OR videoDescription LIKE '%$query%' OR videoTags LIKE '%$query%') AND ban = '' ORDER BY videoID DESC LIMIT $limit");
    $searchQueryNoLimit = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy WHERE (videoTitle LIKE '%$query%' OR videoDescription LIKE '%$query%' OR videoTags LIKE '%$query%') AND ban = '' ORDER BY videoID DESC");
}
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
            <div id="homepage">
                <div class="mt-display-flex mt-align-center" style="padding:10px;">
                    <div style="margin-left:auto;color:#555">
                        Найдено <?php echo mysqli_num_rows($searchQueryNoLimit) ?> видео
                    </div>
                </div>
                <div style="padding:10px;">
                    <?php
                    if(empty($query) or ctype_space($query)){
                        echo '<script>location.href = `/`</script>';
                        die();
                    }else if(mysqli_num_rows($searchQueryNoLimit) == 0){
                        echo '<center><h1>Ничего не найдено...</h1></center>';
                    }else{
                        while($video = mysqli_fetch_assoc($searchQuery)){
                        ?>
                        <div class="related-video">
                            <div id="thumbmail-video">
                                <a href="watch?v=<?php echo $video['videoGetID'] ?>"><img src="uploads/<?php echo $video['videoGetID'] ?>.jpg" width="185" height="104"></a>
                                <span class="video-time"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                                <button title="Посмотреть позже" class="mt-uix-button hidden mt-button-default add-to-button-video <?php echo $global->watchLaterCheck($userID, $video['videoID']) ?>" type="button" data-vid="<?php echo $video['videoID'] ?>">
                                    <img src="img/p.png">
                                </button>
                            </div>
                            <div style="padding-left: 9px;">
                                <a id="title" style="margin:0!important;" href="/watch?v=<?php echo $video['videoGetID'] ?>">
                                    <?php echo $video['videoTitle'] ?>
                                </a>
                                <div id="metadata">
                                    <a id="mt-data-username" href="/user/<?php echo $video['username'] ?>">
                                        <?php echo $video['username'] ?>                            </a><br>
                                    <span style="color:#000!important;margin-right: 5px;">
                                        <?php echo $video['videoViews'] ?> просмотров
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php
                if(mysqli_num_rows($searchQueryNoLimit) > $limit){
                    $newLimit = $limit + 15;
                    echo '<center>
                    <button type="button" class="mt-uix-button mt-button-default global--link" data-link="/results?query='.$query.'&limit='.$newLimit.'">Загрузить больше</button>
                    </center>';
                }
                ?>
                </div>
            </div>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>