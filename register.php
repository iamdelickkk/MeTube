<?php
include 'extra/init.php';
if(isset($_COOKIE['MeTubeUID'])){
    header("Location: /");
}
if(isset($_POST['register'])){
    if(isset($_POST['rule']) && $_POST['rule'] == 1){
        if(!empty($_POST['nickname']) or !empty($_POST['email']) or !empty($_POST['pwd']) or !empty($_POST['pwdRe'])){
            if(ctype_space($_POST['nickname']) or ctype_space($_POST['email']) or ctype_space($_POST['pwd']) or ctype_space($_POST['pwdRe'])){
                $error = 'Не все поля для ввода введены!';
            }else{
                $username = $global->text($_POST['nickname']);
                $email = $_POST['email'];
                $pwd = $_POST['pwd'];
                if($pwd != $_POST['pwdRe']){
                    $error = 'Пароли не совпадают';
                }else{
                    if(strlen($username) > 72){
                        $error = 'Ваш никнейм длинный!';
                    }else if(preg_match("/[^a-zA-Z0-9\!]/", $username)){
                        $error  = "В никнейме допустимо только латинские символы и числа!";
                    }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        $error = 'Неправильная форма почты!';
                    }else if(strlen($pwd) < 6){
                        $error = 'Ваш пароль короткий!';
                    }else{
                        if($global->check('users', 'username', $username) === false){
                            $joined = date('Y-m-d');
                            $pwdHash = password_hash($pwd, PASSWORD_DEFAULT);
                            $create = mysqli_query($mysql, "INSERT INTO users(username, password, email, joined, profileImage, profileBanner) VALUES('$username', '$pwdHash', '$email', '$joined', 'img/default_avatar.png', 'img/default_banner.png')");
                            $user = $global->userData(2, $username);
                            setcookie('MeTubeUID', $user['userID'], time()+7000000);
                            setcookie('MeTubeUPassword', $user['password'], time()+7000000);
                            header("Location: /");
                        }else if($global->check('users', 'email', $email) === true){
                            $error = 'Такая почта уже используется!';
                        }else{
                            $error = 'Такой никнейм уже используется!';
                        }
                    }
                }
            }
        }else{
            $error = 'Не все поля для ввода введены!';
        }
    }else{
        $error = 'Согласитесь с правилами!';
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
                    <form method="post" class="mt--form-login">
                        <div>
                            <h2>
                                Зарегистроваться
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
                            <b>Придумайте никнейм</b>
                        </div>
                        <div style="margin: 0 0 1.5em;">
                            <input type="text" class="input" name="nickname">
                        </div>
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
                        <div class="b">
                            <b>Подтвердите пароль</b>
                        </div>
                        <div style="margin: 0 0 1.5em;">
                            <input type="password" class="input" name="pwdRe">
                        </div>
                        <div style="margin: 0 0 1.5em;">
                            <input type="checkbox" id="yes" class="checkbox" name="rule" value="1">
                            <label for="yes">
                                Я прочитал(-а) <a href="/rules">правила</a> и полностью с ними согласен(-а)
                            </label>
                        </div>
                        <div class="mt-display-flex mt-align-center">
                            <button class="mt-uix-button mt-button-primary" name="register" type="submit">Создать</button>
                            <div>
                                <a href="/login">
                                    Войти в аккаунт
                                </a>
                            </div>
                        </div><br>
                    </form>
                </div>
            </div>
        </div>
        <?php include $_SERVER['DOCUMENT_ROOT'].'/extra/ui/footer.php'; ?>
    </div>
</body>
</html>