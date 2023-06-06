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
if(!isset($_POST['sort'])){
    $_POST['sort'] = 'DESC';
}
if(isset($_POST['dlte'])){
    foreach($_POST['dlte'] as $id){
        $playlist = mysqli_fetch_assoc(mysqli_query($mysql, "SELECT * FROM playlists WHERE playlistID = $id"));
        $playlistGetID = $playlist['playlistGetID'];
        $del = mysqli_query($mysql, "DELETE FROM playlists WHERE playlistID = $id");
        $delvids = mysqli_query($mysql, "DELETE FROM playlist_videos WHERE pvTo = $id");
        $delch = mysqli_query($mysql, "DELETE FROM channel_playlist WHERE chPlaylist = '$playlistGetID'");
    }
    header("Location: /view_all_playlists");
}
if(isset($_POST['add'])){
    if(!empty($_POST['title']) && !empty($_POST['description'])){
        if(ctype_space($_POST['title']) or ctype_space($_POST['description'])){
            die("<script>alert('Введите название и описание!');location.href = '/view_all_playlists';</script>");
        }else if(strlen($_POST['title']) > 72){
            die("<script>alert('Длинное название плейлиста!');location.href = '/view_all_playlists';</script>");
        }else if(strlen($_POST['title']) < 0){
            die("<script>alert('Введите название и описание!');location.href = '/view_all_playlists';</script>");
        }else if(strlen($_POST['description']) < 0){
            die("<script>alert('Введите название и описание!');location.href = '/view_all_playlists';</script>");
        }else{
            $title = $global->text($_POST['title']);
            $description = $global->text($_POST['description']);
            $getID = $global->randText(30);
            $cp = mysqli_query($mysql, "INSERT INTO playlists(playlistTitle, playlistDescription, playlistBy, playlistImage, playlistGetID) VALUES('$title', '$description', $userID, 'default', '$getID')");
            header("Location: /view_all_playlists");
        }
    }else{
        die("<script>alert('Введите название и описание!');location.href = '/view_all_playlists';</script>");
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
                        <div class="guide-item global--link" data-link="/view_all_playlists">
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
                        <div class="guide-item-non-select global--link" data-link="/my_videos_likes">
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
            <form method="POST" id="vid_manager">
                <div class="dashboard_s">
                    <h2 id="vm_h">
                        Плейлисты
                        <div id="vm_c">
                            <?php
                                echo $global->count('int', 'playlists', 'playlistBy', $userID);
                            ?>
                        </div>
                    </h2>
                    <button class="mt-uix-button mt-button-default mt-margin-left add-pla-vid" type="button">Добавить</button>
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
                    $vp = mysqli_query($mysql, "SELECT * FROM playlists WHERE playlistBy = $userID ORDER BY playlistID $sort");
                    while($playlist = mysqli_fetch_assoc($vp)){
                    ?>
                    <div class="related-video">
                        <div id="rm_rl">
                            <input type="checkbox" class="checkbox" id="dltevid" name="dlte[]" value="<?php echo $playlist['playlistID'] ?>">
                        </div>
                        <div id="thumbmail-video">
                            <a href="playlist?list=<?php echo $playlist['playlistGetID'] ?>"><img src="uploads/<?php echo $playlist['playlistImage'] ?>.jpg" width="129" height="74"></a>
                            
                        </div>
                        <div style="padding-left: 9px;">
                            <a id="title" style="margin:0!important;" href="/playlist?list=<?php echo $playlist['playlistGetID'] ?>">
                                <?php echo $playlist['playlistTitle'] ?>
                            </a>
                            <div id="metadata"><?php echo $playlist['playlistDescription'] ?></div>
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