<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Проверка reCAPTCHA
    $recaptcha_secret = '6LdfkVoqAAAAAFhc1--hRbO3tcscILxKN_uGrhl-';
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Запрос к API reCAPTCHA
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
    $responseKeys = json_decode($response, true);

    // Проверка успешности reCAPTCHA
    if (intval($responseKeys["success"]) !== 1) {
        echo 'Пожалуйста, пройдите проверку reCAPTCHA.';
    } else {
        // Обработка данных авторизации
        $login = $_POST['login'];
        $password = $_POST['password'];

        // Подключение к базе данных
        $servername = "localhost";
        $username = "root";
        $password_db = "";
        $dbname = "cosmetology_clinic";

        // Создание соединения
        $conn = new mysqli($servername, $username, $password_db, $dbname);

        // Проверка соединения
        if ($conn->connect_error) {
            die("Ошибка подключения: " . $conn->connect_error);
        }

        // Проверка логина и пароля
        $sql = "SELECT password_hash, role FROM users WHERE login = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $login);
            $stmt->execute();
            $stmt->bind_result($password_hash, $role);
            if ($stmt->fetch()) {
                // Проверка пароля
                if (password_verify($password, $password_hash)) {
                    echo "Авторизация успешна. Ваша роль: $role";
                } else {
                    echo "Неверный пароль.";
                }
            } else {
                echo "Пользователь не найден.";
            }
            $stmt->close();
        } else {
            echo "Ошибка подготовки запроса: " . $conn->error;
        }

        // Закрытие соединения
        $conn->close();
    }
}
?>
