<?php
session_start();
require_once 'db.php';

// 1. Проверка прав
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. ОБРАБОТКА СОЗДАНИЯ ОБЪЕКТА
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_property'])) {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $rooms = $_POST['rooms'];
    $area = $_POST['area'];
    $district = $_POST['district'];
    $house_type = $_POST['house_type'];
    $renovation = $_POST['renovation'];
    $floor = $_POST['floor'];
    $description = $_POST['description'];

    // Работа с картинкой
    $image_name = 'default.jpg'; // на случай если не загрузят
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . '_' . uniqid() . '.' . $ext; // Уникальное имя
        $target = 'img/objects/' . $image_name;
        
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $sql = "INSERT INTO properties (title, price, rooms, area, district, house_type, renovation, floor, description, image_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title, $price, $rooms, $area, $district, $house_type, $renovation, $floor, $description, $image_name]);
    
    header("Location: admin.php?view=properties&success=1");
    exit;
}

// 3. Обработка смены статуса (для заявок)
if (isset($_GET['action']) && isset($_GET['id']) && isset($_GET['type'])) {
    $id = (int)$_GET['id'];
    $type = $_GET['type'];
    $action = $_GET['action'];
    $newStatus = ($action === 'approve') ? 'Одобрено' : 'Отменено';
    $table = ($type === 'seller') ? 'seller_requests' : 'property_requests';
    
    $stmt = $pdo->prepare("UPDATE $table SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $id]);
    header("Location: admin.php?view=" . ($_GET['view'] ?? 'sellers'));
    exit;
}

// 4. Получение данных для текущего вида
$view = $_GET['view'] ?? 'sellers';

if ($view === 'buyers') {
    $stmt = $pdo->query("SELECT pr.*, p.title as property_name FROM property_requests pr JOIN properties p ON pr.property_id = p.id ORDER BY pr.created_at DESC");
    $data = $stmt->fetchAll();
} elseif ($view === 'properties') {
    $stmt = $pdo->query("SELECT * FROM properties ORDER BY created_at DESC");
    $data = $stmt->fetchAll();
} else {
    $stmt = $pdo->query("SELECT * FROM seller_requests ORDER BY created_at DESC");
    $data = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
    <style>
        body { display: flex; min-height: 100vh; background: #f0f2f5; margin: 0; }
        .sidebar { width: 260px; background: #2c3e50; color: white; position: fixed; height: 100%; }
        .sidebar-header { padding: 20px; text-align: center; background: #1a252f; font-weight: bold; }
        .sidebar-menu a { display: block; padding: 15px 25px; color: #bdc3c7; text-decoration: none; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #34495e; color: white; border-left: 4px solid var(--accent-color); }
        
        .admin-main { margin-left: 260px; flex-grow: 1; padding: 30px; }
        
        /* Модальное окно */
        .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:1000; justify-content:center; align-items:center; }
        .modal-content { background:white; padding:30px; border-radius:15px; width:600px; max-height: 90vh; overflow-y: auto; }
        
        .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; }
        .status-processing { background: #fef9e7; color: #f1c40f; }
        .status-approved { background: #e9f7ef; color: #27ae60; }
        .status-rejected { background: #fdedec; color: #e74c3c; }

        .admin-table { width: 100%; background: white; border-collapse: collapse; border-radius: 10px; overflow: hidden; }
        .admin-table th, .admin-table td { padding: 12px 15px; border-bottom: 1px solid #eee; text-align: left; }
        .admin-table th { background: #f8f9fa; color: #888; font-size: 12px; }
        
        .img-preview { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-header">Elite Estate ADMIN</div>
    <nav class="sidebar-menu">
        <a href="admin.php?view=sellers" class="<?= $view === 'sellers' ? 'active' : '' ?>"><i class="fas fa-file-invoice"></i> Заявки продавцов</a>
        <a href="admin.php?view=buyers" class="<?= $view === 'buyers' ? 'active' : '' ?>"><i class="fas fa-shopping-cart"></i> Заявки покупателей</a>
        <a href="admin.php?view=properties" class="<?= $view === 'properties' ? 'active' : '' ?>"><i class="fas fa-building"></i> Объекты</a>
        <a href="index.php" style="margin-top:20px;"><i class="fas fa-home"></i> На сайт</a>
        <a href="logout.php" style="color:#e74c3c;"><i class="fas fa-sign-out-alt"></i> Выйти</a>
    </nav>
</aside>

<main class="admin-main">
    <div style="display:flex; justify-content:space-between; margin-bottom:20px;">
        <h1>
            <?php 
                if($view === 'properties') echo "Управление объектами";
                elseif($view === 'buyers') echo "Заявки покупателей";
                else echo "Заявки продавцов";
            ?>
        </h1>
        <?php if($view === 'properties'): ?>
            <button onclick="document.getElementById('addModal').style.display='flex'" class="btn-search" style="border-radius:5px;">+ Добавить объект</button>
        <?php endif; ?>
    </div>

    <table class="admin-table">
        <?php if($view === 'properties'): ?>
            <thead>
                <tr>
                    <th>Фото</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Район</th>
                    <th>Характеристики</th>
                    <th>Дата</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $item): ?>
                <tr>
                    <td><img src="img/objects/<?= $item['image_url'] ?>" class="img-preview"></td>
                    <td><strong><?= htmlspecialchars($item['title']) ?></strong></td>
                    <td><?= number_format($item['price'], 0, '', ' ') ?> ₽</td>
                    <td><?= $item['district'] ?></td>
                    <td><?= $item['rooms'] ?>к, <?= $item['area'] ?>м², <?= $item['floor'] ?> эт.</td>
                    <td><?= date('d.m.Y', strtotime($item['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        <?php else: ?>
            <!-- Таблицы для заявок (как в предыдущем шаге) -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Клиент</th>
                    <th>Телефон</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                <tr>
                    <td>#<?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['full_name'] ?? $row['user_name']) ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><span class="status-badge status-<?= ($row['status'] === 'Одобрено' ? 'approved' : ($row['status'] === 'Отменено' ? 'rejected' : 'processing')) ?>"><?= $row['status'] ?></span></td>
                    <td>
                        <?php if($row['status'] === 'В обработке'): ?>
                            <a href="admin.php?view=<?= $view ?>&action=approve&id=<?= $row['id'] ?>&type=<?= ($view === 'buyers' ? 'buyer' : 'seller') ?>" class="btn-action btn-approve"><i class="fas fa-check"></i></a>
                            <a href="admin.php?view=<?= $view ?>&action=reject&id=<?= $row['id'] ?>&type=<?= ($view === 'buyers' ? 'buyer' : 'seller') ?>" class="btn-action btn-reject"><i class="fas fa-times"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        <?php endif; ?>
    </table>
</main>

<!-- МОДАЛЬНОЕ ОКНО СОЗДАНИЯ ОБЪЕКТА -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span onclick="document.getElementById('addModal').style.display='none'" style="float:right; cursor:pointer;">&times;</span>
        <h2>Добавить новый объект</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Название объекта</label>
                <input type="text" name="title" required placeholder="Напр: 2-к квартира в центре">
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <div class="form-group"><label>Цена (₽)</label><input type="number" name="price" required></div>
                <div class="form-group"><label>Район</label><input type="text" name="district" required></div>
                <div class="form-group"><label>Кол-во комнат</label><input type="number" name="rooms" required></div>
                <div class="form-group"><label>Площадь (м²)</label><input type="number" name="area" required></div>
                <div class="form-group"><label>Этаж</label><input type="number" name="floor" required></div>
                <div class="form-group"><label>Тип дома</label><input type="text" name="house_type" placeholder="Кирпичный"></div>
            </div>
            <div class="form-group">
                <label>Ремонт</label>
                <select name="renovation">
                    <option>Евроремонт</option>
                    <option>Косметический</option>
                    <option>Дизайнерский</option>
                    <option>Требует ремонта</option>
                </select>
            </div>
            <div class="form-group">
                <label>Описание</label>
                <textarea name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Фото объекта</label>
                <input type="file" name="image" accept="image/*" required>
            </div>
            <button type="submit" name="add_property" class="btn-submit">Создать объект</button>
        </form>
    </div>
</div>

</body>
</html>