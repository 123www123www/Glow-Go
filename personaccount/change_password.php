<?php
// change_password.php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_SESSION['login'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Проверка на пустые поля
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $response['status'] = 'error';
        $response['message'] = 'Все поля обязательны для заполнения.';
        echo json_encode($response);
        exit;
    }

    // Проверка на совпадение паролей
    if ($newPassword !== $confirmPassword) {
        $response['status'] = 'error';
        $response['message'] = 'Пароли не совпадают.';
        echo json_encode($response);
        exit;
    }

    // Получаем хэш пароля из базы данных
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($user && password_verify($currentPassword, $user['password_hash'])) {
        // Хэширование нового пароля
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Обновление пароля в базе данных
        $updateStmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE login = ?");
        if ($updateStmt->execute([$newPasswordHash, $login])) {
            $response['status'] = 'success';
            $response['message'] = 'Пароль успешно изменен.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Ошибка при обновлении пароля.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Неверный текущий пароль.';
    }

    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сменить пароль</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="password"], input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        #response {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Сменить пароль</h1>
        <form method="POST" id="change-password-form">
            <label for="current_password">Текущий пароль:</label>
            <input type="password" name="current_password" required>
            
            <label for="new_password">Новый пароль:</label>
            <input type="password" name="new_password" required>

            <label for="confirm_password">Подтвердите новый пароль:</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Сменить пароль</button>
        </form>

        <div id="response"></div>
    </div>

    <script>
        document.getElementById('change-password-form').onsubmit = async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const response = await fetch('change_password.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            document.getElementById('response').innerText = data.message;
        };
    </script>
</body>
</html>
