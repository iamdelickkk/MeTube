<?php
include 'extra/init.php';
if(isset($_COOKIE['MeTubeUID'])){
    $userID = $_COOKIE['MeTubeUID'];
    $user = $global->userData(1, $userID);
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
                        <div class="guide-item global--link" data-link="/browse_channels">
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
                <div class="channel-actions">
                    <div class="channel-action channel-action-active" style="margin:0;">
                        Все каналы
                    </div>
                </div>
                <div style="margin: 20px 39px;">
                    <div class="category-header">
                        MeTube
                    </div>
                    <div style="overflow:hidden;">
                        <div class="browse_channel">
                            <div>
                                <a href="/user/metube">
                                    <img src="img/metube.png">
                                </a>
                            </div>
                            <div>
                                <a href="/user/metube">metube</a>
                            </div>
                            <div>
                                <button type="button" class="mt-uix-button mt-button-default global--link" data-link="/user/metube">Подписаться</button>
                            </div>
                        </div>
                        <div class="browse_channel">
                            <div>
                                <a href="/user/music">
                                    <img src="img/music.jpg">
                                </a>
                            </div>
                            <div>
                                <a href="/user/music">Music</a>
                            </div>
                            <div>
                                <button type="button" class="mt-uix-button mt-button-default global--link" data-link="/user/music">Подписаться</button>
                            </div>
                        </div>
                        <div class="browse_channel">
                            <div>
                                <a href="/user/sports">
                                    <img src="img/sports.jpg">
                                </a>
                            </div>
                            <div>
                                <a href="/user/sports">Sports</a>
                            </div>
                            <div>
                                <button type="button" class="mt-uix-button mt-button-default global--link" data-link="/user/sports">Подписаться</button>
                            </div>
                        </div>
                        <div class="browse_channel">
                            <div>
                                <a href="/user/gaming">
                                    <img src="img/gaming.jpg">
                                </a>
                            </div>
                            <div>
                                <a href="/user/gaming">Gaming</a>
                            </div>
                            <div>
                                <button type="button" class="mt-uix-button mt-button-default global--link" data-link="/user/gaming">Подписаться</button>
                            </div>
                        </div>
                        
                    </div>
                    <div class="category-header">
                        Популярные
                    </div>
                    <div style="overflow:hidden;">
                        <?php
                        $pop = mysqli_query($mysql, "SELECT * FROM users WHERE ban = '' ORDER BY views DESC LIMIT 4");
                        if(mysqli_num_rows($pop) == 0){
                            echo '<center>Ничего не найдено );</center>';
                        }
                        while($user = mysqli_fetch_assoc($pop)){
                        ?>
                        <div class="browse_channel">
                            <div>
                                <a href="/user/<?php echo $user['username'] ?>">
                                    <img src="<?php echo $user['profileImage'] ?>">
                                </a>
                            </div>
                            <div>
                                <a href="/user/<?php echo $user['username'] ?>"><?php echo $user['username'] ?></a>
                            </div>
                            <div>
                                <button type="button" class="mt-uix-button mt-button-default global--link" data-link="/user/<?php echo $user['username'] ?>">Подписаться</button>
                            </div>
                        </div>
                    <?php } ?>
                        
                    </div>
                    <div class="category-header">
                        Новенькие
                    </div>
                    <div style="overflow:hidden;">
                        <?php
                        $new = mysqli_query($mysql, "SELECT * FROM users WHERE ban = '' ORDER BY userID DESC LIMIT 4");
                        if(mysqli_num_rows($new) == 0){
                            echo '<center>Ничего не найдено );</center>';
                        }
                        while($user = mysqli_fetch_assoc($new)){
                        ?>
                        <div class="browse_channel">
                            <div>
                                <a href="/user/<?php echo $user['username'] ?>">
                                    <img src="<?php echo $user['profileImage'] ?>">
                                </a>
                            </div>
                            <div>
                                <a href="/user/<?php echo $user['username'] ?>"><?php echo $user['username'] ?></a>
                            </div>
                            <div>
                                <button type="button" class="mt-uix-button mt-button-default global--link" data-link="/user/<?php echo $user['username'] ?>">Подписаться</button>
                            </div>
                        </div>
                    <?php } ?>
                        
                    </div>
                    <div class="category-header">
                        Подтверждённые
                    </div>
                    <div style="overflow:hidden;">
                        <?php
                        $ver = mysqli_query($mysql, "SELECT * FROM users WHERE ban = '' AND verifed = 1 ORDER BY userID DESC LIMIT 4");
                        if(mysqli_num_rows($ver) == 0){
                            echo '<center>Ничего не найдено );</center>';
                        }
                        while($user = mysqli_fetch_assoc($ver)){
                        ?>
                        <div class="browse_channel">
                            <div>
                                <a href="/user/<?php echo $user['username'] ?>">
                                    <img src="<?php echo $user['profileImage'] ?>">
                                </a>
                            </div>
                            <div>
                                <a href="/user/<?php echo $user['username'] ?>"><?php echo $user['username'] ?></a>
                            </div>
                            <div>
                                <button type="button" class="mt-uix-button mt-button-default global--link" data-link="/user/<?php echo $user['username'] ?>">Подписаться</button>
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