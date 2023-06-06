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
if(!empty($user['ban'])){
    include $_SERVER['DOCUMENT_ROOT'].'/banned.php';
}
if($user['admin'] != 1){
    header("Location: /");
}
if(isset($_GET['delvideo'])){
    $videoGID = $_GET['delvideo'];
    $dv = mysqli_query($mysql, "DELETE FROM videos WHERE videoGetID = '$videoGID'");
    header("Location: /admin");
}
if(isset($_GET['comment'])){
    $commentID = $_GET['comment'];
    $dv = mysqli_query($mysql, "DELETE FROM comments WHERE commentID = $commentID");
    header("Location: /admin");
}
if(isset($_POST['banf'])){
    $user = $_GET['ban'];
    $r = $_POST['reason'];
    $del = mysqli_query($mysql, "UPDATE users SET ban = '$r' WHERE userID = $user");
    header("Location: /admin");
}
if(isset($_POST['unban'])){
    $user = $_GET['ban'];
    $del = mysqli_query($mysql, "UPDATE users SET ban = '' WHERE userID = $user");
    header("Location: /admin");
}
if(isset($_GET['ban'])){
    $userBan = $global->userData(1, $_GET['ban']);
    if($userBan['userID'] == $userID){
        die("зачем");
    }
    if(empty($userBan['ban'])){
        die('<form action="/admin?ban='.$_GET['ban'].'" method="post">
            <input type="text" name="reason" placeholder="Причина">
            <input type="submit" name="banf">
            </form>');
    }else{
        die('<form action="/admin?ban='.$_GET['ban'].'" method="post">
            Хотите разбанить?
            <input type="submit" name="unban" value="Да">
            <button type="button" onclick="location.href = `/admin`">Нет</button>
            </form>');
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
    <script src="js/jquery.form.js"></script> 
    <script src="js/main.js"></script>
    <style>
        h1{
            font-weight: normal;
        }
        #container{
            display: block;
        }
        td{
            max-width: 200px;
            width:200px;
            min-width:200px;
            word-break:break-word;
        }
    </style>
</head>
<body>
    <!-- metubee.xyz -->
    <div id="app">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/header.php'; ?>
        <div id="alerts">
            
        </div>
        <div id="container">
            <h1>
                Админ панель
            </h1>
            <h3>
                Видео
            </h3>
            <table border="1">
                <tr>
                    <td>
                        Изображение
                    </td>
                    <td>
                        Название
                    </td>
                    <td>
                        URL
                    </td>
                    <td>
                        Удалить
                    </td>
                </tr>
                <?php
                $qv = mysqli_query($mysql, "SELECT * FROM videos ORDER BY videoID DESC");
                while($video = mysqli_fetch_assoc($qv)){
                    echo '<tr>
                    <td>
                        <img src="uploads/'.$video['videoGetID'].'.jpg" width="120">
                    </td>
                    <td>
                        '.$video['videoTitle'].'
                    </td>
                    <td>
                        <a href="'.$link.'watch?v='.$video['videoGetID'].'">'.$link.'watch?v='.$video['videoGetID'].'</a>
                    </td>
                    <td>
                        <a href="/admin?delvideo='.$video['videoGetID'].'">Удалить</a>
                    </td>
                </tr>';
                }
                ?>
            </table>
            <h3>
                Каналы
            </h3>
            <table border="1">
                <tr>
                    <td>
                        Изображение
                    </td>
                    <td>
                        Никнейм
                    </td>
                    <td>
                        URL
                    </td>
                    <td>
                        Забанить
                    </td>
                </tr>
                <?php
                $qv = mysqli_query($mysql, "SELECT * FROM users ORDER BY userID DESC");
                while($user = mysqli_fetch_assoc($qv)){
                    echo '<tr>
                    <td>
                        <img src="'.$user['profileImage'].'" width="120">
                    </td>
                    <td>
                        '.$user['username'].'
                    </td>
                    <td>
                        <a href="'.$link.'user/'.$user['username'].'">'.$link.'user/'.$user['username'].'</a>
                    </td>
                    <td>
                        <a href="/admin?ban='.$user['userID'].'">Забанить</a>
                    </td>
                </tr>';
                }
                ?>
            </table>
            <h3>
                Комментарии
            </h3>
            <table border="1">
                <tr>
                    <td>
                        Текст
                    </td>
                    <td>
                        Написано
                    </td>
                    <td>
                        Видео
                    </td>
                    <td>
                        Удалить
                    </td>
                </tr>
                <?php
                $qv = mysqli_query($mysql, "SELECT * FROM comments LEFT JOIN users ON userID = commentBy LEFT JOIN videos ON videoID = commentVideo ORDER BY videoID DESC");
                while($comment = mysqli_fetch_assoc($qv)){
                    echo '<tr>
                    <td>
                        '.$comment['commentContent'].'
                    </td>
                    <td>
                        '.$comment['username'].'
                    </td>
                    <td>
                        <a href="'.$link.'watch?v='.$comment['videoGetID'].'">'.$link.'watch?v='.$comment['videoGetID'].'</a>
                    </td>
                    <td>
                        <a href="/admin?comment='.$comment['commentID'].'">Удалить</a>
                    </td>
                </tr>';
                }
                ?>
            </table>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>