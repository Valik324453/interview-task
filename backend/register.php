<?php
include('headers.php');
include('db.php');
session_start(); // Запуск сессии

$data = json_decode(file_get_contents("php://input"), true);

$first_name = $data['first_name'];
$last_name = $data['last_name'];
$login = $data['login'];
$password = md5($data['password']); // Использование md5 не рекомендуется для реальных приложений

// Проверка, существует ли логин
$sql_check = "SELECT * FROM users WHERE login = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $login);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Login already exists"]);
} else {
    $sql_insert = "INSERT INTO users (first_name, last_name, login, password) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssss", $first_name, $last_name, $login, $password);

    if ($stmt_insert->execute()) {
        $_SESSION['user'] = $login; // Установка сессии
        echo json_encode(["status" => "success", "user" => $login]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
    $stmt_insert->close();
}

$stmt_check->close();
$conn->close();
?>