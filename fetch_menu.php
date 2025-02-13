<?php
// Database connection parameters
$host = 'localhost'; // Change if your database is hosted elsewhere
$db = 'cbrown'; // Replace with your database name
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch menu items
$sql = "SELECT id,name, price, description, category FROM menu_items"; // Adjusted query
$result = $conn->query($sql);

// Output menu items as HTML
if ($result->num_rows > 0) {
    echo '<div class="row">';
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-lg-6 col-md-6 mt-3 item ' . htmlspecialchars($row['category']) . '">';
        echo '    <div class="d-flex align-items-center">';
        echo '        <img class="flex-shrink-0 img-fluid rounded" src="img/menu-1.jpg" alt="" style="width: 80px;">';
        echo '        <div class="w-100 d-flex flex-column text-start ps-4">';
        echo '            <h5 class="d-flex justify-content-between border-bottom pb-2">';
        echo '                <span>' . htmlspecialchars($row['name']) . '</span>';
        echo '                <span class="text-primary">₹' . htmlspecialchars($row ['price']) . '</span>';
        echo '            </h5>';
        echo '            <small>' . htmlspecialchars($row['description']) . '</small>';
        echo '            <div class="d-flex justify-content-end">';
        echo '                <button class="btn btn-sm btn-primary mt-2" onclick="addToCart(' . $row['id'] . ', \'' . addslashes($row['name']) . '\', ' . $row['price'] . ')">Add</button>';
        echo '            </div>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
    echo '</div>';   
} else {
    echo 'No menu items found.';
}

$conn->close();
?>
