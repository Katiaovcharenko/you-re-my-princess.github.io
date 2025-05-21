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

    // SQL запрос с условием совпадения email в book_appointments и users
    $sql = "SELECT b.id, b.date, b.time, s.sname, b.email, b.name, b.phone, s.description
            FROM book_appointments b
            JOIN services s ON b.service = s.service_id
            JOIN users u ON b.email = u.email
            WHERE b.email = :user_email";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_email', $current_user_email);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>You're my princess - Просмотр Записей</title>
    <link rel="stylesheet" href="view-appointments.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&display=swap">
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
                <li><a href="profile.php">Профиль</a></li>
                <li><a href="login.html">Выход</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-container">
        <section class="appointments-section">
            <h1 class="section-title">Ваши Записи</h1>
            
            <div class="appointment-list">
                <?php if (empty($appointments)): ?>
                    <div class="no-appointments">
                        <p>У вас пока нет записей</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($appointments as $appointment): ?>
                        <div class="appointment-card">
                            <h3>Запись </h3>
                            <div class="appointment-details">
                                <div class="detail-item">
                                    <strong>Дата</strong>
                                    <p><?= htmlspecialchars($appointment['date']) ?></p>
                                </div>
                                <div class="detail-item">
                                    <strong>Время</strong>
                                    <p><?= htmlspecialchars($appointment['time']) ?></p>
                                </div>
                                <div class="detail-item">
                                    <strong>Услуга</strong>
                                    <p><?= htmlspecialchars($appointment['sname']) ?></p>
                                </div>
                                <div class="detail-item">
                                    <strong>Описание</strong>
                                    <p><?= htmlspecialchars($appointment['description']) ?></p>
                                </div>
                                <div class="detail-item">
                                    <strong>Имя</strong>
                                    <p><?= htmlspecialchars($appointment['name']) ?></p>
                                </div>
                                <div class="detail-item">
                                    <strong>Email</strong>
                                    <p><?= htmlspecialchars($appointment['email']) ?></p>
                                </div>
                                <div class="detail-item">
                                    <strong>Телефон</strong>
                                    <p><?= htmlspecialchars($appointment['phone']) ?></p>
                                </div>
                            </div>
                            <span class="status-confirmed">Подтверждена</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
