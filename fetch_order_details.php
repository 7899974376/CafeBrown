<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// Database connection parameters
$host = 'localhost';
$db = 'cbrown';
$user = 'root';
$pass = 'aniket';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection error: " . $conn->connect_error);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed. Check server logs for details.']);
    exit();
}

// Get the order ID from the request body
$input = file_get_contents('php://input');
if (empty($input)) {
    error_log("Empty request body received");
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request: empty body']);
    exit();
}

$data = json_decode($input);
if (json_last_error() !== JSON_ERROR_NONE || !isset($data->order_id)) {
    error_log("Invalid JSON received: " . json_last_error_msg());
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request: malformed JSON']);
    exit();
}

$order_id = intval($data->order_id);
if ($order_id <= 0) {
    error_log("Invalid order ID received: " . $data->order_id);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
    exit();
}

// Prepare the SQL statement
$query = "SELECT o.order_id, o.customer_name, o.customer_email, o.customer_phone, oi.item_name, oi.quantity, oi.price_per_item 
          FROM orders o 
          JOIN order_items oi ON o.order_id = oi.order_id 
          WHERE o.order_id = ?";

$stmt = $conn->prepare($query);
if (!$stmt) {
    $error = $conn->error;
    error_log("SQL prepare error: " . $error);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database error. Check server logs for details.']);
    exit();
}

$stmt->bind_param("i", $order_id);

// Execute the statement
if (!$stmt->execute()) {
    $error = $stmt->error;
    error_log("SQL execute error: " . $error);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database error. Check server logs for details.']);
    exit();
}

$result = $stmt->get_result();
$order = [];
$total_amount = 0;
while ($row = $result->fetch_assoc()) {
    $order['order_id'] = $row['order_id'];
    $order['customer_name'] = $row['customer_name'];
    $order['customer_email'] = $row['customer_email'];
    $order['customer_phone'] = $row['customer_phone'];
    $order['items'][] = [
        'item_name' => $row['item_name'],
        'quantity' => $row['quantity'],
        'price_per_item' => $row['price_per_item'],
        'total' => $row['quantity'] * $row['price_per_item']
    ];
    $total_amount += $row['quantity'] * $row['price_per_item'];
}
$order['total_amount'] = $total_amount;

// Return the order details as JSON
header('Content-Type: application/json');
echo json_encode(['success' => true, 'order' => $order]);

// Close the statement and connection
$stmt->close();
$conn->close();
?>