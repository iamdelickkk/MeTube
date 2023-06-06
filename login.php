<?php
include 'extra/init.php';
if(isset($_COOKIE['MeTubeUID'])){
    header("Location: /");
}
if(!isset($_GET['return'])){
    $_GET['return'] = '/';
}
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['pwd'];
    if(!empty($email) or !empty($password)){
        if($global->check('users', 'email', $email) === false){
            $error = 'Такого пользователя не существует!';
        }else{
            $user = $global->userData(3, $email);
            if(password_verify($password, $user['password'])){
                setcookie('MeTubeUID', $user['userID'], time()+7000000);
                setcookie('MeTubeUPassword', $user['password'], time()+7000000);
                header("Location: ".$_GET['return']);
            }else{
                $error = 'Неправильный пароль!';
            }
        }
    }else{
        $error = 'Не все поля для ввода введены!';
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
</head>
<body>
    <!-- metubee.xyz -->
    <div id="app">
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/header.php'; ?>
        <?php
        if(isset($_GET['return']) && $_GET['return'] != '/'){
            echo '<div class="alert alert-error">
                            <div>
                                <img src="img/p.png">
                            </div>
                            <div class="alert-message">
                                Вам нужно войти в аккаунт чтобы выполнить данное действие
                            </div>
                        </div>';
        }
        ?>
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
                        <div class="guide-promo">
                            <p>
                                Войдите, чтобы добавить каналы и просмотреть интересные рекомендации.
                            </p>
                            <button style="margin-left: auto;" class="mt-uix-button mt-button-primary global--link" data-link="/ServiceLogin">Войти ›</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="login">
                <div>
                    <div class="mt-feature">
                        <div>
                            <img src="img/ytfav.png">
                        </div>
                        <div>
                            <div id="feature-title">
                                Следите за любимыми каналами
                            </div>
                            <div>
                                Сохраняйте видео, чтобы посмотреть их позже, смотрите рекомендации специально для вас или подпишитесь, чтобы получать обновления с ваших любимых каналов.
                            </div>
                        </div>
                    </div>
                    <div class="mt-feature">
                        <div>
                            <img src="img/ytonzgo.png">
                        </div>
                        <div>
                            <div id="feature-title">
                                Смотрите везде
                            </div>
                            <div>
                                Куда бы вы ни отправились, берите с собой любимые передачи — смотрите на смартфоне, планшете или ПК.
                            </div>
                        </div>
                    </div>
                    <div class="mt-feature">
                        <div>
                            <img src="img/ytwfrnz.png">
                        </div>
                        <div>
                            <div id="feature-title">
                                Поделитесь с друзьями
                            </div>
                            <div>
                                Смотрите видео, которыми поделились ваши друзья во всех ваших социальных сетях — все в одном месте.
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-left:10%;">
                    <form method="POST" class="mt--form-login">
                        <div>
                            <h2>
                                Войти
                            </h2>
                        </div>
                        <?php
                        if(isset($error)){
                        ?>
                        <div class="alert alert-error">
                            <div>
                                <img src="img/p.png">
                            </div>
                            <div class="alert-message">
                                <?php echo $error; ?>  
                            </div>
                        </div><br>
                        <?php } ?>
                        <div class="b">
                            <b>Email</b>
                        </div>
                        <div style="margin: 0 0 1.5em;">
                            <input type="text" class="input" name="email">
                        </div>
                        <div class="b">
                            <b>Пароль</b>
                        </div>
                        <div style="margin: 0 0 1.5em;">
                            <input type="password" class="input" name="pwd">
                        </div>
                        <div class="mt-display-flex mt-align-center">
                            <button class="mt-uix-button mt-button-primary" name="login" type="submit">Войти</button>
                            <div>
                                <a href="/register">
                                    Зарегистроваться
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>