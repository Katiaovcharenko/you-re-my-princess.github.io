<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.html');
    exit();
}

// Database configuration
$host = 'localhost';
$db = 'salon_beauty';
$user = 'postgres';
$password = '0810';

$errors = [];
$success = false;

try {
    $dsn = "pgsql:host=$host;dbname=$db";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get current user data
    $email = $_SESSION['email'];
    $sql = "SELECT id, firstname, secondname, fathername, phone, role, profile_picture FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::PARAM_STR);

    if (!$user) {
        header('Location: logout.php');
        exit();
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate and sanitize input
        $firstname = trim($_POST['firstname'] ?? '');
        $secondname = trim($_POST['secondname'] ?? '');
        $fathername = trim($_POST['fathername'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        // Validation
        if (empty($secondname)) {
            $errors[] = "Фамилия обязательна для заполнения";
        }
        
        if (empty($firstname)) {
            $errors[] = "Имя обязательно для заполнения";
        }

        if (!empty($password) && $password !== $confirm_password) {
            $errors[] = "Пароли не совпадают";
        }

        if (!empty($password) && strlen($password) < 8) {
            $errors[] = "Пароль должен содержать минимум 8 символов";
        }

        // Handle file upload
        $profile_picture = $user['profile_picture'];
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/profile_pictures/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($file_ext), $allowed_ext)) {
                $new_filename = uniqid('profile_', true) . '.' . $file_ext;
                $destination = $upload_dir . $new_filename;

                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination)) {
                    // Delete old picture if it exists and isn't the default
                    if ($profile_picture && $profile_picture !== 'empty.jpg' && file_exists($upload_dir . $profile_picture)) {
                        unlink($upload_dir . $profile_picture);
                    }
                    $profile_picture = $new_filename;
                } else {
                    $errors[] = "Ошибка при загрузке изображения";
                }
            } else {
                $errors[] = "Недопустимый формат изображения. Разрешены: JPG, JPEG, PNG, GIF";
            }
        }

        // Update database if no errors
        if (empty($errors)) {
            $update_sql = "UPDATE users SET 
                          firstname = :firstname, 
                          secondname = :secondname, 
                          fathername = :fathername, 
                          phone = :phone, 
                          profile_picture = :profile_picture";
            
            $params = [
                ':firstname' => $firstname,
                ':secondname' => $secondname,
                ':fathername' => $fathername,
                ':phone' => $phone,
                ':profile_picture' => $profile_picture,
                ':id' => $user['id']
            ];

            // Add password to update if provided
            if (!empty($password)) {
                $update_sql .= ", password = :password";
                $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $update_sql .= " WHERE id = :id";

            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->execute($params);

            $success = true;
            // Refresh user data
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    // Escape output for security
    $firstname = htmlspecialchars($user['firstname'] ?? '');
    $secondname = htmlspecialchars($user['secondname'] ?? '');
    $fathername = htmlspecialchars($user['fathername'] ?? '');
    $phone = htmlspecialchars($user['phone'] ?? '');
    $profile_picture = htmlspecialchars($user['profile_picture'] ?? 'default.jpg');

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    die("Произошла ошибка при загрузке данных. Пожалуйста, попробуйте позже.");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're my princess - Редактирование профиля</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="profile.css">
    <style>
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
        }
        .success-message {
            color: #28a745;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .profile-picture-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .btn-save {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-save:hover {
            background-color: #218838;
        }
    </style>
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
                <?php if ($user['role'] == 'Администратор'): ?>
                    <li><a href="admin-dashboard.php">Админ панель</a></li>
                <?php endif; ?>
                <li><a href="view-appointments.php">Мои записи</a></li>
                <li><a href="profile.php">Профиль</a></li>
                <li><a href="logout.php">Выход</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-container">
        <section class="profile-section">
            <div class="profile-header">
                <h1>Редактирование профиля</h1>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message">
                    <p>Профиль успешно обновлен!</p>
                </div>
            <?php endif; ?>

            <div class="profile-content">
                <form action="edit-profile.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <img src="uploads/profile_pictures/<?= $profile_picture ?>" alt="Profile Picture" class="profile-picture-preview" id="profile-preview">
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewImage(this)">
                    </div>
                    
                    <div class="form-group">
                        <label for="secondname">Фамилия</label>
                        <input type="text" id="secondname" name="secondname" value="<?= $secondname ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="firstname">Имя</label>
                        <input type="text" id="firstname" name="firstname" value="<?= $firstname ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fathername">Отчество</label>
                        <input type="text" id="fathername" name="fathername" value="<?= $fathername ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="tel" id="phone" name="phone" value="<?= $phone ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Новый пароль (оставьте пустым, если не хотите менять)</label>
                        <input type="password" id="password" name="password">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Подтвердите новый пароль</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>
                    
                    <button type="submit" class="btn-save">Сохранить изменения</button>
                </form>
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
            © <?= date('Y') ?> You're My Princess. Все права защищены.
        </div>
    </footer>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>