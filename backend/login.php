<?php
require_once 'headers.php';
include('db.php');

session_start();

// Получаем сырые данные из тела запроса
$rawData = file_get_contents("php://input");
error_log("Raw data received: " . $rawData);

// Декодируем JSON данные
$data = json_decode($rawData, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("JSON decode error: " . json_last_error_msg());
    die(json_encode(["status" => "error", "message" => "Invalid JSON format"]));
}

if (!isset($data['login']) || !isset($data['password'])) {
    error_log("Missing login or password");
    die(json_encode(["status" => "error", "message" => "Missing login or password"]));
}

$login = $data['login'];
$password = md5($data['password']);

$sql = "SELECT * FROM users WHERE login=? AND password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $login, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['user'] = $login;
    echo json_encode(["status" => "success", "user" => $login]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid login or password"]);
}

$conn->close();
?>