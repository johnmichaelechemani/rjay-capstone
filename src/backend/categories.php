<?php
include 'db.php';
// Set headers for CORS
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$res = ['error' => false];
global $conn;
// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT name FROM categories");
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$users = [];
while ($user = $result->fetch_assoc()) {
    $users[] = $user;
}

$res = ['categories' => $users];
echo json_encode($res);
