<?php

include('db.php');

// Set headers for CORS
header("Access-Control-Allow-Origin: http://localhost:5173"); // Update this to match your Vue.js development server URL
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");



$res = ['error' => false];
$action = isset($_GET['action']) ? $_GET['action'] : '';
switch ($action) {
    case 'getProducts':
        fetchProducts();
        break;
    case 'fetchCategory':
        fetchCategory();
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
    case 'getProductsByPriceRange':
        getProductsByPriceRange();
        break;
    default:
        $res['error'] = true;
        $res['message'] = 'Invalid action.';
        echo json_encode($res);
        break;
}

function getProductsByPriceRange()
{
    global $conn;

    // Read minPrice and maxPrice from query string
    $minPrice = isset($_GET['minPrice']) ? (float) $_GET['minPrice'] : 0;
    $maxPrice = isset($_GET['maxPrice']) ? (float) $_GET['maxPrice'] : PHP_INT_MAX;

    // Prepare SQL statement to select products within the specified price range
    $stmt = $conn->prepare("SELECT 
        p.*, 
        i.inventory_id, 
        i.quantity
    FROM 
        products AS p
    LEFT JOIN 
        inventory AS i ON p.product_id = i.product_id
    WHERE 
        p.price BETWEEN ? AND ?
    ORDER BY 
        p.price ASC");

    // Bind parameters and execute the statement
    $stmt->bind_param("dd", $minPrice, $maxPrice);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        // Assuming $row['image'] contains the BLOB image data
        $row['image'] = base64_encode($row['image']);
        $products[] = $row;
    }

    // Close the connection
    $conn->close();

    // Return the products as JSON
    echo json_encode($products);
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


function fetchCategory()
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT 
    p.*, 
    c.*,
    i.quantity
FROM 
    products AS p
LEFT JOIN 
    categories AS c ON p.category_id = c.category_id
LEFT JOIN
    inventory AS i ON p.product_id = i.product_id
WHERE 
    p.category_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $stmt->close();

    $cat = [];
    while ($row = $result->fetch_assoc()) {
        $row['image'] = base64_encode($row['image']);
        $cat[] = $row;
    }

    $res = ['cat' => $cat];
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

    // Fetch products from the database
    $sql = "SELECT 
    p.*, 
    i.inventory_id, 
    i.quantity
FROM 
    products AS p
LEFT JOIN 
    inventory AS i ON p.product_id = i.product_id
ORDER BY 
    p.ratings DESC";
    $result = $conn->query($sql);

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

function fetchCartItems()
{
    global $conn;

    // Fetch products from the database
    $sql = "SELECT 
    p.*, 
    i.inventory_id, 
    i.quantity,
    ps.*
FROM 
    products AS p
LEFT JOIN 
    inventory AS i ON p.product_id = i.product_id
LEFT JOIN
    product_specifications AS ps ON i.product_id = ps.product_id
ORDER BY 
    p.ratings DESC";
    $result = $conn->query($sql);

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