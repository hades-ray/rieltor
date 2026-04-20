<?php
session_start();
require_once 'db.php';

$id = $_GET['id'] ?? 0;

// Получаем данные объекта
$stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->execute([$id]);
$property = $stmt->fetch();

if (!$property) {
    die("Объект не найден.");
}

$message_sent = "";

// Обработка заявки
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_request'])) {
    $stmt_req = $pdo->prepare("INSERT INTO property_requests (property_id, user_name, phone, message) VALUES (?, ?, ?, ?)");
    $stmt_req->execute([$id, $_POST['client_name'], $_POST['client_phone'], $_POST['client_msg']]);
    $message_sent = "Ваша заявка принята! Менеджер свяжется с вами.";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo $property['title']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
    <style>
        .property-page { padding: 40px 10%; display: grid; grid-template-columns: 1fr 350px; gap: 40px; }
        .main-img { width: 100%; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .price-tag { font-size: 32px; color: var(--accent-color); font-weight: 800; margin: 20px 0; }
        .spec-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; background: #fdfdfd; padding: 25px; border-radius: 15px; border: 1px solid #eee; }
        .spec-item b { display: block; font-size: 18px; color: var(--primary-color); }
        .spec-item span { color: #888; font-size: 14px; }
        .sidebar-card { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); position: sticky; top: 100px; height: fit-content; }
        /* Модальное окно */
        #modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; justify-content:center; align-items:center; }
        .modal-content { background:white; padding:30px; border-radius:15px; width:400px; position:relative; }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="logo">РиэлторПро</a>
    <nav>
        <a href="catalog.php">Назад в каталог</a>
    </nav>
</header>

<main class="property-page">
    <div class="content">
        <img src="img/objects/<?php echo $property['image_url']; ?>" class="main-img" alt="Фото">
        
        <h1><?php echo htmlspecialchars($property['title']); ?></h1>
        <p style="color: #777;"><i class="fas fa-map-marker-alt"></i> <?php echo $property['district']; ?> район</p>
        
        <div class="price-tag"><?php echo number_format($property['price'], 0, '', ' '); ?> ₽</div>

        <div class="spec-grid">
            <div class="spec-item"><span>Комнат</span><b><?php echo $property['rooms']; ?></b></div>
            <div class="spec-item"><span>Площадь</span><b><?php echo $property['area']; ?> м²</b></div>
            <div class="spec-item"><span>Этаж</span><b><?php echo $property['floor']; ?></b></div>
            <div class="spec-item"><span>Тип дома</span><b><?php echo $property['house_type']; ?></b></div>
            <div class="spec-item"><span>Ремонт</span><b><?php echo $property['renovation']; ?></b></div>
            <div class="spec-item"><span>Цена за м²</span><b><?php echo number_format($property['price']/$property['area'], 0, '', ' '); ?> ₽</b></div>
        </div>

        <div style="margin-top: 30px;">
            <h3>Описание</h3>
            <p><?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
        </div>
    </div>

    <aside>
        <div class="sidebar-card">
            <h3>Заинтересовал объект?</h3>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Если вошел — показываем кнопку открытия модального окна -->
                <p>Наш эксперт ответит на все вопросы и организует показ.</p>
                <button onclick="document.getElementById('modal').style.display='flex'" class="btn-submit">Оставить заявку</button>
            <?php else: ?>
                <!-- Если НЕ вошел — отправляем на регистрацию/вход -->
                <p style="color: #e74c3c; font-weight: bold;">Чтобы оставить заявку на просмотр, пожалуйста, авторизуйтесь.</p>
                <a href="login.php" class="btn-submit" style="display:block; text-align:center; text-decoration:none;">Войти в профиль</a>
                <p style="font-size:12px; text-align:center; margin-top:10px;">Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p>
            <?php endif; ?>

            <?php if($message_sent) echo "<p style='color:green; margin-top:15px;'>$message_sent</p>"; ?>
        </div>
    </aside>
</main>

<!-- Всплывающая форма -->
<div id="modal">
    <div class="modal-content">
        <span onclick="document.getElementById('modal').style.display='none'" style="position:absolute; right:15px; cursor:pointer;">&times;</span>
        <h2 style="margin-top:0">Заявка на просмотр</h2>
        <form method="POST">
            <div class="form-group">
                <label>Ваше имя</label>
                <input type="text" name="client_name" required>
            </div>
            <div class="form-group">
                <label>Телефон</label>
                <input type="tel" name="client_phone" required placeholder="+7">
            </div>
            <div class="form-group">
                <label>Ваш комментарий</label>
                <textarea name="client_msg" rows="3">Хочу посмотреть объект: <?php echo $property['title']; ?></textarea>
            </div>
            <button type="submit" name="send_request" class="btn-submit">Отправить эксперту</button>
        </form>
    </div>
</div>

</body>
</html>