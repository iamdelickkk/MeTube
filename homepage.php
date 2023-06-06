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
                        <div class="guide-item global--link" data-link="/">
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
                        <div class="guide-item-non-select global--link" data-link="/browse_channels">
                            <img src="img/browse_channels.png">
                            <span>
                                Просмотреть каналы
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="homepage">
                <div class="welcomeHP">
                    <div>
                        <img src="img/hp.png" width="100">
                    </div>
                    <div>
                        <h2>
                            Это ваша главная страница
                        </h2>
                        <span>
                            Чтобы ваша главная страница не была пустой, подпишитесь на каналы. После того, у вас будет видео от тех пользователей, на которых вы подписались.
                        </span><br><br>
                        <button class="mt-uix-button mt-button-primary" onclick="$('.welcomeHP').fadeOut(300)">Понятно</button>
                    </div>
                </div>
                <?php
                $hpv = mysqli_query($mysql, "SELECT * FROM videos LEFT JOIN users ON userID = videoBy LEFT JOIN subscriptions ON videoBy = subscribeTo WHERE subscribeBy = $userID AND ban = '' ORDER BY videoID DESC LIMIT 15");
                if(mysqli_num_rows($hpv) == 0){
                    echo '<center id="gt" class="fn">
                    <img src="img/subscribe.png" width="400">
                    <h1 class="fn">У вас нет подписок...</h1>
                    <h4 class="fn">Зайдите в раздел "Публичные видео" или введите в поиске интересующее видео и подпишитесь на автора!</h4>
                    </center>';
                }else{
                    while($video = mysqli_fetch_assoc($hpv)){
                ?>
                <div class="bubbleVideo">
                    <div class="bubbleAuthor">
                        <a href="/user/<?php echo $video['username'] ?>" class="bubbleChannel">
                            <img src="<?php echo $video['profileImage'] ?>">
                        </a>
                        <span><a href="/user/<?php echo $video['username'] ?>"><b><?php echo $video['username'] ?></b></a> выложил(-а) видео </span>
                        <img src="img/p.png" data-open="#dropdown_video<?php echo $video['videoID'] ?>" class="arrow-action-menu">
                        <div class="dropdown hidden" id="dropdown_video<?php echo $video['videoID'] ?>">
                            <div class="dropdown_section global--link" data-link="/watch?v=<?php echo $video['videoGetID'] ?>&flag=true">
                                Пожаловаться
                            </div>
                        </div>
                    </div>
                    <div class="bubbleVideoInfo">
                        <div id="thumbmail-video">
                            <a href="watch?v=<?php echo $video['videoGetID'] ?>"><img src="uploads/<?php echo $video['videoGetID'] ?>.jpg"></a>
                            <span class="video-time"><?php echo $global->updateDuration($video['videoDuration']) ?></span>
                            <button title="Посмотреть позже" class="mt-uix-button mt-button-default add-to-button-video <?php echo $global->watchLaterCheck($userID, $video['videoID']) ?> hidden" data-vid="<?php echo $video['videoID'] ?>">
                                <img src="img/p.png">
                            </button>
                        </div>
                        <div id="bubbleinfo">
                            <a id="bubbletitle" href="watch?v=<?php echo $video['videoGetID'] ?>"><?php echo $video['videoTitle'] ?></a>
                            <div id="infobubbleauthor"><img src="<?php echo $video['profileImage'] ?>" width="18" height="18"> <a href="/user/<?php echo $video['username'] ?>"><?php echo $video['username'] ?></a> <span><?php echo $video['videoViews'] ?> просмотров</span></div>
                            <div id="idk"><?php echo $video['videoDescription'] ?></div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>