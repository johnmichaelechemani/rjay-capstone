<?php

include('db.php');

// Set headers for CORS
header("Access-Control-Allow-Origin: http://localhost:5173"); // Update this to match your Vue.js development server URL
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

function fetchSearch()
{
    global $conn;

    // Fetch products from the database
    $data = json_decode(file_get_contents("php://input"), true);
    $pname = $data['query'];

    // LIKE pattern for partial matches
    $likePattern = '%' . $pname . '%';

    // First, try to find matches using LIKE for partial matches
    $sql = "SELECT * 
    FROM 
        products AS p
    LEFT JOIN
        categories AS c on p.category_id = c.category_id
    WHERE 
        product_name LIKE ? OR product_description LIKE ? OR c.category_name LIKE ? OR c.category_description LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $likePattern, $likePattern, $likePattern, $likePattern);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $row['image'] = base64_encode($row['image']);
        $products[] = $row;
    }

    // If no results from LIKE and you want to try a more fuzzy approach with SOUNDEX
    if (empty($products)) {
        $sql = "SELECT * FROM products WHERE SOUNDEX(product_name) = SOUNDEX(?) OR SOUNDEX(product_description) = SOUNDEX(?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $pname, $pname);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $row['image'] = base64_encode($row['image']);
            $products[] = $row;
        }
    }

    $stmt->close();
    return $products;
}

header('Content-Type: application/json');
echo json_encode(fetchSearch());