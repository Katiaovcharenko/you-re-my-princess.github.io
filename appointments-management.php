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

    // Обработка добавления новой записи
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
        $client_name = $_POST['appointment-name'];
        $appointment_date = $_POST['appointment-date'];
        $appointment_time = $_POST['appointment-time'];
        $service = $_POST['appointment-service'];

        $sql = "INSERT INTO book_appointments (name, date, time, service, email, phone) VALUES (:client_name, :appointment_date, :appointment_time, :service, :email, :phone)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'client_name' => $client_name,
            'appointment_date' => $appointment_date,
            'appointment_time' => $appointment_time,
            'service' => $service,
            'email' => $_POST['email'],
            'phone' => $_POST['phone']
        ]);
    }

    // Обработка редактирования записи
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $client_name = $_POST['appointment-name'];
        $appointment_date = $_POST['appointment-date'];
        $appointment_time = $_POST['appointment-time'];
        $service = $_POST['appointment-service'];

        $sql = "UPDATE book_appointments SET name = :client_name, date = :appointment_date, time = :appointment_time, service = :service, email = :email, phone = :phone WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'client_name' => $client_name,
            'appointment_date' => $appointment_date,
            'appointment_time' => $appointment_time,
            'service' => $service,
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'id' => $id
        ]);
    }
    // Обработка удаления записи
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id'];

        $sql = "DELETE FROM book_appointments WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    // Получение всех записей для отображения
    $sql = "
        SELECT b.id, b.date, b.time, s.sname AS service_name, b.email, b.name, b.phone
        FROM book_appointments b
        JOIN services s ON b.service = s.service_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're my princess - Управление Записями</title>
    <link rel="stylesheet" href="appointments-management.css">
</head>
<body>
    <header>
        <nav class="header-left">
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="admin-dashboard.html">Админ Панель</a></li>
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
    <div class="container">
        <h1>Управление Записями</h1>
        <div class="appointment-management">
            <h2>Добавить Новую Запись</h2>
            <form id="appointment-form" action="appointments-management.php" method="post">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="appointment-name">Имя Клиента:</label>
                    <input type="text" id="appointment-name" name="appointment-name" required>
                </div>
                <div class="form-group">
                    <label for="appointment-date">Дата Записи:</label>
                    <input type="date" id="appointment-date" name="appointment-date" required>
                </div>
                <div class="form-group">
                    <label for="appointment-time">Время Записи:</label>
                    <input type="time" id="appointment-time" name="appointment-time" required>
                </div>
                <div class="form-group">
                    <label for="appointment-service">Услуга:</label>
                    <select id="appointment-service" name="appointment-service" required>
                    <option value="1">Мужская стрижка</option>
                    <option value="2">Женская стрижка (классическая)</option>
                    <option value="3">Женская стрижка (креативная)</option>
                    <option value="4">Детская стрижка</option>
                    <option value="5">Укладка</option>
                    <option value="6">Полное окрашивание</option>
                    <option value="7">Мелирование</option>
                    <option value="8">Колорирование</option>
                    <option value="9">Тонирование</option>
                    <option value="10">Лечебная маска</option>
                    <option value="11">Ламинирование</option>
                    <option value="12">Кератиновое выпрямление</option>
                    <option value="13">Ботокс для волос</option>
                        <!-- Добавьте другие услуги по мере необходимости -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Электронная почта:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Телефон:</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <button type="submit">Добавить Запись</button>
            </form>
        </div>
        <div class="appointment-list">
            <h2>Список Записей</h2>
            <ul>
                <?php foreach ($appointments as $appointment): ?>
                    <li>
                        <h3>Запись <?= htmlspecialchars($appointment['id']); ?></h3>
                        <p>Имя Клиента: <?= htmlspecialchars($appointment['name']); ?></p>
                        <p>Дата: <?= htmlspecialchars($appointment['date']); ?></p>
                        <p>Время: <?= htmlspecialchars($appointment['time']); ?></p>
                        <p>Услуга: <?= htmlspecialchars($appointment['service_name']); ?></p>
                        <p>Электронная почта: <?= htmlspecialchars($appointment['email']); ?></p>
                        <p>Телефон: <?= htmlspecialchars($appointment['phone']); ?></p>
                        <form action="appointments-management.php" method="post" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($appointment['id']); ?>">
                            <button type="submit">Удалить</button>
                        </form>
                        <button type="button" onclick="editAppointment(<?= htmlspecialchars(json_encode($appointment)); ?>)">Редактировать</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <footer>
        <p>© 2024 You're My Princess. Все права защищены.</p>
    </footer>
    <script>
        function editAppointment(appointment) {
            document.getElementById('appointment-name').value = appointment.name;
            document.getElementById('appointment-date').value = appointment.date;
            document.getElementById('appointment-time').value = appointment.time;
            document.getElementById('appointment-service').value = appointment.service;
            document.getElementById('email').value = appointment.email;
            document.getElementById('phone').value = appointment.phone;
            
            const form = document.getElementById('appointment-form');
            form.querySelector('input[name="action"]').value = 'edit';
            form.action = 'appointments-management.php';
            if (!document.getElementById('appointment-id')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id';
                input.id = 'appointment-id';
                input.value = appointment.id;
                form.appendChild(input);
            } else {
                document.getElementById('appointment-id').value = appointment.id;
            }
            document.querySelector('button[type="submit"]').innerText = 'Обновить Запись';
        }
    </script>
</body>
</html>