<?php

include('headers.php');
include('db.php');

// Получаем путь из URL
$short_code = isset($_GET['url']) ? $_GET['url'] : '';

// Проверяем наличие соответствующего длинного URL в базе данных
$sql = "SELECT long_url, transition_count FROM urls WHERE short_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $short_code);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($long_url, $transition_count);

if ($stmt->num_rows > 0) {
    $stmt->fetch();

    // Увеличиваем счетчик переходов
    $new_transition_count = $transition_count + 1;
    $update_sql = "UPDATE urls SET transition_count = ? WHERE short_code = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("is", $new_transition_count, $short_code);
    $update_stmt->execute();

    // Выполняем редирект
    header("Location: " . $long_url);
    exit();
} else {
    // Если URL не найден, можно показать страницу 404 или другую логику
    header("HTTP/1.0 404 Not Found");
    echo "Страница не найдена.";
}

$stmt->close();
$conn->close();