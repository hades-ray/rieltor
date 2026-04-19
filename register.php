<?php
require_once 'db.php';
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $pass = $_POST['password'];
    $pass_confirm = $_POST['password_confirm'];

    if ($pass !== $pass_confirm) {
        $error = "Пароли не совпадают!";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $check->execute([$email, $username]);
        
        if ($check->rowCount() > 0) {
            $error = "Пользователь с такой почтой или логином уже существует!";
        } else {
            // Записываем пароль как есть (без хеширования)
            $stmt = $pdo->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$email, $username, $pass])) {
                $success = "Регистрация успешна! <a href='login.php'>Войдите здесь</a>";
            } else {
                $error = "Ошибка при регистрации.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style/style.css">
    <title>Регистрация</title>
</head>
<body class="auth-body">
    <div class="auth-card">
        <h2>Регистрация</h2>
        <?php if($error) echo "<div class='alert error'>$error</div>"; ?>
        <?php if($success) echo "<div class='alert success'>$success</div>"; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Подтверждение пароля</label>
                <input type="password" name="password_confirm" required>
            </div>
            <button type="submit" class="btn-submit">Зарегистрироваться</button>
        </form>
        <div class="auth-footer">
            Есть аккаунт? <a href="login.php">Авторизируйтесь</a>
        </div>
    </div>
</body>
</html>