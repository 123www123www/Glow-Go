<!-- personaccount/index.php -->
<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['login']) || !isset($_SESSION['phoneNumber'])) {
    // Если данные не найдены, перенаправляем на страницу авторизации или регистрации
    header("Location: login.php");
    exit;
}

$login = $_SESSION['login'];
$phoneNumber = $_SESSION['phoneNumber'];
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/main.css">
        <link rel="icon" type="images/x-icon" href="../images/icon/logo.svg">
        <title>Glow&Go</title>
        <script src="../js/script.js" defer></script>
    </head>
    <body>
        <header>
            <div class="container">
                <nav class="menu">
                    <ul class="menu__top">
                        <li class="menu__item"><a href="/" class="menu__link"><img src="../images/icon/logo.svg" alt="icon"></a></li>
                        <li class="menu__item"><a href="../uslugi/" class="menu__link">Услуги</a></li>
                        <li class="menu__item"><a href="../specialist/" class="menu__link">Наши специалисты</a></li>
                        <li class="menu__item"><a href="#" class="menu__link"><img src="../images/icon/cab.png" alt="icon"></a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <section>
            <div class="profil">
                <div class="container">
                    <h1 class="profil__title">Личный кабинет</h1>
                    <div class="profil__box">
                        <img src="../images/img/profil.png" alt="img" class="profil__img">
                        <div class="profil__info">
                        <p class="profil__name"><?php echo htmlspecialchars($login); ?></p>
                        <p class="profil__telnum"><?php echo htmlspecialchars($phoneNumber); ?></p>
                        <a href="change_password.php" class="profil__act">Сменить пароль</a>
                        <a href="admin_login.php" class="profil__act">Админ панель</a>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer>
            <div class="container">
                <div class="end">
                    <a href="/" class="end__link"><img src="../images/icon/logo.svg" alt="icon"></a>
                    <ul class="end__list">
                        <li class="end__item"><a href="#" class="end__link"><img src="../images/icon/vk.png" alt="icon" class="end__img"></a></li>
                        <li class="end__item"><a href="#" class="end__link"><img src="../images/icon/insta.png" alt="icon" class="end__img"></a></li>
                        <li class="end__item"><a href="#" class="end__link"><img src="../images/icon/facebook.png" alt="icon" class="end__img"></a></li>
                    </ul>
                </div>
            </div>
        </footer>
    </body>
</html>