<?php
    include('db.php');

    // Set headers for CORS
    header("Access-Control-Allow-Origin: http://localhost:5173"); // Update this to match your Vue.js development server URL
    header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    
    $res = ['error' => false];
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    switch ($action) {
        case 'ViewProductLists':
            ViewProductLists();
            break;
        case 'updateStatus':
            updateStatus();
            break;
        default:
            $res['error'] = true;
            $res['message'] = 'Invalid action.';
            echo json_encode($res);
            break;
    }

function updateStatus()
{
    global $conn;

    // Check if the content type is JSON
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['status']) && isset($data['order_id'])) {
            // Use prepared statement to prevent SQL injection
            $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");

            if ($stmt) {
                // Bind the parameters to the statement
                $stmt->bind_param("si", $data['status'], $data['order_id']);

                // Execute the statement
                $success = $stmt->execute();

                if ($success) {
                    // If the update was successful
                    echo "Order status updated successfully.";
                } else {
                    // If the execution failed
                    echo "Failed to update order status.";
                }

                // Close the statement
                $stmt->close();
            } else {
                echo "Failed to prepare the statement.";
            }
        } else {
            echo "Missing required data.";
        }
    } else {
        echo "Content type must be application/json.";
    }
}
    

function ViewProductLists()
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"), true);
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM products WHERE store_id = ?");
    $stmt->bind_param("s", $data['store_id']);
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

?>