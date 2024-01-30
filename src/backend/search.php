<?php

include('db.php');

// Set headers for CORS
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$searchTerm = '';
global $conn;
if (isset($_GET['query'])) {
    $searchTerm = $_GET['query'];
}

// Ensure that you are escaping the search term to prevent SQL injection
$searchTerm = mysqli_real_escape_string($conn, $searchTerm);
$likeTerm = '%' . $searchTerm . '%';

// SQL Query
$query = "SELECT * FROM products WHERE name LIKE '$likeTerm' OR description LIKE '$likeTerm'";
$result = mysqli_query($conn, $query);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

// Free result set
mysqli_free_result($result);

// Close connection
mysqli_close($conn);

// Return as JSON
echo json_encode($products);
