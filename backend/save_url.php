<?php
require_once 'headers.php';
require_once 'db.php';

session_start();
if (!isset($_SESSION['user'])) {
    die(json_encode(["status" => "error", "message" => "Not authenticated"]));
}

$data = json_decode(file_get_contents("php://input"), true);
$long_url = $data['url'];
$customBackHalf = $data['customBackHalf'];
$login = $_SESSION['user'];

// Проверяем, если URL не содержит схемы, добавляем http:// по умолчанию
if (!preg_match('/^https?:\/\//i', $long_url)) {
    $long_url = 'http://' . $long_url;
}

// Проверка, что URL валидный и содержит хотя бы одну точку
if (!filter_var($long_url, FILTER_VALIDATE_URL) || !preg_match('/\./', parse_url($long_url, PHP_URL_HOST))) {
    echo json_encode(["status" => "error", "message" => "Invalid URL"]);
    exit();
}

// Найти ID пользователя по логину
$sql = "SELECT id FROM users WHERE login = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    $stmt->close();
    $conn->close();
    exit();
}

$row = $result->fetch_assoc();
$user_id = $row['id'];
$stmt->close();

// Генерация уникального кода для короткого URL, если customBackHalf не задан
$short_code = $customBackHalf ? $customBackHalf : substr(md5($long_url . time()), 0, 6);

// Проверка уникальности short_code
$sql = "SELECT id FROM urls WHERE short_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $short_code);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Custom back-half already in use"]);
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->close();

// Вставка URL в базу данных
$sql = "INSERT INTO urls (long_url, short_code, user, transition_count) VALUES (?, ?, ?, 0)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $long_url, $short_code, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "short_url" => $short_code]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save URL"]);
}

$stmt->close();
$conn->close();
?>