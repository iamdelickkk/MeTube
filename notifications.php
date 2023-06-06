<?php
include 'extra/init.php';
if(!isset($_COOKIE['MeTubeUID'])){
    header("Location: /login?return=".$_SERVER['REQUEST_URI']);
}
if(isset($_GET['limit'])){
    $limit = $_GET['limit'];
}else{
    $limit = 15;
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
    <script src="js/jquery.form.js"></script> 
    <script src="js/main.js"></script>
    <script>
        setInterval(function(){
            if($('#file_name').html() == '"err"'){
                $("#alerts").html(`<div class="alert alert-error">
                        <div>
                            <img src="img/p.png">
                        </div>
                        <div class="alert-message">
                            Произошла ошибка при добавлении видео. Попробуйте позже. (Error Code - #f3293U)
                        </div>
                    </div><br>`);
                $('#container').remove();
            }
            if($('.progress_bar_percent').html() == '100%'){
                $('.button_upload').removeClass('hidden');
            }
        }, 500);
    </script>
</head>
<body>
    <!-- metubee.xyz -->
    <div id="app">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/header.php'; ?>
        <div id="alerts">
            
        </div>
        <div id="container">
            <div id="upload">
                <div class="my_videos_upload_top">
                    Уведомления
                </div>
                <div style="background:#fff">
                    <div>
                        <?php
                        $notifies = mysqli_query($mysql, "SELECT * FROM notifications WHERE notificationTo = $userID ORDER BY notificationID DESC LIMIT $limit");
                        if(mysqli_num_rows($notifies) == 0){
                            echo '<center><h1>Пока что нет уведомлений...</h1></center>';
                        }
                        while($notify = mysqli_fetch_assoc($notifies)){
                            $profile = $global->userData(1, $notify['notificationBy']);
                        ?>
                        <div class="notification<?php if($notify['notificationNew'] == 1){ echo ' notification_new'; } ?>">
                            <?php
                            if($notify['notificationAction'] == 2){
                            ?>
                            <div>
                                <img src="img/new_follower.png" width="30">
                            </div>
                            <div>
                                На вас подписался <a href="/user/<?php echo $profile['username'] ?>"><?php echo $profile['username'] ?></a>
                            </div>
                            <?php
                            }else{
                            ?>
                            <div>
                                <img src="img/new_like.png" width="30">
                            </div>
                            <div>
                                Ваше <a href="<?php echo $notify['notificationActionURL'] ?>">видео</a> понравилось <a href="/user/<?php echo $profile['username'] ?>"><?php echo $profile['username'] ?></a>
                            </div>
                            <?php } ?>
                            <div id="date">
                                <?php echo $global->formatDate(date('F j, Y',strtotime($notify['notificationAdded']))); ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php
                if($global->count('int', 'notifications', 'notificationTo', $userID) > $limit){
                ?>
                <center style="margin:20px;">
                    <button class="mt-uix-button mt-button-default global--link" data-link="/notifications?limit=<?php echo $limit + 15; ?>">
                        Загрузить больше
                    </button>
                </center>
                <?php } ?>
            </div>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>
<?php $updNotifies = mysqli_query($mysql, "UPDATE notifications SET notificationNew = 0 WHERE notificationTo = $userID"); ?>