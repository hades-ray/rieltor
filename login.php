<?php
require_once 'db.php';
session_start();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Простое сравнение строк вместо password_verify
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Сохраняем роль
        if ($user['role'] === 'admin') {
            header("Location: admin.php");   
        } else {
            header("Location: index.php");
        } exit;
    } else {
        $error = "Неверный логин или пароль!";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <title>Авторизация</title>
</head>
<body class="auth-body">
    <div class="auth-card">
        <h2>Вход в профиль</h2>
        <?php if($error) echo "<div class='alert error'>$error</div>"; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-submit">Войти</button>
        </form>
        <div class="auth-footer">
            Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a>
        </div>
    </div>
</body>
</html>