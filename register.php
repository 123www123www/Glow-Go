<?php

// register.php
require 'db.php';
session_start();

// Заголовок, чтобы браузер понимал, что возвращаем JSON
header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $phoneNumber = trim($_POST['phoneNumber']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Проверка на пустые поля
    if (empty($login) || empty($phoneNumber) || empty($password) || empty($confirmPassword)) {
        $response['status'] = 'error';
        $response['message'] = 'Все поля обязательны для заполнения.';
        echo json_encode($response);
        exit;
    }

   
    
    // Проверка на совпадение паролей
    if ($password !== $confirmPassword) {
        $response['status'] = 'error';
        $response['message'] = 'Пароли не совпадают.';
        echo json_encode($response);
        exit;
    }

    // Хэширование пароля
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Вставка данных в базу
    $stmt = $pdo->prepare("INSERT INTO Users (login, password_hash, phone_number, role) VALUES (?, ?, ?, ?)");
    $role = 'user'; // или 'admin', в зависимости от ваших требований
    if ($stmt->execute([$login, $passwordHash, $phoneNumber, $role])) {
        // Сохраняем данные пользователя в сессии
        $_SESSION['login'] = $login;
        $_SESSION['phoneNumber'] = $phoneNumber;

        $response['status'] = 'success';
        $response['message'] = 'Регистрация успешна!';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Ошибка регистрации.';
    }

    echo json_encode($response);
    exit;
}

?>
