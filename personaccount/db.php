<?php
// db.php
$host = 'localhost';
$db_name = 'cosmetology_clinic';
$username = 'root'; // Имя пользователя по умолчанию
$password = ''; // Пароль по умолчанию

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
?>
