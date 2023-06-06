<?php
include 'extra/init.php';
$userID = $_COOKIE['MeTubeUID'];
$user = $global->userData(1, $userID);
if(!empty($user['ban'])){
    include $_SERVER['DOCUMENT_ROOT'].'/banned.php';
}
if(!isset($_COOKIE['MeTubeUID'])){
    header("Location: /login?return=".$_SERVER['REQUEST_URI']);
}
if($global->check('playlists', 'playlistGetID', $_GET['list']) === true){
	$playlist = $global->getPlaylist($_GET['list']);
	$playlistID = $playlist['playlistID'];
    if(!empty($playlist['ban'])){
        header("Location: /user/".$playlist['username']);
    }
	if($playlist['playlistBy'] == $userID && !isset($_GET['action_edit'])){
		header("Location: /playlist?action_edit=1&list=".$_GET['list']);
	}
	if(isset($_GET['action_edit']) && $_GET['action_edit'] == 1){
		if($playlist['playlistBy'] != $userID){
			header("Location: /playlist?list=".$_GET['list']);
		}
	}
	if(isset($_GET['action_edit']) && $_GET['action_edit'] != 1){
		header("Location: /playlist?action_edit=1&list=".$_GET['list']);
	}
	if(isset($_POST['dlte'])){
	    foreach($_POST['dlte'] as $id){
	        $del = mysqli_query($mysql, "DELETE FROM playlist_videos WHERE pvID = $id");
	    }
	    header("Location: ".$_SERVER['REQUEST_URI']);
	}
	if(isset($_POST['add'])){
		$videoGetID = $global->getMetubeID($_POST['url']);
		$video = $global->videoData($videoGetID);
		$videoID = $video['videoID'];
        if($global->check('playlists', 'playlistID', $playlistID) === true){
            $ch = mysqli_query($mysql, "SELECT * FROM playlist_videos WHERE pvVideo = $videoID AND pvTo = $playlistID");
            if(mysqli_num_rows($ch) == 0){
                if($playlist['playlistBy'] == $userID){
                    $a = mysqli_query($mysql, "INSERT INTO playlist_videos(pvTo, pvVideo) VALUES($playlistID, $videoID)");
                    $up = mysqli_query($mysql, "UPDATE playlists SET playlistImage = '$videoGetID' WHERE playlistID = $playlistID");
                    header("Location: ".$_SERVER['REQUEST_URI']);
                }else{
                    $error = 'undefined';
                }
            }else{
                $error = 'Такое видео и так есть в плейлисте!';
            }
        }else{
            $error = 'undefined';
        }
	}
}else{
	header("Location: /404");
}
if(isset($_POST['done'])){
	$title = $global->text($_POST['title']);
	$description = $global->text($_POST['description']);
	if(!empty($title) && !empty($description)){
		if(!ctype_space($title) && !ctype_space($description)){
			if(strlen($title) <= 72){
				$u = mysqli_query($mysql, "UPDATE playlists SET playlistTitle = '$title', playlistDescription = '$description' WHERE playlistID = $playlistID");
				header("Location: /playlist?list=".$_GET['list']);
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
                        <div class="guide-item-non-select global--link" data-link="/browse_channels">
                            <img src="img/browse_channels.png">
                            <span>
                                Просмотреть каналы
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <form method="POST" id="vid_manager">
                <div>
                    <div class="playlist_s">
                        <h2 id="vm_p">
                        	<?php echo $playlist['playlistTitle'] ?>
                        </h2>
                
                        <?php
                        if(isset($_GET['action_edit'])){
                        ?>
                        <div class="mt-margin-left">
                        	<button type="button" class="mt-uix-button mt-button-default global--link" data-link="/view_all_playlists">
                        		Отменить
                        	</button>
                        	<button type="submit" name="done" class="mt-uix-button mt-button-primary">
                        		Готово
                        	</button>
                        </div>
                    	<?php } ?>
                    </div>
                    <?php
                        if(!isset($_GET['action_edit'])){
                        ?>
                        <h4 class="grP">
                        	<?php echo nl2br($playlist['playlistDescription']) ?>
                        </h4>
                    	<?php } ?>
                </div>
                <?php
                if(isset($_GET['action_edit'])){
                ?>
                <div class="channel-actions">
                    <div class="channel-action channel-action-active" style="margin:0;">
                        Основная информация
                    </div>
                </div>
                <div class="p10">
                	<div class="mt-display-flex">
	                	<div class="vFpL">
	                		<div>
	                			<input type="text" name="title" placeholder="Название плейлиста" class="input" value="<?php echo $playlist['playlistTitle'] ?>">
	                		</div>
	                		<div>
	                			<textarea name="description" placeholder="Описание плейлиста" class="input"><?php echo $playlist['playlistDescription'] ?></textarea>
	                		</div>
	                	</div>
                	</div>
                </div>
                <div class="dashboard_s">
                        <div id="rm_rl">
                            <input type="checkbox" class="checkbox" id="alld">
                        </div>
                        <input class="mt-uix-button mt-button-default" type="submit" name="delete_smth" value="Удалить">
                        <button class="mt-uix-button mt-button-default mt-margin-left addtopl" type="button">Добавить видео по URL</button>
                </div>
            <?php } ?>
                <?php
                if($global->check('playlist_videos', 'pvTo', $playlistID) === false){
                	echo '<div class="alert alert-warn">
                            <div>
                                <img src="img/p.png">
                            </div>
                            <div class="alert-message">
                                В этом плейлисте нет видео
                            </div>
                        </div>';
                }
                ?>
                <br>
                <?php
                    $ps = mysqli_query($mysql, "SELECT * FROM playlist_videos LEFT JOIN videos ON videoID = pvVideo LEFT JOIN users ON userID = videoBy WHERE pvTo = $playlistID AND ban = '' ORDER BY pvID DESC LIMIT 15");
                    if(mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM playlist_videos LEFT JOIN videos ON videoID = pvVideo LEFT JOIN users ON userID = videoBy WHERE pvTo = $playlistID AND ban != ''")) != 0){
                        echo '<span style="color:#666;margin:10px;">Есть скрытые видео</span><br><br>';
                    }
                    while($video = mysqli_fetch_assoc($ps)){
                    ?>
                    <div class="related-video">
                        <div id="rm_rl">
                        	<?php
                        	if($playlist['playlistBy'] == $userID){
                        	?>
                        	<input type="checkbox" class="checkbox" id="dltevid" name="dlte[]" value="<?php echo $video['pvID'] ?>">
                       		<?php } ?>
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
                                <a id="mt-data-username" href="/user/<?php echo $video['username'] ?>">
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
            </form>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>