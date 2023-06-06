<?php
include 'extra/init.php';
if(!isset($_COOKIE['MeTubeUID'])){
    header("Location: /login?return=".$_SERVER['REQUEST_URI']);
}
if(!isset($_POST['sort'])){
    $_POST['sort'] = 'DESC';
}
$userID = $_COOKIE['MeTubeUID'];
$user = $global->userData(1, $userID);
if(!empty($user['ban'])){
    include $_SERVER['DOCUMENT_ROOT'].'/banned.php';
}
if(isset($_POST['dlte'])){
    foreach($_POST['dlte'] as $id){
        $del = mysqli_query($mysql, "DELETE FROM likes WHERE likeID = $id");
    }
    header("Location: /my_videos_likes");
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
    <style>#homepage{width:700px;border-left: 1px solid #e2e2e2;}</style>
</head>
<body>
    <!-- metubee.xyz -->
    <div id="popups">

    </div>
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
                        <div class="guide-item-non-select">
                            <span>
                                <?php echo $user['username'] ?>
                            </span>
                        </div>
                        <div class="guide-section-separator"></div>
                        <h3>аккаунт</h3>
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
                        <div class="guide-section-separator"></div>
                        <h3>менеджер видео</h3>
                        <div class="guide-item-non-select global--link" data-link="/my_videos">
                            <span>
                                Мои видео
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/view_all_playlists">
                            <span>
                                Плейлисты
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/my_videos_history">
                            <span>
                                История просмотра
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/watch_later">
                            <span>
                                Посмотреть позже
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/my_favorites">
                            <span>
                                Избранное
                            </span>
                        </div>
                        <div class="guide-item global--link" data-link="/my_videos_likes">
                            <span>
                                Понравилось
                            </span>
                        </div>
                        <div class="guide-section-separator"></div>
                        <h3>канал</h3>
                        <div class="guide-item-non-select global--link" data-link="/copyright?o=me">
                            <span>
                                Авторские права
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/analytics">
                            <span>
                                Аналитика
                            </span>
                        </div>
                        <div class="guide-item-non-select global--link" data-link="/personalize">
                            <span>
                                Настройки
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <form method="POST" action="/my_videos_likes" id="vid_manager">
                <div class="dashboard_s">
                    <h2 id="vm_h">
                        Понравилось
                        <div id="vm_c">
                            <?php
                                echo $global->count('int', 'likes', 'likeFrom', $userID);
                            ?>
                        </div>
                    </h2>
                </div>
                <div>
                    <div class="dashboard_s">
                        <div id="rm_rl">
                            <input type="checkbox" class="checkbox" id="alld">
                        </div>
                        <input class="mt-uix-button mt-button-default" type="submit" name="delete_smth" value="Удалить">
                        <select name="sort" class="mt-uix-button mt-button-default mt-margin-left" onchange="this.form.submit()">
                            <option value="DESC" <?php if($_POST['sort'] == 'DESC'){ echo 'selected'; } ?>>Сортировать: от новых к старым</option>
                            <option value="ASC" <?php if($_POST['sort'] == 'ASC'){ echo 'selected'; } ?>>Сортировать: от старых к новым</option>
                        </select>
                    </div><br>
                    <?php
                    $sort = $_POST['sort'];
                    $wh = mysqli_query($mysql, "SELECT * FROM likes LEFT JOIN videos ON videoID = likeVid LEFT JOIN users ON userID = videoBy WHERE likeFrom = $userID AND ban = '' ORDER BY likeID $sort");
                    if(mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM likes LEFT JOIN videos ON videoID = likeVid LEFT JOIN users ON userID = videoBy WHERE likeFrom = $userID AND ban != ''")) != 0){
                        echo '<span style="color:#666;margin:10px;">Есть скрытые видео</span><br><br>';
                    }
                    while($video = mysqli_fetch_assoc($wh)){
                    ?>
                    <div class="related-video">
                        <div id="rm_rl">
                            <input type="checkbox" class="checkbox" id="dltevid" name="dlte[]" value="<?php echo $video['likeID'] ?>">
                        </div>
                        <div id="thumbmail-video">
                            <a href="watch?v=<?php echo $video['videoGetID'] ?>"><img src="uploads/<?php echo $video['videoGetID'] ?>.jpg" width="129" height="74"></a>
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
                                <a id="mt-data-username" href="/user/<?php echo $video['username'] ?>   ">
                                    <?php echo $video['username'] ?>                            </a><br>
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
            </form>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>