<?php
session_start(); // Находим текущую сессию
session_unset(); // Удаляем все переменные сессии
session_destroy(); // Уничтожаем сессию

// Перенаправляем на главную страницу
header("Location: index.php");
exit;
?>