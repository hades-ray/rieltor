<?php 
session_start(); // ОБЯЗАТЕЛЬНО в самой первой строке
require_once 'db.php'; 
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>РиэлторПро - Агентство недвижимости</title>
    <!-- Подключаем иконки -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Подключаем наш раздельный файл CSS -->
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
            <!-- Если пользователь вошел -->
            <span style="margin-left: 15px; font-weight: bold;">
                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
            <a href="logout.php" class="btn-profile" style="background: var(--accent-color);">
                <i class="fas fa-sign-out-alt"></i> Выйти
            </a>
        <?php else: ?>
            <!-- Если пользователь НЕ вошел -->
            <a href="login.php" class="btn-profile">
                <i class="fas fa-user"></i> Профиль
            </a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <!-- Hero -->
    <section class="hero">
        <h1>Найдем дом вашей мечты за 3 дня</h1>
        <p>Лучшая недвижимость в вашем регионе от проверенных собственников</p>
        <div class="search-bar">
            <select><option>Любой тип</option><option>Квартира</option><option>Дом</option></select>
            <input type="text" placeholder="Район">
            <input type="text" placeholder="Цена до">
            <button class="btn-search">Найти</button>
        </div>
    </section>

    <!-- Advantages -->
    <section class="section">
        <h2>Почему нам доверяют</h2>
        <div class="advantages-grid">
            <div class="adv-card">
                <i class="fas fa-check-double"></i>
                <h3>Чистые сделки</h3>
                <p>Все объекты проходят 3 этапа юридической проверки.</p>
            </div>
            <div class="adv-card">
                <i class="fas fa-users"></i>
                <h3>Экспертность</h3>
                <p>Наши агенты имеют стаж работы более 5 лет.</p>
            </div>
            <div class="adv-card">
                <i class="fas fa-piggy-bank"></i>
                <h3>Без переплат</h3>
                <p>Прямые цены от застройщиков без скрытых комиссий.</p>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section class="section" style="background: var(--bg-light);">
        <h2>Популярные категории</h2>
        <div class="categories-grid">
            <a href="#" class="cat-card"><i class="fas fa-building"></i>Новостройки</a>
            <a href="#" class="cat-card"><i class="fas fa-key"></i>Вторичка</a>
            <a href="#" class="cat-card"><i class="fas fa-tree"></i>Загородная</a>
            <a href="#" class="cat-card"><i class="fas fa-city"></i>Коммерческая</a>
        </div>
    </section>

    <!-- Banner -->
    <div class="promo-banner">
        <h2>Планируете продать недвижимость?</h2>
        <p>У нас самая большая база потенциальных покупателей. Продадим за 14 дней!</p>
        <a href="sell.php" class="btn-white">Оставить заявку на продажу</a>
    </div>
</main>

<footer style="background: #2d3436; color: white; padding: 30px; text-align: center;">
    <p>&copy; 2024 РиэлторПро. Все права защищены.</p>
</footer>

</body>
</html>