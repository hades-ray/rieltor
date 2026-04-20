<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Наши услуги | РиэлторПро</title>
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

<section class="page-header"">
    <h1>Профессиональные услуги в недвижимости</h1>
    <p>От консультации до получения ключей — мы рядом на каждом этапе</p>
</section>

<section class="info-section">
    <div class="services-list">
        <div class="service-card">
            <i class="fas fa-home"></i>
            <h3>Покупка жилья</h3>
            <p>Поиск идеального варианта, организация просмотров и торги с продавцом для получения лучшей цены.</p>
        </div>
        <div class="service-card">
            <i class="fas fa-tags"></i>
            <h3>Продажа недвижимости</h3>
            <p>Профессиональная фотосъемка, оценка объекта и агрессивный маркетинг для быстрой продажи.</p>
        </div>
        <div class="service-card">
            <i class="fas fa-percentage"></i>
            <h3>Ипотечный брокеридж</h3>
            <p>Помощь в одобрении ипотеки даже в сложных случаях. Скидки на процентную ставку от банков-партнеров.</p>
        </div>
        <div class="service-card">
            <i class="fas fa-gavel"></i>
            <h3>Юридическое сопровождение</h3>
            <p>Полная проверка документов, составление договоров и сопровождение в Росреестре.</p>
        </div>
        <div class="service-card">
            <i class="fas fa-city"></i>
            <h3>Коммерческая недвижимость</h3>
            <p>Подбор офисов, складов и торговых площадей с расчетом окупаемости инвестиций.</p>
        </div>
        <div class="service-card">
            <i class="fas fa-search-dollar"></i>
            <h3>Оценка объектов</h3>
            <p>Независимая экспертиза рыночной стоимости вашей недвижимости для продажи или залога.</p>
        </div>
    </div>
</section>

<div class="promo-banner" style="margin: 0 10% 60px 10%;">
    <h2>Нужна консультация эксперта?</h2>
    <p>Это бесплатно! Оставьте заявку, и мы перезвоним вам в течение 15 минут.</p>
    <a href="sell.php" class="btn-white">Записаться на консультацию</a>
</div>

<footer style="background: #2d3436; color: white; padding: 30px; text-align: center;">
    <p>&copy; 2024 РиэлторПро. Эксперты вашего спокойствия.</p>
</footer>

</body>
</html>