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

    // Обработка добавления новой акции
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
        $pname = $_POST['promo-name'];
        $description = $_POST['promo-description'];

        $sql = "INSERT INTO promotions (pname, description) VALUES (:pname, :description)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'pname' => $pname,
            'description' => $description
        ]);
    }

    // Обработка редактирования акции
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
        $id = $_POST['promo_id'];
        $pname = $_POST['promo-name'];
        $description = $_POST['promo-description'];

        $sql = "UPDATE promotions SET pname = :pname, description = :description WHERE promo_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'pname' => $pname,
            'description' => $description,
            'id' => $id
        ]);
    }

    // Обработка удаления акции
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['promo_id'];

        $sql = "DELETE FROM promotions WHERE promo_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    // Получение всех акций для отображения
    $sql = "SELECT * FROM promotions";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're my princess - Управление Акциями</title>
    <link rel="stylesheet" href="promotions-management.css">
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
                <li><a href="login.htlm">Выход</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h1>Управление Акциями</h1>
        <div class="promo-management">
            <h2>Добавить Новую Акцию</h2>
            <form id="promo-form" action="promotions-management.php" method="post">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="promo-name">Название Акции:</label>
                    <input type="text" id="promo-name" name="promo-name" required>
                </div>
                <div class="form-group">
                    <label for="promo-description">Описание Акции:</label>
                    <textarea id="promo-description" name="promo-description" required></textarea>
                </div>
                <button type="submit">Добавить Акцию</button>
            </form>
        </div>
        <div class="promotion-list">
            <h2>Список Акций</h2>
            <ul>
                <?php foreach ($promotions as $promo): ?>
                    <li>
                        <h3><?= htmlspecialchars($promo['pname']); ?></h3>
                        <p><?= htmlspecialchars($promo['description']); ?></p>
                        <form action="promotions-management.php" method="post" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="promo_id" value="<?= htmlspecialchars($promo['promo_id']); ?>">
                            <button type="submit">Удалить</button>
                        </form>
                        <button type="button" onclick="editPromo(<?= htmlspecialchars(json_encode($promo)); ?>)">Редактировать</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <footer>
        <p>© 2024 You're My Princess. Все права защищены.</p>
    </footer>
    <script>
        function editPromo(promo) {
            document.getElementById('promo-name').value = promo.pname;
            document.getElementById('promo-description').value = promo.description;

            const form = document.getElementById('promo-form');
            form.querySelector('input[name="action"]').value = 'edit';
            form.action = 'promotions-management.php';
            if (!document.getElementById('promo-edit-id')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'promo_id';
                input.id = 'promo-edit-id';
                input.value = promo.promo_id;
                form.appendChild(input);
            } else {
                document.getElementById('promo-edit-id').value = promo.promo_id;
            }
            document.querySelector('button[type="submit"]').innerText = 'Обновить Акцию';
        }
    </script>
</body>
</html>