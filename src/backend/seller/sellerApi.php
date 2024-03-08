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
    case 'EditStatus':
        EditStatus();
        break;
    case 'getSpecs':
        getSpecs();
        break;
    case 'editProductsInfo':
        editProductsInfo();
        break;
    default:
        $res['error'] = true;
        $res['message'] = 'Invalid action.';
        echo json_encode($res);
        break;
}

function editProductsInfo() {
    global $conn;
    
    // Decode the JSON object from the request
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Extract product details from the data
    $product_id = $data['product_id'];
    $product_name = $data['product_name'];
    $product_price = $data['product_price'];
    $product_description = $data['product_description'];
    $shipping_fee = $data['shipping_fee'];
    $quantity = $data['quantity'];
    $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['image']));
    $specifications = $data['specifications'];

    // Begin transaction for atomicity
    $conn->begin_transaction();
    
    try {
        // Update product information in the products table
        $stmt = $conn->prepare("UPDATE products SET product_name = ?, price = ?, product_description = ?, shipping_fee = ?, image = ? WHERE product_id = ?");
        $stmt->bind_param("sdsdsi", $product_name, $product_price, $product_description, $shipping_fee, $image, $product_id);
        $stmt->execute();
        $stmt->close();
    
        // Update quantity in the inventory table
        $stmt = $conn->prepare("UPDATE inventory SET quantity = ? WHERE product_id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();
        $stmt->close();
    
        // Delete existing specifications for the product
        $stmt = $conn->prepare("DELETE FROM product_specifications WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->close();
    
        // Insert new/updated specifications
        $insertStmt = $conn->prepare("INSERT INTO product_specifications (product_id, spec_key, spec_value) VALUES (?, ?, ?)");
        foreach ($specifications as $spec) {
            $spec_key = $spec['spec_key'];
            $spec_value = $spec['spec_value'];
            $insertStmt->bind_param("iss", $product_id, $spec_key, $spec_value);
            $insertStmt->execute();
        }
        $insertStmt->close();
    
        // Commit the transaction
        $conn->commit();
        echo "Product, inventory, and specifications updated successfully.";
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        echo "Error occurred: " . $e->getMessage();
    }

    // Consider when to close the connection based on your application's architecture
    // $conn->close();
}

function getSpecs()
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM product_specifications WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    $res = [];
    while ($row = $result->fetch_assoc()) {
        $res[] = $row;
    }
    
    echo json_encode($res);
}

function EditStatus()
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $newStatus = $data['status'];
    $estdate = $data['estimated_delivery'];
    $UpdateDate = $data['date'];

    // Use '==' for comparison (or '===' for strict comparison)
    if ($newStatus == 'processing') {
        // Assuming 'delivered_date' is the correct column name when the status is 'delivered'
        $stmt = $conn->prepare("UPDATE order_details SET status = ?, estimated_delivery = ?, processing_date = ? WHERE order_detail_id = ?");
        $stmt->bind_param("sssi", $newStatus, $estdate, $UpdateDate, $id);
    } elseif ($newStatus == 'out_for_delivery') {
        // Adjust accordingly if 'delivery_date' or another column should be used here
        $stmt = $conn->prepare("UPDATE order_details SET status = ?, delivery_date = ? WHERE order_detail_id = ?");
        $stmt->bind_param("ssi", $newStatus, $UpdateDate, $id);
    } elseif ($newStatus == 'delivered') {
        $stmt = $conn->prepare("UPDATE order_details SET status = ?, delivered_date = ? WHERE order_detail_id = ?");
        $stmt->bind_param("ssi", $newStatus, $UpdateDate, $id);
    }

    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "Order status updated successfully.";
    } else {
        echo "No order was updated. Please check your input.";
    }

    $stmt->close();
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
    od.*, 
    o.*,
    p.*,
    u.*
FROM 
order_details AS od
LEFT JOIN 
orders AS o ON o.order_id = od.order_id
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