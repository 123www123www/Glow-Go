<?php
// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Подключение к базе данных
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cosmetology_clinic";

    // Создание соединения
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Проверяем соединение
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    // Получаем данные из формы
    $client_name = $conn->real_escape_string($_POST['client_name']);
    $agreement = isset($_POST['agreement']) ? 1 : 0;
    $procedure_id = 1; // ID процедуры, можно изменить

    // Время заявки
    $application_datetime = date("Y-m-d H:i:s");

    // Поиск клиента только по имени
    $result = $conn->query("SELECT client_id FROM clients WHERE name='$client_name'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $client_id = $row['client_id'];
    } else {
        // Если клиент не найден, добавляем его
        $conn->query("INSERT INTO clients (name) VALUES ('$client_name')");
        $client_id = $conn->insert_id; // Получаем ID нового клиента
    }

    // Вставляем данные в таблицу application
    $sql = "INSERT INTO application (client_id, application_datetime, agreement, procedure_id) 
            VALUES ('$client_id', '$application_datetime', '$agreement', '$procedure_id')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.html");
    exit();
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }

    // Закрываем соединение
    $conn->close();
}
?>
