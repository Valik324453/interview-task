<?php

$servername = "db";// Используйте имя сервиса из docker-compose.yml
$username = "user";
$password = "password";
$dbname = "midnight";

// Попытка подключения к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    echo json_encode(array("status" => "error", "message" => "Connection failed: " . $conn->connect_error));
    exit;
}
?>