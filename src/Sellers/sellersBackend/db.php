<?php
$servername = "localhost";
$name = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $name, $password, $dbname);

if ($conn->connect_error) {
    echo "connection failed: " . $conn->connect_error;
    die("connection failed: " . $conn->connect_error);
} else {
}
