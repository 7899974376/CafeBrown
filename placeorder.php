<?php
session_start(); // Start session to access cart data

// Database connection
$host = 'localhost'; // Change if your database is hosted elsewhere
$db = 'cbrown'; // Replace with your database name
$user = 'root'; // Replace with your database username
$pass = 'aniket'; // Replace with your database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the cart is not empty and the table number is set
if (!empty($_SESSION['cart']) && isset($_SESSION['table']) && !is_null($_POST['name']) && !is_null($_POST['phone'])) {
    $table = $_SESSION['table'];
    $totalAmount = 0;

    // Calculate total amount
    foreach ($_SESSION['cart'] as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }

    // Get name, phone number, and email from POST request
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];


    // Insert order into the database
    $stmt = $conn->prepare("INSERT INTO orders (table_no, grand_total, customer_name, customer_phone, customer_email) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $table, $totalAmount, $name, $phone, $email);


    if ($stmt->execute()) {
        $orderId = $conn->insert_id; // Fetch the order_id of the newly created order

        // Insert each item into the order_items table
        foreach ($_SESSION['cart'] as $item) {
            $itemName = $item['name'];
            $quantity = $item['quantity'];
            $pricePerItem = $item['price'];
            $totalPrice = $pricePerItem * $quantity;

            $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, item_name, quantity, price_per_item, total_price) VALUES (?, ?, ?, ?, ?)");
            $stmtItem->bind_param("isddd", $orderId, $itemName, $quantity, $pricePerItem, $totalPrice);
            $stmtItem->execute();
            $stmtItem->close();
        }
        unset($_SESSION['cart']); // Clear the cart after successful order placement
        echo "Order placed successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Your cart is empty or table number is not set!";
}

$conn->close();
?>
