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

    // Получаем email текущего пользователя из сессии
    $current_user_email = $_SESSION['email'] ?? null;

    if (!$current_user_email) {
        // Если пользователь не авторизован, перенаправляем на страницу входа
        header("Location: login.html");
        exit();
    }

    // Получаем список услуг из базы данных
    $services_sql = "SELECT service_id, sname FROM services";
    $services_stmt = $pdo->prepare($services_sql);
    $services_stmt->execute();
    $services = $services_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're my princess - Запись на Услугу</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="book-appointment.css">
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
                <li><a href="view-appointments.php">Мои записи</a></li>
                <li><a href="profile.php">Профиль</a></li>
                <li><a href="login.html">Выход</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="container">
        <h1>Запись на Услугу</h1>
        <form action="book.appointments.php" method="post">
            <div class="form-group">
                <label for="service">Выберите Услугу:</label>
                <select id="service" name="service" class="form-control" required>
                    <?php foreach ($services as $service): ?>
                        <option value="<?= htmlspecialchars($service['service_id']) ?>">
                            <?= htmlspecialchars($service['sname']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Выберите Дату:</label>
                <input type="date" id="date" name="date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="time">Выберите Время:</label>
                <input type="time" id="time" name="time" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="name">Имя:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Электронная почта:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($current_user_email) ?>" readonly required>
            </div>
            <div class="form-group">
                <label for="phone">Телефон:</label>
                <input type="tel" id="phone" name="phone" class="form-control" required>
            </div>
            <button type="submit">Перейти к оплате</button>
        </form>
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
            © 2024 You're My Princess. Все права защищены.
        </div>
    </footer>
</body>
</html>