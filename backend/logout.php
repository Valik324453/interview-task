<?php
require_once 'headers.php';

session_start();
session_destroy(); // Завершает сессию
setcookie(session_name(), '', time() - 3600, '/'); // Очищает сессионные куки
header('Content-Type: application/json');
echo json_encode(["status" => "success", "message" => "Logged out"]);

?>