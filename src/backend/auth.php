<?php

include('db.php');

// Set headers for CORS
header("Access-Control-Allow-Origin: http://localhost:5173"); // Update this to match your Vue.js development server URL
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$res = ['error' => false];

global $conn, $res;
$data = json_decode(file_get_contents("php://input"), true);
// Extract data from the array
$username = $data['name'];
$email = $data['email'];
$password = $data['password'];
$contact_number = $data['contact_number'];
$role = $data['role'];

$stmt = $conn->prepare("INSERT INTO users (username, email, password, contact_number, role) VALUES (?, ?, ? , ?, ?)");
$stmt->bind_param("sssss", $username, $email, $password, $contact_number, $role);
$stmt->execute();
if ($stmt->affected_rows > 0) {
    $res['success'] = true;
    $res['message'] = 'Registered successfully.';
} else {
    $res['success'] = false;
    $res['message'] = 'Failed to add user.';
}
$stmt->close();
echo json_encode($res);
