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

    $email = $_POST['email'];
    $password_input = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $mail = $stmt->fetch(PDO::FETCH_ASSOC); 

    if ($mail && password_verify($password_input, $mail['password'])) {
        $_SESSION['email'] = $email; 
        header('Location: profile.php'); 
        exit(); 
    } else { 
        header('Location: login.html'); 
        exit();
    }
} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
?>
