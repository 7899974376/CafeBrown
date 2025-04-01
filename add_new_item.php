<?php
// Database connection parameters
$host = 'localhost'; // Change if your database is hosted elsewhere
$db = 'cbrown'; // Replace with your database name
$user = 'root'; // Replace with your database username
$pass = 'aniket'; // Replace with your database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);

}

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemName = $_POST['itemName'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    
    // Handle image upload
    if (isset($_FILES['itemImage']) && $_FILES['itemImage']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['itemImage']['tmp_name']);
        
        // Validate image size
        if ($_FILES['itemImage']['size'] > 60 * 1024) { // 60KB
            echo 'Error: Image size must not exceed 60KB.';

            exit;
        }

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO menu_items (name, description, price, category, picture) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $itemName, $description, $price, $category, $image);

        // Execute and check for success
        if ($stmt->execute()) {
            echo 'Menu item added successfully!';

        } else {
            echo 'Error adding menu item: ' . $stmt->error;

        }

        $stmt->close();
    } else {
        echo 'Error: No image uploaded or upload error occurred.';

    }
}

$conn->close();
?>
