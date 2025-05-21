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

    // Обработка добавления новой услуги
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
        $name = $_POST['service-name'];
        $description = $_POST['service-description'];
        $price = $_POST['service-price'];
        $duration = $_POST['service-duration'];
        $category_id = $_POST['category-id'];

        $sql = "INSERT INTO services (sname, description, price, duration, category_id) VALUES (:sname, :description, :price, :duration, :category_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'sname' => $name,
            'description' => $description,
            'price' => $price,
            'duration' => $duration,
            'category_id' => $category_id
        ]);
    }

    // Обработка редактирования услуги
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $name = $_POST['service-name'];
        $description = $_POST['service-description'];
        $price = $_POST['service-price'];
        $duration = $_POST['service-duration'];
        $category_id = $_POST['category-id'];

        $sql = "UPDATE services SET sname = :sname, description = :description, price = :price, duration = :duration, category_id = :category_id WHERE service_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'sname' => $name,
            'description' => $description,
            'price' => $price,
            'duration' => $duration,
            'category_id' => $category_id,
            'id' => $id
        ]);
    }

    // Обработка удаления услуги
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id'];

        $sql = "DELETE FROM services WHERE service_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    // Получение всех услуг для отображения с названиями категорий
    $sql = "
        SELECT s.service_id, s.sname, s.description, s.price, s.duration, c.value AS category_name
        FROM services s
        JOIN categories c ON s.category_id = c.id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Получение всех категорий для выбора
    $sql = "SELECT id, value FROM categories";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're my princess - Управление Услугами</title>
    <link rel="stylesheet" href="services-management.css">
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
        <h1>Управление Услугами</h1>
        <div class="service-management">
            <h2>Добавить Новую Услугу</h2>
            <form id="service-form" action="services-management.php" method="post">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="service-name">Название Услуги:</label>
                    <input type="text" id="service-name" name="service-name" required>
                </div>
                <div class="form-group">
                    <label for="service-description">Описание Услуги:</label>
                    <textarea id="service-description" name="service-description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="service-price">Цена:</label>
                    <input type="number" id="service-price" name="service-price" required>
                </div>
                <div class="form-group">
                    <label for="service-duration">Длительность:</label>
                    <input type="number" id="service-duration" name="service-duration" required>
                </div>
                <div class="form-group">
                    <label for="category-id">Категория:</label>
                    <select id="category-id" name="category-id" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']); ?>"><?= htmlspecialchars($category['value']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit">Добавить Услугу</button>
            </form>
        </div>
        <div class="service-list">
            <h2>Список Услуг</h2>
            <ul>
                <?php foreach ($services as $service): ?>
                    <li>
                        <h3><?= htmlspecialchars($service['sname']); ?></h3>
                        <p><?= htmlspecialchars($service['description']); ?></p>
                        <p>Цена: <?= htmlspecialchars($service['price']); ?> руб.</p>
                        <p>Длительность: <?= htmlspecialchars($service['duration']); ?> минут</p>
                        <p>Категория: <?= htmlspecialchars($service['category_name']); ?></p>
                        <form action="services-management.php" method="post" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($service['service_id']); ?>">
                            <button type="submit">Удалить</button>
                        </form>
                        <button type="button" onclick="editService(<?= htmlspecialchars(json_encode($service)); ?>)">Редактировать</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <footer>
        <p>© 2024 You're My Princess. Все права защищены.</p>
    </footer>
    <script>
        function editService(service) {
            document.getElementById('service-name').value = service.sname;
            document.getElementById('service-description').value = service.description;
            document.getElementById('service-price').value = service.price;
            document.getElementById('service-duration').value = service.duration;
            document.getElementById('category-id').value = service.category_id;
            
            const form = document.getElementById('service-form');
            form.querySelector('input[name="action"]').value = 'edit';
            form.action = 'services-management.php';
            if (!document.getElementById('service-edit-id')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id';
                input.id = 'service-edit-id';
                input.value = service.service_id;
                form.appendChild(input);
            } else {
                document.getElementById('service-edit-id').value = service.service_id;
            }
            document.querySelector('button[type="submit"]').innerText = 'Обновить Услугу';
        }
    </script>
</body>
</html>