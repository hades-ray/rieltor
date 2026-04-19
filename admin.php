<?php
session_start();
require_once 'db.php';

// Проверка прав доступа
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Получаем все заявки из таблицы seller_requests (из главной страницы)
$stmt = $pdo->query("SELECT * FROM seller_requests ORDER BY created_at DESC");
$requests = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель администратора</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background: #f4f7f6;">

<header>
    <a href="index.php" class="logo">Админ-Панель</a>
    <nav>
        <span style="margin-right: 20px;">Привет, Администратор!</span>
        <a href="logout.php" class="btn-profile" style="background: var(--accent-color);">Выйти</a>
    </nav>
</header>

<div class="catalog-container">
    <h1><i class="fas fa-clipboard-list"></i> Заявки от продавцов</h1>
    
    <?php if (empty($requests)): ?>
        <p>Новых заявок пока нет.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя клиента</th>
                    <th>Телефон</th>
                    <th>Адрес объекта</th>
                    <th>Цена</th>
                    <th>Дата</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $req): ?>
                <tr>
                    <td>#<?php echo $req['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($req['full_name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($req['phone']); ?></td>
                    <td><?php echo htmlspecialchars($req['property_address']); ?></td>
                    <td><?php echo htmlspecialchars($req['expected_price']); ?></td>
                    <td><?php echo date('d.m.Y H:i', strtotime($req['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>