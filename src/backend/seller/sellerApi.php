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
    case 'deleteProduct':
        deleteProduct();
        break;
    case 'fetchcategories':
        fetchcategories();
        break;
    case 'AddCategory':
        AddCategory();
        break;
    case 'SaveProduct':
        SaveProduct();
        break;
    case 'fetchSalesData':
        fetchSalesData();
        break;
    case 'fetchRealTimeMonthlySales':
        fetchRealTimeMonthlySales();
        break;
    case 'fetchStocks':
        fetchStocks();
        break;
    default:
        $res['error'] = true;
        $res['message'] = 'Invalid action.';
        echo json_encode($res);
        break;
}

function fetchStocks() {
    global $conn;
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($data['store_id'])) {
        echo json_encode(['error' => true, 'message' => 'store_id is missing']);
        exit;
    }
    
    $storeId = $data['store_id'];

    // Adjusted query with LEFT JOIN to include products table
    $stmt = $conn->prepare("
        SELECT SUM(inventory.quantity) AS totalQuantity
        FROM inventory
        LEFT JOIN products ON products.product_id = inventory.product_id
        WHERE products.store_id = ?
    ");
    
    $stmt->bind_param("i", $storeId);
    $stmt->execute();
    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()) {
        echo json_encode(['totalQuantity' => $row['totalQuantity']]);
    } else {
        echo json_encode(['error' => true, 'message' => 'Failed to fetch inventory status.']);
    }

    $stmt->close();
}


function fetchRealTimeMonthlySales() {
    global $conn;
    header('Content-Type: application/json'); // Ensure JSON response

    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['store_id'])) {
        echo json_encode(['error' => true, 'message' => 'store_id is missing']);
        exit;
    }

    $storeId = $data['store_id']; // Now we're sure this exists
    // var_dump($storeId); // Use for debugging only

    $currentYear = date('Y');
    $stmt = $conn->prepare("
        SELECT MONTH(order_details.delivered_date) AS saleMonth, 
               SUM(order_details.total_price_products) AS totalSales
        FROM order_details
        LEFT JOIN products ON products.product_id = order_details.product_id
        WHERE YEAR(order_details.delivered_date) = ?
          AND products.store_id = ?
          AND order_details.status = 'delivered'
        GROUP BY MONTH(order_details.delivered_date)
        ORDER BY MONTH(order_details.delivered_date)
    ");

    $stmt->bind_param("si", $currentYear, $storeId);
    $stmt->execute();
    $result = $stmt->get_result();

    $monthlySales = [];
    while($row = $result->fetch_assoc()) {
        $monthlySales[] = $row;
    }

    echo json_encode($monthlySales);
    $stmt->close();
}

function fetchSalesData() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    // Fetch the 'start', 'end', and 'store_id' from the query parameters
    $startDate = $data['start'];
    $endDate = $data['end'];
    $storeId = $data['store_id']; // Dynamic store_id
    
    // Ensure the start and end dates include the entire day
    $startDate .= " 00:00:00";
    $endDate .= " 23:59:59";

    // Update your query to include status check and use LEFT JOIN for products
    $stmt = $conn->prepare("
        SELECT SUM(order_details.total_price_products) AS totalSales 
        FROM order_details 
        LEFT JOIN products 
        ON products.product_id = order_details.product_id 
        WHERE order_details.delivered_date BETWEEN ? AND ? 
        AND products.store_id = ? 
        AND order_details.status = 'delivered'
    ");

    // Bind the parameters to the query
    $stmt->bind_param("ssi", $startDate, $endDate, $storeId);

    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Fetch the data
    $value = $result->fetch_assoc();
    
    // Assuming you want to return the sum as a part of a JSON response
    echo json_encode([
        'totalSales' => $value['totalSales'] ? $value['totalSales'] : 0
    ]);

    // Close the statement
    $stmt->close();
}

function SaveProduct() {
    global $conn;
    // Decode the JSON body from the request
    $data = json_decode(file_get_contents("php://input"), true);

    // Extract the product details and image data from the decoded data
    $selectedCategory = $data['selectedCategory'];
    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data['image'])); // This could be a base64 string or a URL
    $productName = $data['productName'];
    $productDescription = $data['productDescription'];
    $price = $data['price'];
    $shipping = $data['shipping'];
    $specifications = $data['specifications'];
    $quantity = $data['quantity'];
    $storeID = $data['store_id'];

    $stmt = $conn->prepare("INSERT INTO products (category_id, product_name, product_description, price, shipping_fee, image, store_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issddsi", $selectedCategory, $productName, $productDescription, $price, $shipping, $imageData, $storeID);

    if ($stmt->execute()) {
        $product_id = $conn->insert_id;
        // Insert specifications as before...
        $insertStmt = $conn->prepare("INSERT INTO product_specifications (product_id, spec_key, spec_value) VALUES (?, ?, ?)");
        foreach ($specifications as $spec) {
            $spec_key = $spec['Spec_key']; // Adjust these based on your actual JSON keys
            $spec_value = $spec['Spec_value']; // Adjust these based on your actual JSON keys
            $insertStmt->bind_param("iss", $product_id, $spec_key, $spec_value);
            $insertStmt->execute();
        }
        $insertStmt->close();

        $insertquant = $conn->prepare("INSERT INTO inventory (product_id, quantity) VALUES (?, ?)");
        $insertquant->bind_param("ii", $product_id, $quantity);
        $insertquant->execute();
        $insertquant->close();

        echo json_encode('Product and specifications saved successfully');
    } else {
        echo json_encode('Failed to save product');
    }

    $stmt->close();
}


function AddCategory() {
    global $conn;
    // Decode the JSON body from the request
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if the necessary data is available
    if (isset($data['category_name']) && isset($data['category_description'])) {
        $catname = $conn->real_escape_string($data['category_name']);
        $catdesc = $conn->real_escape_string($data['category_description']);

        // Prepare the query to check if the category name already exists
        $checkQuery = $conn->prepare("SELECT category_name FROM categories WHERE category_name = ?");
        $checkQuery->bind_param("s", $catname);
        $checkQuery->execute();
        $result = $checkQuery->get_result();
        $checkQuery->close();

        if ($result->num_rows > 0) {
            // Category name exists, send an error response
            http_response_code(409); // Conflict
            echo json_encode(['error' => 'Category name already exists.']);
            return;
        } 

        // Proceed with insertion since the category name does not exist
        $stmt = $conn->prepare("INSERT INTO categories (category_name, category_description) VALUES (?, ?)");
        $stmt->bind_param("ss", $catname, $catdesc);
        if (!$stmt->execute()) {
            // Insertion failed, send an error response
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to add category.']);
        } else {
            // Successfully inserted, send a success response
            http_response_code(200); // OK
            echo json_encode(['success' => 'Category added successfully.']);
        }
        $stmt->close();
    } else {
        // Missing data, send an error response
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Missing category name or description.']);
    }
}

function deleteProduct() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    $product_id = $data['id'];

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Delete from inventory table first to avoid foreign key constraint failure
        $stmt = $conn->prepare("DELETE FROM inventory WHERE product_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $stmt->close();
            echo "Product deleted from inventory table.\n";
        } else {
            // If preparation fails, throw an exception
            throw new Exception("Error preparing statement to delete from inventory table.\n");
        }

        // Delete from product_specifications table
        $stmt = $conn->prepare("DELETE FROM product_specifications WHERE product_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $stmt->close();
            echo "Product deleted from product_specifications table.\n";
        } else {
            throw new Exception("Error preparing statement to delete from product_specifications table.\n");
        }

        // Finally, delete from products table
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $stmt->close();
            echo "Product deleted from products table.\n";
        } else {
            throw new Exception("Error preparing statement to delete from products table.\n");
        }

        // If everything is fine, commit the transaction
        $conn->commit();
    } catch (Exception $e) {
        // An error occurred, roll back the transaction and print the error message
        $conn->rollback();
        echo $e->getMessage();
    }
}

function fetchcategories()
{
    global $conn;
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM categories");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $cat = [];
    while ($res = $result->fetch_assoc()) {
        $cat[] = $res;
    }

    echo json_encode($cat);
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
    var_dump($newStatus);

    if ($newStatus == 'processing') {
        $stmt = $conn->prepare("UPDATE order_details SET status = ?, estimated_delivery = ?, processing_date = ? WHERE order_detail_id = ?");
        $stmt->bind_param("sssi", $newStatus, $estdate, $UpdateDate, $id);
        $stmt->execute();
    } elseif ($newStatus == 'out_for_delivery') {
        $stmt = $conn->prepare("UPDATE order_details SET status = ?, delivery_date = ? WHERE order_detail_id = ?");
        $stmt->bind_param("ssi", $newStatus, $UpdateDate, $id);
        $stmt->execute();
    } elseif ($newStatus == 'delivered') {
        // First, update the status and delivered_date
        $stmt = $conn->prepare("UPDATE order_details SET status = ?, delivered_date = ? WHERE order_detail_id = ?");
        $stmt->bind_param("ssi", $newStatus, $UpdateDate, $id);
        $stmt->execute();
        
        // Fetch the quantity of the product ordered
        $stmt = $conn->prepare("SELECT quantity, product_id FROM order_details WHERE order_detail_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $quantity = $row['quantity'];
            $productId = $row['product_id'];
            
            // Update the inventory
            $stmt = $conn->prepare("UPDATE inventory SET quantity = quantity - ? WHERE product_id = ?");
            $stmt->bind_param("ii", $quantity, $productId);
            $stmt->execute();
        }
    } elseif ($newStatus == 'cancelled') {
        $stmt = $conn->prepare("UPDATE order_details SET status = ?, processing_date = ? WHERE order_detail_id = ?");
        $stmt->bind_param("ssi", $newStatus, $UpdateDate, $id);
        $stmt->execute();
    }

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
    p.store_id = ?
ORDER BY 
    od.order_detail_id");
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