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
    // $id = $data['id'];
    $id = 8;

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

    $res = ['order records' => $cat];
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

    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user_id, $total_price, $status);

    // Set parameters and execute
    $user_id = 1;
    $total_price = 799;
    $status = "pending";
    $stmt->execute();

    // Capture the last inserted ID to use as a foreign key
    $order_id = $conn->insert_id;

    // Insert into the second table (profiles)
    $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $order_id, $product_id, $quantity, $price);

    // Set parameters and execute
    $product_id = 1;
    $quantity = 2;
    $price = 70;
    $stmt->execute();

    echo "New records created successfully";

    $stmt->close();
    $conn->close();
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
                us.store_name
            FROM 
                products AS p
            LEFT JOIN 
                inventory AS i ON p.product_id = i.product_id
            LEFT JOIN 
                user_store AS us ON p.store_id = us.store_id
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