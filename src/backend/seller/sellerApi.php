<?php

include('../db.php');

// Set headers for CORS
header("Access-Control-Allow-Origin: http://localhost:5173"); // Update this to match your Vue.js development server URL
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


$res = ['error' => false];
$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
    case 'getOrders':
        getOrders();
        break;
    case 'getProducts':
        getProducts();
        break;
    default:
        $res['error'] = true;
        $res['message'] = 'Invalid action.';
        echo json_encode($res);
        break;
}

function getProducts()
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['store_id'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT 
    p.*, 
    i.*
FROM 
    products AS p
LEFT JOIN
    inventory AS i ON  p.product_id = i.product_id
WHERE 
    p.store_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    $res = [];
    while ($row = $result->fetch_assoc()) {
        $row['image'] = base64_encode($row['image']);
        $res[] = $row;
    }
    
    echo json_encode($res);
}

function getOrders()
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['store_id'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT 
    o.*, 
    od.*,
    p.*,
    u.username
FROM 
orders AS o
LEFT JOIN 
order_details AS od ON o.order_id = od.order_id
LEFT JOIN
    products AS p ON  p.product_id = od.product_id
LEFT JOIN
    users As u ON u.user_id = o.user_id
WHERE 
    p.store_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    $res = [];
    while ($row = $result->fetch_assoc()) {
        $row['image'] = base64_encode($row['image']);
        $res[] = $row;
    }
    
    echo json_encode($res);
}