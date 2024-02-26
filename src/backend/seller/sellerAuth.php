<?php

include('../db.php');

// Set headers for CORS
header("Access-Control-Allow-Origin: http://localhost:5173"); // Update this to match your Vue.js development server URL
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


$res = ['error' => false];
$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
    case 'register':
        register();
        break;
    case 'login':
        login();
        break;
    default:
        $res['error'] = true;
        $res['message'] = 'Invalid action.';
        echo json_encode($res);
        break;
}

global $globalUser;
function register()
{
    global $conn, $res;
    $data = json_decode(file_get_contents("php://input"), true);
    // Extract data from the array
    $store_name = $data['name'];
    $store_email = $data['email'];
    $store_password = $data['password'];
    $store_contact_number = $data['contact_number'];
    $store_role = $data['role'];
    $hashed_password = password_hash($store_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO user_store (store_name, store_email, store_password, store_contact_number, store_role) VALUES (?, ?, ? , ?, ?)");
    $stmt->bind_param("sssss", $store_name, $store_email, $hashed_password, $store_contact_number, $store_role);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $res['success'] = true;
        $res['message'] = 'Registered successfully.';
    } else {
        $res['success'] = false;
        $res['message'] = 'Failed to add seller.';
    }

    $stmt->close();
    echo json_encode($res);
}
function login()
{
    session_start();
    global $conn, $res, $globalUser;
    // Use json_decode with true to get an associative array
    $post_data = json_decode(file_get_contents("php://input"), true);

    // Extract data from the array
    $store_email = $post_data['email'];
    $store_password = $post_data['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM user_store WHERE store_email=?");
    $stmt->bind_param("s", $store_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $store = $result->fetch_array();

    if ($store) {
        if (password_verify($store_password, $store['store_password'])) {
            $_SESSION['store'] = $store;
            $globalUser = $store;
            $res['success'] = true;
            $res['message'] = 'Login Success!';
            $res['store_role'] = $store['store_role'];
            $res['store'] = $store;
        } else {
            $res['error'] = true;
            $res['message'] = 'logging in';
        }
    } else {
        $res['error'] = true;
        $res['message'] = 'Invalid password';
    }
    // Encode the final response array and send as HTTP response
    echo json_encode($res);

}