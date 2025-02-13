<?php
session_start(); // Start session to store cart data

if (isset($_SESSION['table'])) {
    // Retrieve the value of the 'table' session variable
    $table = $_SESSION['table'];
}
// Initialize cart array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to update cart items and quantities
function updateCart($item) {
    if (isset($_SESSION['cart'][$item['id']])) {
        $_SESSION['cart'][$item['id']]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$item['id']] = $item;
    }
}

// Function to remove item from cart
function removeItem($itemId) {
    if (isset($_SESSION['cart'][$itemId])) {
        unset($_SESSION['cart'][$itemId]);
    }
}

// Function to update item quantity in cart
function updateItemQuantity($itemId, $quantity) {
    if (isset($_SESSION['cart'][$itemId])) {
        $_SESSION['cart'][$itemId]['quantity'] = $quantity;
    }
}

// Check if the "Add to Cart" button was clicked
if (isset($_POST['add_to_cart'])) {
    $item = array(
        'id' => $_POST['item_id'],
        'name' => $_POST['item_name'],
        'price' => $_POST['item_price'],
        'quantity' => 1
    );
    updateCart($item);
}

// Check if the "Remove" button was clicked
if (isset($_POST['remove_item'])) {
    $itemId = $_POST['remove_item'];
    removeItem($itemId);
    
    // Generate updated cart HTML
    $cartItems = $_SESSION['cart'];
    $html = '';
    foreach ($cartItems as $item) {
        $html .= '<tr>
                    <td>' . $item['name'] . '</td>
                    <td>
                        <button onclick="updateQuantity(' . $item['id'] . ', \'decrement\')">-</button>
                        <input type="number" id="quantity-' . $item['id'] . '" value="' . $item['quantity'] . '" data-price="' . $item['price'] . '" readonly>
                        <button onclick="updateQuantity(' . $item['id'] . ', \'increment\')">+</button>
                        <button onclick="removeItem(' . $item['id'] . ')">Remove</button>
                    </td>
                    <td>₹' . $item['price'] . '</td>
                    <td id="total-' . $item['id'] . '">' . number_format($item['price'] * $item['quantity'], 2) . '</td>
                  </tr>';
    }
    echo $html;
    exit;
}

// Check if the quantity is being updated
if (isset($_POST['update_quantity'])) {
    $itemId = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    updateItemQuantity($itemId, $quantity);
    
    // Generate updated cart HTML
    $cartItems = $_SESSION['cart'];
    $html = '';
    foreach ($cartItems as $item) {
        $html .= '<tr>
                    <td>' . $item['name'] . '</td>
                    <td>
                        <button onclick="updateQuantity(' . $item['id'] . ', \'decrement\')">-</button>
                        <input type="number" id="quantity-' . $item['id'] . '" value="' . $item['quantity'] . '" data-price="' . $item['price'] . '" readonly>
                        <button onclick="updateQuantity(' . $item['id'] . ', \'increment\')">+</button>
                        <button onclick="removeItem(' . $item['id'] . ')">Remove</button>
                    </td>
                    <td>₹' . $item['price'] . '</td>
                    <td id="total-' . $item['id'] . '">' . number_format($item['price'] * $item['quantity'], 2) . '</td>
                  </tr>';
    }
    echo $html;
    exit;
}

// Return updated cart items in JSON format for AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode($_SESSION['cart']);
    exit;
}

// Display cart items
$cartItems = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link href="img/favicon.ico" rel="icon">

<!-- Google Web Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">

<!-- Icon Font Stylesheet -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

<!-- Libraries Stylesheet -->
<link href="lib/animate/animate.min.css" rel="stylesheet">
<link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
<link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

<!-- Customized Bootstrap Stylesheet -->
<link href="css/bootstrap.min.css" rel="stylesheet">

<!-- Template Stylesheet -->
<link href="css/style.css" rel="stylesheet">
    <style>
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function updateQuantity(itemId, action) {
            const quantityElement = document.getElementById(`quantity-${itemId}`);
            let quantity = parseInt(quantityElement.value);

            if (action === 'increment') {
                quantity += 1;
            } else if (action === 'decrement' && quantity > 1) {
                quantity -= 1;
            }

            quantityElement.value = quantity;

            // Update total price for this item
            const price = parseFloat(quantityElement.dataset.price);
            const totalElement = document.getElementById(`total-${itemId}`);
            totalElement.innerText = (price * quantity).toFixed(2);

            // Update overall total
            updateOverallTotal();

            // Send updated quantity to the server
            $.post('cart.php', { update_quantity: true, item_id: itemId, quantity: quantity }, function(response) {
                $('#cart-body').html(response);
                updateOverallTotal();
            });
        }

        function updateOverallTotal() {
            let overallTotal = 0;
            const cartBody = document.getElementById('cart-body');
            const rows = cartBody.getElementsByTagName('tr');

            for (let row of rows) {
                const totalCell = row.querySelector('td:last-child');
                overallTotal += parseFloat(totalCell.innerText);
            }

            document.getElementById('overall-total').innerText = overallTotal.toFixed(2);
        }

        function removeItem(itemId) {
            // Make an AJAX request to remove the item from the cart
            $.post('cart.php', { remove_item: itemId }, function(response) {
                // Update the cart display with the new cart data
                $('#cart-body').html(response);
                updateOverallTotal();
            });
        }

        function placeOrder() {
            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;

            // Validate name and phone number
            if (!name || !phone) {
                alert("Please enter both name and phone number.");
                return;
            }

            // Make an AJAX request to place the order
            $.post('placeorder.php', { name: name, phone: phone }, function(response) {
                alert(response); // Show the response from the server
                dasPDF();
                location.reload(); // Reload the page to update the cart
            });
        }
        async function dasPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const content = document.getElementById("cart-body");

            // Convert HTML to Canvas
            html2canvas(content).then((canvas) => {
                const imgData = canvas.toDataURL("image/png");

                // Add the image to PDF
                doc.addImage(imgData, "PNG", 10, 10, 190, 0);
                
                // Automatically download PDF
                doc.save("webpage.pdf");
            });
        }
    </script>
</head>
<body>
<div class="container-xxl bg-white p-0">

        <!-- Navbar & Hero Start -->
        <div class="container-xxl position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
                <a href="" class="navbar-brand p-0">
                    <h1 class="text-primary m-0"><i class="fa fa-coffee me-3"></i>Cafe Brown</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0 pe-4">
                        <a href="index.php" class="nav-item nav-link active">Home</a>
                        <a href="about.html" class="nav-item nav-link">About</a>
                        <a href="menu.php" class="nav-item nav-link">Menu</a>
                        <a href="cart.php" class="nav-item nav-link">Cart</a>
                    </div>
                </div>
            </nav>

            <div class="container-xxl py-5 bg-dark hero-header mb-5">
                <div class="container text-center my-5 pt-5 pb-4">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">Cart</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Pages</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Cart</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    <div class="container mt-5">
        <h1 class="text-center">Your Cart</h1>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="cart-body">
                    <?php foreach ($cartItems as $item) { ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td>
                                <button onclick="updateQuantity(<?php echo $item['id']; ?>, 'decrement')">-</button>
                                <input type="number" id="quantity-<?php echo $item['id']; ?>" value="<?php echo $item['quantity']; ?>" data-price="<?php echo $item['price']; ?>" readonly>
                                <button onclick="updateQuantity(<?php echo $item['id']; ?>, 'increment')">+</button>
                                <button onclick="removeItem(<?php echo $item['id']; ?>)">Remove</button>
                            </td>
                            <td>₹<?php echo $item['price']; ?></td>
                            <td id="total-<?php echo $item['id']; ?>"><?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table><br>

            <div >
            Name: <input type="text" id="name" name="name" required><br><br>
            Phone: <input type="text" id="phone" name="phone" required><br>
                    </div>
        </div>
        <div class="text-end">
            <h4>Total: ₹<span id="overall-total"><?php echo array_sum(array_map(function($item) { return $item['price'] * $item['quantity']; }, $cartItems)); ?></span></h4>
            <button id="order-btn" class="btn btn-primary py-3 px-5 mt-2" onclick="placeOrder()">Place Order</button>
        </div>
    </div>

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    
                    <div class="col-lg-8 col-md-3">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Contact</h4>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Vijay Arcade, First floor, near NH4 Highway,Kagal</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+919021005170</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href="https://maps.app.goo.gl/qgyii5811rcZZrEi6?g_st=iw" target="_blank"><i class="fa fa-map-marker-alt"></i></a>
                            <a class="btn btn-outline-light btn-social" href="https://www.instagram.com/cafee_brown/?igsh=MXBrZzV1bXFsM2NjNQ%3D%3D" target="_blank"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-3">
                        <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Opening</h4>
                        <h5 class="text-light fw-normal">Monday - Saturday</h5>
                        <p>09AM - 05PM</p>
                        <h5 class="text-light fw-normal">Sunday</h5>
                        <p>10AM - 01PM</p>
                    </div>
                </div>
            </div>
            
        </div>
        <!-- Footer End -->
        <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
