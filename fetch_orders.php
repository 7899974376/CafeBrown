<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// Database connection parameters
$host = 'localhost'; // Change if your database is hosted elsewhere
$db = 'cbrown'; // Replace with your database name
$user = 'root'; // Replace with your database username
$pass = 'aniket'; // Replace with your database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection error: " . $conn->connect_error);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed. Check server logs for details.']);
    exit();
}

// Get the table number from the request body
$input = file_get_contents('php://input');
if (empty($input)) {
    error_log("Empty request body received");
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request: empty body']);
    exit();
}

$data = json_decode($input);
if (json_last_error() !== JSON_ERROR_NONE || !isset($data->table_no)) {
    error_log("Invalid JSON received: " . json_last_error_msg());
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request: malformed JSON']);
    exit();
}

$table_no = intval($data->table_no);
if ($table_no <= 0) {
    error_log("Invalid table number received: " . $data->table_no);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid table number']);
    exit();
}

// Prepare the SQL statement
$query = "SELECT o.order_id, oi.item_name, oi.quantity, oi.price_per_item, o.confirm_order, o.customer_name, o.customer_phone, o.customer_email 
          FROM orders o 
          JOIN order_items oi ON o.order_id = oi.order_id 
          WHERE o.table_no = ? AND o.payment = 'No'";

error_log("Executing query: " . $query);
$stmt = $conn->prepare($query);

if (!$stmt) {
    $error = $conn->error;
    error_log("SQL prepare error: " . $error);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database error. Check server logs for details.']);
    exit();
}

$stmt->bind_param("i", $table_no); // Bind table number as integer

// Execute the statement
if (!$stmt->execute()) {
    $error = $stmt->error;
    error_log("SQL execute error: " . $error);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database error. Check server logs for details.']);
    exit();
}

$result = $stmt->get_result();

// Fetch the orders
$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row; // This will now include order_id
}

// Return the orders as JSON
header('Content-Type: application/json');
if (empty($orders)) {
    echo json_encode(['success' => false, 'message' => 'No orders found.']);
} else {
echo json_encode(['success' => true, 'orders' => $orders, 'new_orders' => !empty($orders)]);

}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
