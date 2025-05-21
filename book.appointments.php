<?php
session_start();

// Подключение к базе данных
$host = 'localhost';
$db = 'salon_beauty';
$user = 'postgres';
$password = '0810';
$dsn = "pgsql:host=$host;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Проверка авторизации пользователя
    if (!isset($_SESSION['email'])) {
        header("Location: login.html");
        exit();
    }

    // Получение данных из формы
    $service = $_POST['service'] ?? null;
    $date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;
    $name = $_POST['name'] ?? null;
    $email = $_SESSION['email']; // Берем из сессии
    $phone = $_POST['phone'] ?? null;

    // Валидация данных
    if (!$service || !$date || !$time || !$name || !$phone) {
        throw new Exception("Все поля формы должны быть заполнены");
    }

    // Проверка формата даты и времени
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        throw new Exception("Неверный формат даты");
    }

    if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
        throw new Exception("Неверный формат времени");
    }

    // Вставка записи в базу данных
    $sql = "INSERT INTO book_appointments 
            (date, time, name, email, phone, service) 
            VALUES 
            (:date, :time, :name, :email, :phone, :service)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':service', $service);

    if ($stmt->execute()) {
        // Перенаправляем на страницу успешной записи
        header("Location: view-appointments.php");
        exit();
    } else {
        throw new Exception("Ошибка при записи на прием");
    }

} catch (PDOException $e) {
    // Логирование ошибки и перенаправление с сообщением
    error_log("Database error: " . $e->getMessage());
    header("Location: book-appointment.php?error=db_error");
    exit();
} catch (Exception $e) {
    // Логирование ошибки и перенаправление с сообщением
    error_log("Appointment error: " . $e->getMessage());
    header("Location: book-appointment.php?error=" . urlencode($e->getMessage()));
    exit();
}