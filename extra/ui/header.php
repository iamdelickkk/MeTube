
<div class="mt-masthead-container">
            <div class="mt-masthead">
                <a title="MeTube" href="<?php echo $link ?>" id="logotype">
                    <img src="<?php echo $link ?>img/p.png">
                </a>
                <form method="get" action="<?php echo $link ?>results">
                    <input type="search" name="query" id="search_input" <?php if(isset($_GET['query'])){ echo 'value="'.htmlspecialchars($_GET['query']).'"'; } ?> required>
                    <button class="mt-uix-button mt-button-default" id="search_button">
                        <img src="<?php echo $link ?>img/p.png">
                    </button>
                </form>
                <div class="mt-display-flex" style="position:relative">
                    <button style="margin-left: 25px;" class="mt-uix-button mt-button-default mt-button-h-upload global--link" data-link="<?php echo $link ?>my_videos_upload">Добавить видео</button>
                    <?php
                    if(isset($_COOKIE['MeTubeUID']) && $_COOKIE['MeTubeUID'] != 0){
                    ?>
                    <button class="mt-uix-button mt-button-default mt-button-more" data-open="#headdropdown1"><img class="mt-uix-button-arrow" src="<?php echo $link ?>img/p.png" alt="" title=""></button>
                    <div class="dropdown hidden" id="headdropdown1">
                        <div class="dropdown_section global--link" data-link="<?php echo $link ?>my_videos">
                            <img src="<?php echo $link ?>img/p.png" class="upload-menu-vm">
                            Видео менеджер
                        </div>

                    </div>
                <?php } ?>
                </div>
                <?php
                if(isset($_COOKIE['MeTubeUID']) && $_COOKIE['MeTubeUID'] != 0){
                ?>
                <div class="mt-display-flex mt-margin-left mt-align-center mt-cursor-pointer">
                    <div id="oVaS">
                        <span style="color:#999;margin-right: 5px;"><?php echo $user['username'] ?></span>
                    </div>
                    <button class="mt-uix-button mt-button-default notification-button global--link" data-link="<?php echo $link ?>notifications">
                        <?php echo $global->newNotificationsCount($_COOKIE['MeTubeUID']); ?>
                    </button>
                    <div id="oVaS">
                        <img src="<?php echo $link.$user['profileImage'] ?>" class="pfp_head">
                    </div>
                </div>
                <?php
                }else{
                ?>
                <button style="margin-left: auto;" class="mt-uix-button mt-button-primary global--link" data-link="<?php echo $link ?>login">Войти</button>
                <?php } ?>
            </div>
        </div>
<?php
if(isset($_COOKIE['MeTubeUID']) && $_COOKIE['MeTubeUID'] != 0){
?>
<div class="mt-masthead-upr hidden">
    <div class="mt-mh-pl-all">
        <div class="mt-mh-pl">
            <div>
                <a href="<?php echo $link ?>watch_later"><img src="<?php echo $link ?>img/whs.png" width="129" height="74"></a>
            </div>
            <div>
                <a id="idk" href="<?php echo $link ?>watch_later">
                    <h3 style="margin:0;">
                        Посмотреть позже
                    </h3>
                </a>
                <span style="color:#999;">
                    <?php echo $global->count('int', 'watch_later', 'wlTo', $userID) ?> видео
                </span><br>
                <a id="idk" href="<?php echo $link ?>watch_later">
                    Посмотреть лист
                </a>
            </div>
        </div>
        <div class="mt-mh-pl">
            <div>
                <a href="<?php echo $link ?>my_favorites"><img src="<?php echo $link ?>img/favs.png" width="129" height="74"></a>
            </div>
            <div>
                <a id="idk" href="<?php echo $link ?>my_favorites">
                    <h3 style="margin:0;">
                        Избранное
                    </h3>
                </a>
                <span style="color:#999;">
                    <?php echo $global->count('int', 'favorites', 'favoriteTo', $userID) ?> видео
                </span><br>
                <a id="idk" href="<?php echo $link ?>my_favorites">
                    Посмотреть лист
                </a>
            </div>
        </div>
        <?php
        $aplmh = mysqli_query($mysql, "SELECT * FROM playlists WHERE playlistBy = $userID ORDER BY playlistID DESC LIMIT 15");
        if(mysqli_num_rows($aplmh) == 0){
            echo '<h3 class="mt-pl-alert"><img src="'.$link.'img/plas.png" width="300">Чтобы это место не было таким пустым, создайте плейлист</h3>';
        }
        while($playlistH = mysqli_fetch_assoc($aplmh)){
        ?>
        <div class="mt-mh-pl">
            <div>
                <a href="<?php echo $link ?>playlist?list=<?php echo $playlistH['playlistGetID'] ?>"><img src="<?php echo $link ?>uploads/<?php echo $playlistH['playlistImage'] ?>.jpg" width="129" height="74"></a>
            </div>
            <div>
                <a id="idk" href="<?php echo $link ?>playlist?list=<?php echo $playlistH['playlistGetID'] ?>">
                    <h3 style="margin:0;">
                        <?php echo $playlistH['playlistTitle'] ?>
                    </h3>
                </a>
                <span style="color:#999;">
                    <?php echo $global->count('int', 'playlist_videos', 'pvTo', $playlistH['playlistID']) ?> видео
                </span><br>
                <a id="idk" href="<?php echo $link ?>playlist?list=<?php echo $playlistH['playlistGetID'] ?>">
                    Посмотреть лист
                </a>
            </div>
        </div>
    <?php } ?>
    </div>
    <div class="mt-margin-left">
        <div>
            <h4 style="color:#666;">MeTube</h4>
        </div>
        <div>
            <a href="<?php echo $link ?>user/<?php echo $user['username'] ?>">Мой канал</a>
        </div>
        <div>
            <a href="<?php echo $link ?>my_videos">Видео менеджер</a>
        </div>
        <div>
            <a href="<?php echo $link ?>?GetFrom=HeaderManager">Подписки</a>
        </div>
        <div>
            <a href="<?php echo $link ?>settings">Настройки</a>
        </div>
        <div>
            <a href="<?php echo $link ?>?logout=true">Выйти</a>
        </div>
    </div>
</div>
<?php } ?>