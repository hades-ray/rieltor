<?php 
session_start();
require_once 'db.php'; 

// 1. Получаем список уникальных районов из базы для фильтра
$stmt_districts = $pdo->query("SELECT DISTINCT district FROM properties ORDER BY district ASC");
$all_districts = $stmt_districts->fetchAll(PDO::FETCH_COLUMN);

// 2. Получаем список уникального количества комнат для фильтра
$stmt_rooms = $pdo->query("SELECT DISTINCT rooms FROM properties ORDER BY rooms ASC");
$all_rooms = $stmt_rooms->fetchAll(PDO::FETCH_COLUMN);

// 3. Логика фильтрации
$where = [];
$params = [];

// Фильтр по цене
if (!empty($_GET['price_to'])) {
    $where[] = "price <= ?";
    $params[] = $_GET['price_to'];
}

// Фильтр по комнатам
if (!empty($_GET['rooms'])) {
    $where[] = "rooms = ?";
    $params[] = $_GET['rooms'];
}

// Фильтр по району
if (!empty($_GET['district'])) {
    $where[] = "district = ?";
    $params[] = $_GET['district'];
}

// Сборка SQL запроса
$sql = "SELECT * FROM properties";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

// Сортировка
$sort = $_GET['sort'] ?? 'new';
if ($sort == 'cheap') $sql .= " ORDER BY price ASC";
elseif ($sort == 'area') $sql .= " ORDER BY area DESC";
else $sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог недвижимости</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>

<header>
    <a href="index.php" class="logo">РиэлторПро</a>
    <nav>
        <a href="catalog.php">Каталог</a>
        <a href="services.php">Услуги</a>
        <a href="about.php">О компании</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="btn-profile" style="background: var(--accent-color);">Выйти</a>
        <?php else: ?>
            <a href="login.php" class="btn-profile">Профиль</a>
        <?php endif; ?>
    </nav>
</header>

<div class="catalog-container">
    <h1>Каталог недвижимости</h1>

    <!-- Панель фильтров и сортировки -->
    <form class="filters-panel" method="GET">
        <div>
            <label>Цена до, руб</label>
            <input type="number" name="price_to" placeholder="10 000 000" value="<?php echo $_GET['price_to'] ?? ''; ?>">
        </div>
        <div>
            <label>Количество комнат</label>
            <select name="rooms">
                <option value="">Любое</option>
                <?php foreach ($all_rooms as $room_val): ?>
                    <option value="<?= $room_val ?>" <?= (isset($_GET['rooms']) && $_GET['rooms'] == $room_val) ? 'selected' : '' ?>>
                        <?= $room_val ?>-к квартира
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>Район города</label>
            <select name="district">
                <option value="">Все районы</option>
                <?php foreach ($all_districts as $dist): ?>
                    <option value="<?= htmlspecialchars($dist) ?>" <?= (isset($_GET['district']) && $_GET['district'] == $dist) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dist) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Сортировка</label>
            <select name="sort">
                <option value="new">Сначала новые</option>
                <option value="cheap">Сначала дешевые</option>
                <option value="area">По площади</option>
            </select>
        </div>
        <div style="display: flex; gap: 5px;">
            <button type="submit" class="btn-search" style="flex-grow: 1; padding: 7px;">Применить</button>
            <a href="catalog.php" class="btn-search" style="background:#95a5a6; padding: 7px 15px; text-decoration:none; text-align:center;"><i class="fas fa-sync-alt"></i></a>
        </div>
    </form>

    <!-- Сетка объектов -->
    <div class="properties-grid">
        <?php if (empty($items)): ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 50px;">
                <i class="fas fa-search" style="font-size: 40px; color: #ccc;"></i>
                <p>К сожалению, по вашим параметрам ничего не найдено.</p>
                <a href="catalog.php" style="color: var(--primary-color);">Сбросить фильтры</a>
            </div>
        <?php endif; ?>

        <?php foreach ($items as $item): ?>
            <div class="property-card">
                <div class="property-img" style="background-image: url('img/objects/<?php echo $item['image_url']; ?>');">
                    <div class="property-price"><?= number_format($item['price'], 0, '', ' ') ?> ₽</div>
                </div>
                <div class="property-info">
                    <h3><?= htmlspecialchars($item['title']) ?></h3>
                    <span class="property-address"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($item['district']) ?></span>
                    
                    <div class="price-sq"><?= number_format($item['price'] / $item['area'], 0, '', ' ') ?> ₽ за м²</div>

                    <div class="property-stats">
                        <div class="stat-item"><i class="fas fa-door-open"></i> <?= $item['rooms'] ?> комн.</div>
                        <div class="stat-item"><i class="fas fa-expand-arrows-alt"></i> <?= $item['area'] ?> м²</div>
                        <div class="stat-item"><i class="fas fa-layer-group"></i> <?= $item['floor'] ?> эт.</div>
                    </div>
                    
                    <div class="btn-view-container">
                        <a href="property.php?id=<?= $item['id'] ?>" class="btn-submit" style="display: block; text-decoration: none; text-align: center;">Посмотреть</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer style="background: #2d3436; color: white; padding: 30px; text-align: center; margin-top: 50px;">
    <p>&copy; 2024 РиэлторПро. Все права защищены.</p>
</footer>

</body>
</html>