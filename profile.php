<?php
session_start(); 
if (!isset($_SESSION['email'])) { 
    header('Location: login.html'); 
    exit(); 
}
$host = 'localhost';
$db = 'salon_beauty';
$user = 'postgres';
$password = '0810';
$dsn = "pgsql:host=$host;dbname=$db";

try { 
    $pdo = new PDO($dsn, $user, $password); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    
    $email = $_SESSION['email']; 
    $sql = "SELECT firstname, secondname, fathername, phone, role, profile_picture FROM users WHERE email = :email"; 
    $stmt = $pdo->prepare($sql); 
    $stmt->execute(['email' => $email]); 
    $user = $stmt->fetch(PDO::FETCH_ASSOC); 
    
    if (!$user) { // Данные не найдены 
        echo "Ошибка: пользователь не найден."; 
        exit(); 
    }

    $firstname = htmlspecialchars($user['firstname']); 
    $secondname = htmlspecialchars($user['secondname']); 
    $fathername = htmlspecialchars($user['fathername']); 
    $phone = htmlspecialchars($user['phone']); 
    $role = htmlspecialchars($user['role']);
    $profile_picture = htmlspecialchars($user['profile_picture']);

} catch (PDOException $e) { 
    echo "Ошибка подключения: " . $e->getMessage(); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're my princess - Профиль</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <header>
        <nav class="header-left">
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="service.php">Услуги</a></li>
                <li><a href="info.php">О нас</a></li>
            </ul>
        </nav>
        <h1 class="logo">You're My Princess</h1>
        <nav class="header-right">
            <ul>
                <?php if ($role == 'Администратор'): ?>
                    <li><a href="admin-dashboard.html">Админ панель</a></li>
                <?php endif; ?>
                <li><a href="view-appointments.php">Мои записи</a></li>
                <li><a href="login.html">Выход</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-container">
        <section class="profile-section">
            <div class="profile-header">
                <h1>Ваш Профиль</h1>
            </div>
            <div class="profile-content">
                <div class="form-group">
                    <img src="uploads/profile_pictures/<?= $profile_picture ?>" alt src="empty.jpg" class="profile-picture-preview" id="profile-preview">
                </div>
                <div class="profile-details">
                    <h2><?php echo $secondname; ?> <?php echo $firstname; ?> <?php echo $fathername; ?></h2>
                    <span class="role"><?php echo $role; ?></span>
                    <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($email); ?></p>
                    <p><i class="fas fa-phone"></i> <?php echo $phone; ?></p>
                </div>
                <button class="btn-edit" onclick="window.location.href='edit-profile.php'">Редактировать Профиль</button>
            </div>
        </section>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-logo">You're My Princess</div>
            <div class="social-links">
                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-link"><i class="fab fa-vk"></i></a>
                <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        <div class="copyright">
            © 2023 You're My Princess. Все права защищены.
        </div>
    </footer>
</body>
</html>