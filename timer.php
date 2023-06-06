
<!doctype html>
<html lang="ru">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>КОГДА ВЫЙДЕТ НОВЫЙ METUBEEEEEEEEEEEEEEEEEEEEEEEEEE</title>
<style>
    *,
    *::before,
    *::after {
    box-sizing: border-box;
    }

    @media (prefers-reduced-motion: no-preference) {
    :root {
        scroll-behavior: smooth;
    }
    }

    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        -webkit-text-size-adjust: 100%;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        overflow: hidden;
    }

    .timer__items {
    display: flex;
    font-size: 48px;
    }

    .timer__item {
    position: relative;
    min-width: 60px;
    margin-left: 10px;
    margin-right: 10px;
    padding-bottom: 15px;
    text-align: center;
    }

    .timer__item::before {
    content: attr(data-title);
    display: block;
    position: absolute;
    left: 50%;
    bottom: 0;
    transform: translateX(-50%);
    font-size: 14px;
    }

    .timer__item:not(:last-child)::after {
    content: ':';
    position: absolute;
    right: -15px;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // конечная дата
    const deadline = new Date(2023, 01, 01);
    // id таймера
    let timerId = null;
    // склонение числительных
    function declensionNum(num, words) {
        return words[(num % 100 > 4 && num % 100 < 20) ? 2 : [2, 0, 1, 1, 1, 2][(num % 10 < 5) ? num % 10 : 5]];
    }
    // вычисляем разницу дат и устанавливаем оставшееся времени в качестве содержимого элементов
    function countdownTimer() {
        const diff = deadline - new Date();
        if (diff <= 0) {
        clearInterval(timerId);
        }
        const days = diff > 0 ? Math.floor(diff / 1000 / 60 / 60 / 24) : 0;
        const hours = diff > 0 ? Math.floor(diff / 1000 / 60 / 60) % 24 : 0;
        const minutes = diff > 0 ? Math.floor(diff / 1000 / 60) % 60 : 0;
        const seconds = diff > 0 ? Math.floor(diff / 1000) % 60 : 0;
        $days.textContent = days < 10 ? '0' + days : days;
        $hours.textContent = hours < 10 ? '0' + hours : hours;
        $minutes.textContent = minutes < 10 ? '0' + minutes : minutes;
        $seconds.textContent = seconds < 10 ? '0' + seconds : seconds;
        $days.dataset.title = declensionNum(days, ['день', 'дня', 'дней']);
        $hours.dataset.title = declensionNum(hours, ['час', 'часа', 'часов']);
        $minutes.dataset.title = declensionNum(minutes, ['минута', 'минуты', 'минут']);
        $seconds.dataset.title = declensionNum(seconds, ['секунда', 'секунды', 'секунд']);
    }
    // получаем элементы, содержащие компоненты даты
    const $days = document.querySelector('.timer__days');
    const $hours = document.querySelector('.timer__hours');
    const $minutes = document.querySelector('.timer__minutes');
    const $seconds = document.querySelector('.timer__seconds');
    // вызываем функцию countdownTimer
    countdownTimer();
    // вызываем функцию countdownTimer каждую секунду
    timerId = setInterval(countdownTimer, 1000);
    });
</script>
</head>
    <style>
        input{
            padding:10px;
            margin:5px;
            border: 1px solid lightgray;
            border-radius: 10px;
            outline: 0;
            width:300px;
        }
        input:focus{
            border: 1px solid rgb(0, 136, 255);
            box-shadow: 0 0 5px 0 rgb(0, 136, 255);
        }
    </style>
    <body>
        <div style="text-align:center">
            <h3>
                Таймер выхода нового MeTube
            </h3>
            <div class="timer">
                <div class="timer__items">
                <div class="timer__item timer__days">00</div>
                <div class="timer__item timer__hours">00</div>
                <div class="timer__item timer__minutes">00</div>
                <div class="timer__item timer__seconds">00</div>
                </div>
            </div>
            <form method="GET" action="https://moogle.metubee.xyz/index.php">
                <input type="search" name="q" placeholder="Поиск в Moogle">
            </form>
        </div>
    </body>

</html>

