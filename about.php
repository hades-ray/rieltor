<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>О компании | РиэлторПро</title>
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

<section class="page-header">
    <h1>Мы строим доверие с 2010 года</h1>
    <p>РиэлторПро — это команда экспертов, влюбленных в свое дело</p>
</section>

<section class="info-section">
    <div class="info-grid">
        <div class="info-text">
            <h2>Наша история</h2>
            <p>Агентство недвижимости «РиэлторПро» начиналось как небольшое семейное бюро. Сегодня мы — один из лидеров рынка, предоставляющий полный спектр услуг по жилой и коммерческой недвижимости.</p>
            <p>За 14 лет работы мы поняли главное: недвижимость — это не просто квадратные метры. Это новые возможности, уют и фундамент для будущего наших клиентов. Поэтому наш приоритет — абсолютная прозрачность и безопасность каждой сделки.</p>
        </div>
        <div class="info-img">
            <img src="img/about.jpg" style="width:100%; border-radius:15px;" alt="Офис">
        </div>
    </div>
</section>

<section class="stats-container">
    <div class="stat-box">
        <h3>14+</h3>
        <p>Лет на рынке</p>
    </div>
    <div class="stat-box">
        <h3>5000+</h3>
        <p>Счастливых семей</p>
    </div>
    <div class="stat-box">
        <h3>120</h3>
        <p>Опытных агентов</p>
    </div>
    <div class="stat-box">
        <h3>0</h3>
        <p>Оспоренных сделок</p>
    </div>
</section>

<section class="info-section" style="background: #fff;">
    <h2 style="text-align: center; margin-bottom: 40px;">Наши принципы</h2>
    <div class="services-list">
        <div class="service-card">
            <i class="fas fa-shield-check"></i>
            <h3>Юридическая безупречность</h3>
            <p>Каждый объект проходит проверку в 3 этапа юридическим отделом перед тем, как попасть в наш каталог.</p>
        </div>
        <div class="service-card">
            <i class="fas fa-user-friends"></i>
            <h3>Индивидуальный подход</h3>
            <p>Мы не просто ищем квартиру, мы подбираем образ жизни, учитывая инфраструктуру, соседей и перспективы района.</p>
        </div>
        <div class="service-card">
            <i class="fas fa-handshake"></i>
            <h3>Партнерство</h3>
            <p>Мы сотрудничаем с крупнейшими банками и застройщиками, обеспечивая нашим клиентам эксклюзивные скидки.</p>
        </div>
    </div>
</section>

<footer style="background: #2d3436; color: white; padding: 30px; text-align: center;">
    <p>&copy; 2024 РиэлторПро. Мы работаем для вас.</p>
</footer>

</body>
</html>