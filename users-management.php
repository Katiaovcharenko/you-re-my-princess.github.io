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

    // Обработка добавления нового пользователя
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
        $secondname = $_POST['secondname'];
        $firstname = $_POST['firstname'];
        $fathername = $_POST['fathername'];
        $email = $_POST['user-email'];
        $phone = $_POST['user-phone'];
        $role = $_POST['user-role'];
        $password = password_hash($_POST['user-password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (firstname, secondname, fathername, email, phone, password, role) VALUES (:name, :email, :phone, :password, :role)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'secondname' => $secondname,
            'firstname' => $firstname,
            'fathername' => $fathername,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'role' => $role
        ]);
    }

    // Обработка редактирования пользователя
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $secondname = $_POST['secondname'];
        $firstname = $_POST['firstname'];
        $fathername = $_POST['fathername'];
        $email = $_POST['user-email'];
        $phone = $_POST['user-phone'];
        $role = $_POST['user-role'];

        // Обновление только если предоставлен новый пароль
        if (!empty($_POST['user-password'])) {
            $password = password_hash($_POST['user-password'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET secondname = :secondname, firstname = :firstname, fathername = :fathername, email = :email, phone = :phone, password = :password, role = :role WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'secondname' => $secondname,
                'firstname' => $firstname,
                'fathername' => $fathername,
                'email' => $email,
                'phone' => $phone,
                'password' => $password,
                'role' => $role,
                'id' => $id
            ]);
        } else {
            $sql = "UPDATE users SET secondname = :secondname, firstname = :firstname, fathername = :fathername, email = :email, phone = :phone, role = :role WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'secondname' => $secondname,
                'firstname' => $firstname,
                'fathername' => $fathername,
                'email' => $email,
                'phone' => $phone,
                'role' => $role,
                'id' => $id
            ]);
        }
    }

    // Обработка удаления пользователя
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
        $id = $_POST['id'];

        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    // Получение всех пользователей для отображения
    $sql = "SELECT * FROM users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're my princess - Управление Пользователями</title>
    <link rel="stylesheet" href="users-management.css">
</head>
<body>
    <header>
        <nav class="header-left">
            <ul>
                <li><a href="index.html">Главная</a></li>
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
        <h1>Управление Пользователями</h1>
        <div class="user-management">
            <h2>Добавить Нового Пользователя</h2>
            <form id="user-form" action="users-management.php" method="post">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="secondname">Фамилия:</label>
                    <input type="text" id="secondname" name="secondname" required>
                </div>
                <div class="form-group">
                    <label for="firstname">Имя:</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="fathername">Отчество:</label>
                    <input type="text" id="fathername" name="fathername" required>
                </div>
                <div class="form-group">
                    <label for="user-email">Электронная почта:</label>
                    <input type="email" id="user-email" name="user-email" required>
                </div>
                <div class="form-group">
                    <label for="user-phone">Телефон:</label>
                    <input type="tel" id="user-phone" name="user-phone" required>
                </div>
                <div class="form-group">
                    <label for="user-role">Роль:</label>
                    <select id="user-role" name="user-role" required>
                        <option value="Администратор">Администратор</option>
                        <option value="Клиент">Клиент</option>
                        <!-- Добавьте другие роли по мере необходимости -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="user-password">Пароль:</label>
                    <input type="password" id="user-password" name="user-password" required>
                </div>
                <button type="submit">Добавить Пользователя</button>
            </form>
        </div>
        <div class="user-list">
            <h2>Список Пользователей</h2>
            <ul>
                <?php foreach ($users as $user): ?>
                    <li>
                        <h3><?= htmlspecialchars($user['secondname']); ?> <?= htmlspecialchars($user['firstname']); ?> <?= htmlspecialchars($user['fathername']); ?></h3>
                        <p>Электронная почта: <?= htmlspecialchars($user['email']); ?></p>
                        <p>Телефон: <?= htmlspecialchars($user['phone']); ?></p>
                        <p>Роль: <?= htmlspecialchars($user['role']); ?></p>
                        <form action="users-management.php" method="post" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
                            <button type="submit">Удалить</button>
                        </form>
                        <button type="button" onclick="editUser(<?= htmlspecialchars(json_encode($user)); ?>)">Редактировать</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <footer>
        <p>© 2024 You're My Princess. Все права защищены.</p>
    </footer>
    <script>
        function editUser(user) {
            document.getElementById('secondname').value = user.secondname;
            document.getElementById('firstname').value = user.firstname;
            document.getElementById('fathername').value = user.fathername;
            document.getElementById('user-email').value = user.email;
            document.getElementById('user-phone').value = user.phone;
            document.getElementById('user-role').value = user.role;

            const form = document.getElementById('user-form');
            form.action = 'users-management.php';
            form.innerHTML += '<input type="hidden" name="action" value="edit">' + 
                              '<input type="hidden" name="id" value="' + user.id + '">';
            document.querySelector('button[type="submit"]').innerText = 'Обновить Пользователя';
        }
    </script>