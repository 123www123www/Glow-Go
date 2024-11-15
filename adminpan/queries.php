<?php
// Подключаем файл с базой данных
include 'database.php';

// Функция для выполнения запросов
function executeQuery($query) {
    global $mysqli; // Используем глобальную переменную
    $result = $mysqli->query($query);
    if (!$result) {
        die("Ошибка выполнения запроса: " . $mysqli->error);
    }
    return $result;
}

// Функции для выполнения запросов
function getServicesByTypeAndCategory($type, $category) {
    $query = "SELECT procedure_name, COUNT(*) as total 
              FROM procedures 
              WHERE procedure_name LIKE '%$type%' AND description LIKE '%$category%'
              GROUP BY procedure_name";
    return executeQuery($query);
}

function getPopularServices() {
    $query = "SELECT p.procedure_name, COUNT(a.procedure_id) as count 
              FROM application a 
              JOIN procedures p ON a.procedure_id = p.procedure_id 
              GROUP BY p.procedure_name 
              ORDER BY count DESC";
    return executeQuery($query);
}

function getPatientsByDoctorProfile($profile) {
    $query = "SELECT c.name, c.phone_number, c.email 
              FROM clients c 
              JOIN application a ON c.client_id = a.client_id 
              JOIN employees e ON a.employee_id = e.employee_id 
              WHERE e.position LIKE '%$profile%'";
    return executeQuery($query);
}

function getPatientsByDoctorAndDate($doctorId, $startDate, $endDate) {
    $query = "SELECT c.name, c.phone_number 
              FROM clients c 
              JOIN application a ON c.client_id = a.client_id 
              WHERE a.employee_id = $doctorId 
              AND a.application_datetime BETWEEN '$startDate' AND '$endDate'";
    return executeQuery($query);
}

function getNewPatients() {
    $query = "SELECT name, phone_number, email 
              FROM clients ";
    return executeQuery($query);
}

function getDoctorsByProfileAndExperience($profile, $minExperience) {
    $query = "SELECT e.full_name, COUNT(*) as total 
              FROM employees e 
              WHERE e.position LIKE '%$profile%' 
              AND (YEAR(NOW()) - YEAR(e.hire_date)) >= $minExperience
              GROUP BY e.full_name";
    return executeQuery($query);
}

function getAveragePatientsPerDay($doctorId, $startDate, $endDate) {
    $query = "SELECT AVG(count) as average 
              FROM (
                  SELECT COUNT(*) as count 
                  FROM application 
                  WHERE employee_id = $doctorId 
                  AND application_datetime BETWEEN '$startDate' AND '$endDate'
                  GROUP BY DATE(application_datetime)
              ) as daily_counts";
    return executeQuery($query);
}

function getCabinetsVisits($startDate, $endDate) {
    $query = "SELECT room_id, COUNT(*) as visits 
              FROM application 
              WHERE application_datetime BETWEEN '$startDate' AND '$endDate' 
              GROUP BY room_id";
    return executeQuery($query);
}

function getServicesByPatientAndDate($patientId, $startDate, $endDate) {
    $query = "SELECT p.procedure_name 
              FROM application a 
              JOIN procedures p ON a.procedure_id = p.procedure_id 
              WHERE a.client_id = $patientId 
              AND a.application_datetime BETWEEN '$startDate' AND '$endDate'";
    return executeQuery($query);
}

function getPaymentRequestsByServiceType($serviceType) {
    $query = "SELECT a.application_id, a.application_datetime, p.procedure_name 
              FROM application a 
              JOIN procedures p ON a.procedure_id = p.procedure_id 
              WHERE p.procedure_name LIKE '%$serviceType%'";
    return executeQuery($query);
}

// Обработка параметра action
$action = isset($_GET['action']) ? $_GET['action'] : null;

switch ($action) {
    case 'services_by_type_category':
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        echo "<h3>Список и общее число услуг типа '$type' и категории '$category':</h3>";
        $services = getServicesByTypeAndCategory($type, $category);
        while ($row = $services->fetch_assoc()) {
            echo "Услуга: " . $row['procedure_name'] . ", Количество: " . $row['total'] . "<br>";
        }
        break;

    case 'popular_services':
        echo "<h3>Самые популярные услуги:</h3>";
        $popular_services = getPopularServices();
        while ($row = $popular_services->fetch_assoc()) {
            echo "Услуга: " . $row['procedure_name'] . ", Количество заявок: " . $row['count'] . "<br>";
        }
        break;

    case 'patients_by_doctor_profile':
        $profile = isset($_GET['profile']) ? $_GET['profile'] : '';
        echo "<h3>Пациенты у врача с профилем '$profile':</h3>";
        $patients = getPatientsByDoctorProfile($profile);
        while ($row = $patients->fetch_assoc()) {
            echo "Пациент: " . $row['name'] . ", Телефон: " . $row['phone_number'] . ", Email: " . $row['email'] . "<br>";
        }
        break;

    case 'patients_by_doctor_and_date':
        $doctorId = isset($_GET['doctorId']) ? intval($_GET['doctorId']) : 0;
        $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
        $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
        echo "<h3>Пациенты, перенесшие операции у врача:</h3>";
        $operation_patients = getPatientsByDoctorAndDate($doctorId, $startDate, $endDate);
        while ($row = $operation_patients->fetch_assoc()) {
            echo "Пациент: " . $row['name'] . ", Телефон: " . $row['phone_number'] . "<br>";
        }
        break;

    case 'new_patients':
        echo "<h3>Новые пациенты:</h3>";
        $new_patients = getNewPatients();
        while ($row = $new_patients->fetch_assoc()) {
            echo "Пациент: " . $row['name'] . ", Телефон: " . $row['phone_number'] . ", Email: " . $row['email'] . "<br>";
        }
        break;

    case 'doctors_by_profile_and_experience':
        $doctor_profile = isset($_GET['doctor_profile']) ? $_GET['doctor_profile'] : '';
        $min_experience = isset($_GET['min_experience']) ? intval($_GET['min_experience']) : 0;
        echo "<h3>Врачи с профилем '$doctor_profile':</h3>";
        $doctors = getDoctorsByProfileAndExperience($doctor_profile, $min_experience);
        while ($row = $doctors->fetch_assoc()) {
            echo "Врач: " . $row['full_name'] . ", Количество пациентов: " . $row['total'] . "<br>";
        }
        break;

    case 'average_patients_per_day':
        $doctorId = isset($_GET['doctorId']) ? intval($_GET['doctorId']) : 0;
        $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
        $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
        echo "<h3>Среднее число принятых пациентов в день:</h3>";
        $average_patients = getAveragePatientsPerDay($doctorId, $startDate, $endDate);
        $row = $average_patients->fetch_assoc();
        echo "Среднее количество пациентов в день: " . $row['average'] . "<br>";
        break;

    case 'cabinets_visits':
        $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
        $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
        echo "<h3>Посещения кабинетов:</h3>";
        $cabinets_visits = getCabinetsVisits($startDate, $endDate);
        while ($row = $cabinets_visits->fetch_assoc()) {
            echo "Кабинет: " . $row['room_id'] . ", Посещения: " . $row['visits'] . "<br>";
        }
        break;

    case 'services_by_patient_and_date':
        $patientId = isset($_GET['patientId']) ? intval($_GET['patientId']) : 0;
        $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
        $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
        echo "<h3>Услуги пациента:</h3>";
        $patient_services = getServicesByPatientAndDate($patientId, $startDate, $endDate);
        while ($row = $patient_services->fetch_assoc()) {
            echo "Услуга: " . $row['procedure_name'] . "<br>";
        }
        break;

    case 'payment_requests_by_service_type':
        $serviceType = isset($_GET['serviceType']) ? $_GET['serviceType'] : '';
        echo "<h3>Запросы на оплату по типу услуги '$serviceType':</h3>";
        $payment_requests = getPaymentRequestsByServiceType($serviceType);
        while ($row = $payment_requests->fetch_assoc()) {
            echo "Запрос ID: " . $row['application_id'] . ", Дата: " . $row['application_datetime'] . ", Услуга: " . $row['procedure_name'] . "<br>";
        }
        break;

    default:
        echo "<h3>Пожалуйста, выберите действие из списка.</h3>";
        break;
}

// Закрываем соединение с базой данных
$mysqli->close();
?>


