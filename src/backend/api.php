<?php

include('db.php');

// Set headers for CORS
header("Access-Control-Allow-Origin: http://localhost:5173"); // Update this to match your Vue.js development server URL
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

function fetchProducts()
{
    global $conn;

    // Fetch products from the database
    $sql = "SELECT * FROM products ORDER BY ratings DESC"; // Modify this query based on your database structure
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

    return $products;
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode(fetchProducts());
