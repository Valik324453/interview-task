<?php
require_once 'headers.php';
session_start();


if (!isset($_SESSION['user'])) {
    echo json_encode(["status" => "error", "message" => "Not authenticated"]);
    exit;
}

// Дальнейшие действия с аутентифицированным пользователем
echo json_encode(["status" => "success", "user" => $_SESSION['user']]);
?>