<?php
include 'extra/init.php';
if(!isset($_COOKIE['MeTubeUID'])){
    header("Location: /login?return=".$_SERVER['REQUEST_URI']);
}
$userID = $_COOKIE['MeTubeUID'];
$user = $global->userData(1, $userID);
if(!empty($user['ban'])){
    include $_SERVER['DOCUMENT_ROOT'].'/banned.php';
}
if(isset($_GET['v']) && !empty($_GET['v'])){
	$videoGetID = $_GET['v'];
	if($global->check('videos', 'videoGetID', $videoGetID) === false){
		header("Location: /?return=".$_SERVER['REQUEST_URI']);
	}else{
		$video = $global->videoData($videoGetID);
		if($video['videoBy'] != $userID){
			header("Location: /?return=".$_SERVER['REQUEST_URI']);
		}
	}
}else{
	header("Location: /?return=".$_SERVER['REQUEST_URI']);
}
if(isset($_POST['save'])){
	if(!empty($_POST['title']) && !ctype_space($_POST['title'])){
		$title = $global->text($_POST['title']);
		$description = $global->text($_POST['description']);
		$tags = $global->text($_POST['tags']);
		$category = $_POST['category'];
		$edit = mysqli_query($mysql, "UPDATE videos SET videoTitle = '$title', videoDescription = '$description', videoTags = '$tags', videoCategory = '$category' WHERE videoGetID = '$videoGetID'");
		header("Location: /watch?v=".$_GET['v']);
	}else{
		header("Location: ".$_SERVER['REQUEST_URI']);
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
    <style>#homepage{width:700px;border-left: 1px solid #e2e2e2;}input{width:100%;}textarea{width:100%;height:200px!important;}video{width: -webkit-fill-available;width: -moz-available;width:fill-available;}</style>
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
                        <div class="guide-item global--link" data-link="/my_videos">
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
                    <h2 id="vm_h" style="width:70%;word-break: break-all;">
                        Редактирование видео: <?php echo $video['videoTitle'] ?>
                    </h2>
                    <div class="mt-margin-left">
                    	<button class="mt-uix-button mt-button-default global--link" data-link="/my_videos" type="button">Отменить</button>
                    	<button class="mt-uix-button mt-button-primary global--link" name="save" type="submit">Сохранить</button>
                    </div>
                </div>
                <div class="mt-display-flex">
                	<div style="margin:10px;width:50%;">
                		<div class="b">
                            <b>Название видео</b>
                        </div>
                        <div style="margin: 0 0 1.5em;">
                            <input type="text" class="input" name="title" value="<?php echo $video['videoTitle'] ?>">
                        </div>
                        <div class="b">
                            <b>Описание видео</b>
                        </div>
                        <div style="margin: 0 0 1.5em;">
                            <textarea name="description" class="input"><?php echo $video['videoDescription'] ?></textarea>
                        </div>
                        <div class="b">
                            <b>Теги видео</b>
                        </div>
                        <div style="margin: 0 0 1.5em;">
                            <input type="text" class="input" name="tags" value="<?php echo $video['videoTags'] ?>">
                        </div>
                        <div class="b">
                            	<b>Категория видео</b>
	                        </div>
	                        <div style="margin: 0 0 1.5em;">
	                            <select name="category" id="category" class="mt-uix-button mt-button-default">
	                                <option value="film_and_animation" <?php if($video['videoCategory'] == 'film_and_animation'){ echo 'selected'; } ?>>Фильмы и анимация</option>
	                                <option value="gaming"<?php if($video['videoCategory'] == 'gaming'){ echo 'selected'; } ?>>Компьютерные игры</option>
	                                <option value="comedy" <?php if($video['videoCategory'] == 'comedy'){ echo 'selected'; } ?>>Комедия</option>
	                                <option value="sports" <?php if($video['videoCategory'] == 'sports'){ echo 'selected'; } ?>>Спорт</option>
	                                <option value="entertainment" <?php if($video['videoCategory'] == 'entertainment'){ echo 'selected'; } ?>>Развлечения</option>
	                                <option value="music" <?php if($video['videoCategory'] == 'music'){ echo 'selected'; } ?>>Музыка</option>

	                            </select>
	                        </div>
                	</div>
                	<div style="margin:10px;width:50%;">
                		<video src="uploads/<?php echo $video['videoGetID'] ?>.m4v" controls></video>
                	</div>
                </div>
            </form>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>