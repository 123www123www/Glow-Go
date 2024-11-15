<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'cosmetology_clinic';

// Создаем соединение с базой данных
$mysqli = new mysqli($host, $user, $password, $dbname);

// Проверяем соединение
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

// Устанавливаем кодировку для корректной работы с UTF-8
$mysqli->set_charset("utf8");
?>
