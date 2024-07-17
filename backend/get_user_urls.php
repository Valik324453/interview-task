<?php
require_once 'headers.php';
require_once 'db.php';

session_start();
if (!isset($_SESSION['user'])) {
    die(json_encode(["status" => "error", "message" => "Not authenticated"]));
}

$login = $_SESSION['user'];

// Проверка на админа
if ($login === 'admin') {
    // Если админ, получить все URL-адреса всех пользователей
    $sql = "SELECT urls.long_url, urls.short_code, urls.transition_count, users.login as user_login FROM urls JOIN users ON urls.user = users.id";
    $stmt = $conn->prepare($sql);
} else {
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

    // Найти URL-ы пользователя по ID
    $sql = "SELECT long_url, short_code, transition_count FROM urls WHERE user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

$urls = [];
while ($row = $result->fetch_assoc()) {
    $urls[] = $row;
}

echo json_encode(["status" => "success", "urls" => $urls]);

$stmt->close();
$conn->close();
?>