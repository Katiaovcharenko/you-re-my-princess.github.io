<?php
session_start();
$host = 'localhost';
$db = 'salon_beauty';
$user = 'postgres';
$password = '0810';
$dsn = "pgsql:host=$host;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $secondname = $_POST['secondname'];
    $firstname = $_POST['firstname'];
    $fathername = $_POST['fathername'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хэширование пароля

    $sql = "INSERT INTO users (firstname, secondname, fathername, email, phone, password) VALUES (:firstname, :secondname, :fathername, :email, :phone, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['firstname' => $firstname, 'secondname' => $secondname, 'fathername' => $fathername, 'email' => $email, 'phone' => $phone, 'password' => $password]);

    header('Location: login.html'); 
    exit();
} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
?>
