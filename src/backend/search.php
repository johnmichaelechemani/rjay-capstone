<?php

include('db.php');

// Set headers for CORS
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$searchTerm = '';

if (isset($_GET['query'])) {
    $searchTerm = $_GET['query'];
}

// Ensure that you are escaping the search term to prevent SQL injection
$searchTerm = mysqli_real_escape_string($conn, $searchTerm);
$likeTerm = '%' . $searchTerm . '%';

// Use prepared statements to prevent SQL injection
$query = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $likeTerm, $likeTerm);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);

    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    // Free result set
    mysqli_free_result($result);

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($conn);

    // Return as JSON
    echo json_encode($products);
} else {
    // Handle query execution error
    echo json_encode(['error' => 'Query execution error']);
}
