<?php 
session_start();
require_once 'db.php'; 

// Логика фильтрации (базовый пример)
$where = [];
$params = [];

if (!empty($_GET['price_to'])) {
    $where[] = "price <= ?";
    $params[] = $_GET['price_to'];
}
if (!empty($_GET['rooms'])) {
    $where[] = "rooms = ?";
    $params[] = $_GET['rooms'];
}

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
            <label>Комнат</label>
            <select name="rooms">
                <option value="">Любое</option>
                <option value="1">1-к квартира</option>
                <option value="2">2-к квартира</option>
                <option value="3">3-к квартира</option>
            </select>
        </div>
        <div>
            <label>Район</label>
            <select name="district">
                <option value="">Все районы</option>
                <option>Центральный</option>
                <option>Приморский</option>
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
        <button type="submit" class="btn-search" style="padding: 10px;">Применить</button>
    </form>

    <!-- Сетка объектов -->
    <div class="properties-grid">
        <?php if (empty($items)): ?>
            <p>Ничего не найдено по вашим параметрам.</p>
        <?php endif; ?>

        <?php foreach ($items as $item): ?>
            <div class="property-card">
                <div class="property-img" style="background-image: url('img/objects/<?php echo $item['image_url']; ?>');">
                    <div class="property-price"><?php echo number_format($item['price'], 0, '', ' '); ?> ₽</div>
                </div>
                <div class="property-info">
                    <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    <span class="property-address"><i class="fas fa-map-marker-alt"></i> <?php echo $item['district']; ?> район</span>

                    <div class="price-sq">
                        <?php echo number_format($item['price'] / $item['area'], 0, '', ' '); ?> ₽ за м²
                    </div>

                    <div class="property-stats">
                        <div class="stat-item"><i class="fas fa-door-open"></i> <?php echo $item['rooms']; ?> комн.</div>
                        <div class="stat-item"><i class="fas fa-expand-arrows-alt"></i> <?php echo $item['area']; ?> м²</div>
                        <div class="stat-item"><i class="fas fa-layer-group"></i> <?php echo $item['floor']; ?> эт.</div>
                    </div>

                    <!-- Контейнер, который прижмет кнопку к низу -->
                    <div class="btn-view-container">
                        <a href="property.php?id=<?php echo $item['id']; ?>" class="btn-submit" style="display: block; text-decoration: none; text-align: center;">
                            Посмотреть
                        </a>
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