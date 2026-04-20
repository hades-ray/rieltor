<?php
session_start();
require_once 'db.php';

// ПРОВЕРКА: Если пользователь не вошел, перекидываем на страницу входа
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_sale'])) {
    try {
        $sql = "INSERT INTO seller_requests (full_name, phone, property_address, expected_price) 
                VALUES (:name, :phone, :address, :price)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'name' => $_POST['userName'],
            'phone' => $_POST['userPhone'],
            'address' => $_POST['propertyAddress'],
            'price' => $_POST['propertyPrice']
        ]);
        $message = "<div class='alert success'>✅ Заявка на продажу принята! Наши эксперты свяжутся с вами.</div>";
    } catch (Exception $e) {
        $message = "<div class='alert error'>Ошибка: " . $e->getMessage() . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Продать недвижимость</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body style="background: var(--bg-light);">

<header>
    <a href="index.php" class="logo">РиэлторПро</a>
    <nav>
        <a href="catalog.php">Каталог</a>
        <a href="logout.php" class="btn-profile" style="background: var(--accent-color);">Выйти</a>
    </nav>
</header>

<div class="section">
    <div class="form-container" style="margin-top: 50px;">
        <h2>Заявка на продажу объекта</h2>
        <p style="text-align:center; color:#666; margin-bottom:30px;">
            Заполните данные о вашем объекте, и мы подготовим бесплатную оценку стоимости.
        </p>
        
        <?php echo $message; ?>

        <form method="POST">
            <div class="form-group">
                <label>Ваше имя</label>
                <!-- Можно автоматически подставить логин из сессии -->
                <input type="text" name="userName" value="<?php echo $_SESSION['username']; ?>" required>
            </div>
            <div class="form-group">
                <label>Контактный телефон</label>
                <input type="tel" name="userPhone" required placeholder="+7">
            </div>
            <div class="form-group">
                <label>Адрес объекта и краткое описание</label>
                <textarea name="propertyAddress" rows="4" required placeholder="Напр: ул. Ленина 15, кв 40. 2-комнатная, 55кв.м."></textarea>
            </div>
            <div class="form-group">
                <label>Желаемая цена (₽)</label>
                <input type="text" name="propertyPrice" placeholder="5 000 000">
            </div>
            <button type="submit" name="submit_sale" class="btn-submit">Отправить на оценку</button>
        </form>
        <br>
        <a href="index.php" style="display:block; text-align:center; color:#888;">Вернуться на главную</a>
    </div>
</div>

</body>
</html>