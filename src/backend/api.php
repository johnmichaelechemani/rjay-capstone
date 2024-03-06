<?php

include('db.php');

// Set headers for CORS
header("Access-Control-Allow-Origin: http://localhost:5173"); // Update this to match your Vue.js development server URL
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");



$res = ['error' => false];
$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
    case 'getProducts':
        fetchProducts();
        break;
    case 'addCart':
        addCart();
        break;
    case 'fetchCartItems':
        fetchCartItems();
        break;
    case 'getProductSpecifications':
        fetchSpecs();
        break;
    case 'CheckoutOrder':
        CheckoutOrder();
        break;
    case 'getTrackOrder':
        trackOrder();
        break;
    case 'deleteCartItem':
        deleteCartItem();
        break;
    case 'getStorename':
        getStorename();
        break;
    case 'fetchcategories':
        fetchcategories();
        break;
    default:
        $res['error'] = true;
        $res['message'] = 'Invalid action.';
        echo json_encode($res);
        break;
}

function fetchcategories()
{
    global $conn;
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $users = [];
    while ($user = $result->fetch_assoc()) {
        $users[] = $user;
    }

    $res = ['categories' => $users];
    echo json_encode($res);
}

//fetching user store
function getStorename()
{
    global $conn;
    $role = 'seller';
    $stmt = $conn->prepare("SELECT * FROM user_store WHERE store_role = ?");
    $stmt->bind_param("s", $role);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    $specs = [];
    while ($row = $result->fetch_assoc()) {
        $specs[] = $row;
    }

    echo json_encode($specs);
}

function trackOrder()
{

    global $conn;

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT 
    p.*, 
    o.*,
    od.*
FROM 
    products AS p
LEFT JOIN 
order_details AS od ON p.product_id = od.product_id
LEFT JOIN
    orders AS o ON od.order_id = o.order_id
WHERE 
    o.user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    $cat = [];
    while ($row = $result->fetch_assoc()) {
        $row['image'] = base64_encode($row['image']);
        $cat[] = $row;
    }

    $res['order_records'] = $cat;
    echo json_encode($res);
}

function deleteCartItem()
{
    global $conn, $res;

    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        $id = $data['id'];
        mysqli_query($conn, "DELETE FROM cart_items WHERE cart_item_id=$id");
        $res['success'] = true;
        $res['message'] = 'Delete successful.';
    } else {
        $res['success'] = false;
        $res['message'] = 'ID not provided.';
    }

    echo json_encode($res);
}

function CheckoutOrder()
{
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);

    // Assuming the initial insert into 'orders' is correct and does not involve the payment method
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, item) VALUES (?, ?, ?)");
    $user_id = $data['user_id'];
    $total_price = $data['total_price'];
    $item = $data['item'];
    // Assuming user_id is an integer, total_price is a string that needs conversion, and item is a string
    $stmt->bind_param("ids", $user_id, $total_price, $item);
    $stmt->execute();

    $order_id = $conn->insert_id;
    $payment = $data['payment_method']; // This is used in the next insert

    $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, total_price_products, payment_method) VALUES (?, ?, ?, ?, ?)");

    // Bind parameters just once outside the loop
    $stmt->bind_param("iiids", $order_id, $product_id, $quantity, $price, $payment);

    foreach ($data['product_id'] as $key => $product_id) {
        $quantity = $data['quantity'][$key];
        $price = $data['price'][$key];

        // No need to re-bind; just execute
        $stmt->execute();
        
        // Assuming deletion from cart_items is as intended
        $deleteStmt = $conn->prepare("DELETE FROM cart_items WHERE product_id = ?");
        $deleteStmt->bind_param("i", $product_id);
        $deleteStmt->execute();
        $deleteStmt->close();
    }

    echo "New records created successfully";

    $stmt->close();
    $conn->close();
}

function generateUniqueOrderNumber($conn) {
    do {
        // Generate random 11-digit number
        $order_number = rand(10000, 99999);
        // Check if it already exists
        $query = "SELECT order_number FROM order_details WHERE order_number = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $order_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
    } while ($exists); // Keep generating until unique

    return $order_number;
}

function fetchSpecs()
{
    global $conn;
    // Use the $_GET superglobal to read parameters from the query string
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Basic validation and conversion to integer

    if ($id <= 0) {
        echo json_encode(['error' => true, 'message' => 'Invalid ID.']);
        return;
    }

    $stmt = $conn->prepare("SELECT * FROM product_specifications WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    $specs = [];
    while ($row = $result->fetch_assoc()) {
        $specs[] = $row;
    }

    $res = ['specifications' => $specs];
    echo json_encode($res);
}

function addCart()
{
    global $conn, $res;
    $data = json_decode(file_get_contents("php://input"), true);
    // Extract data from the array
    $product_id = $data['product_id'];
    $quantity = $data['quantity'];
    $cart_id = $data['cart_id'];

    $stmt = $conn->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $cart_id, $product_id, $quantity);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $res['success'] = true;
        $res['message'] = 'Product added successfully.';
    } else {
        $res['success'] = false;
        $res['message'] = 'Failed to add product.';
    }
    $stmt->close();
    echo json_encode($res);
}


function fetchProducts()
{
    global $conn;
    $storeName = 'seller';
    // Prepare the SQL query with a placeholder for the store_name
    $sql = "SELECT 
                p.*, 
                i.inventory_id, 
                i.quantity,
                us.store_name,
                c.category_name
            FROM 
                products AS p
            LEFT JOIN 
                inventory AS i ON p.product_id = i.product_id
            LEFT JOIN 
                user_store AS us ON p.store_id = us.store_id
            LEFT JOIN 
                categories AS c ON p.category_id = c.category_id
            WHERE 
                us.store_role = ?
            ORDER BY 
                p.ratings DESC";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the $storeName variable to the placeholder in the SQL query
    $stmt->bind_param("s", $storeName);

    // Execute the query
    $stmt->execute();

    // Get the result of the query
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        // Assuming $row['image'] contains the BLOB image data
        // Encode the image data to base64
        if(isset($row['image'])) {
            $row['image'] = base64_encode($row['image']);
        }
        $products[] = $row;
    }

    // Close the statement
    $stmt->close();

    // It's generally not a good idea to close the global connection within a function
    // as it might be used elsewhere. Consider removing the $conn->close();

    echo json_encode($products);
}

function fetchCartItems()
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['cart_id'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT 
    p.*, 
    ci.*
   
FROM 
    products AS p
LEFT JOIN 
    cart_items AS ci ON  ci.product_id = p.product_id
WHERE 
    ci.cart_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    // Fetch data as an associative array
    $products = [];
    while ($row = $result->fetch_assoc()) {
        // Assuming $row['image'] contains the BLOB image data
        $row['image'] = base64_encode($row['image']);
        $products[] = $row;
    }

    // Close the connection
    $conn->close();

    echo json_encode($products);
}