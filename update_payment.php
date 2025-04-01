<?php
// Database connection parameters
$servername = "localhost"; // Change if necessary
$username = "root"; // Replace with your database username
$password = "aniket"; // Replace with your database password
$dbname = "cbrown"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);
$order_id = $data['order_id'];
$payment_status = $data['payment'];

// Prepare and bind
$stmt = $conn->prepare("UPDATE orders SET payment = ? WHERE order_id = ?");
$stmt->bind_param("si", $payment_status, $order_id);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error updating payment status: " . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
