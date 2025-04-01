<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Brown - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1a1a;
            color: #ffffff;
        }
        .sidebar {
            background-color: #2d2d2d;
            min-height: 100vh;
            padding: 20px;
        }
        .content {
            padding: 20px;
        }
        .nav-link {
            color: #ffffff;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #3d3d3d;
        }
        .table-card {
            background-color: #2d2d2d;
            padding: 20px;
            margin: 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .table-card:hover {
            background-color: #3d3d3d;
        }
        .form-control, .form-select {
            background-color: #3d3d3d;
            border-color: #4d4d4d;
            color: #ffffff;
        }
        .form-control:focus, .form-select:focus {
            background-color: #3d3d3d;
            border-color: #6d6d6d;
            color: #ffffff;
            box-shadow: 0 0 0 0.25rem rgba(255,255,255,0.1);
        }
        .cafe-title {
            color: #d4a373;
            margin-bottom: 2rem;
        }
        #orderDetails {
            background-color: #2d2d2d;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #orderDetails, #orderDetails * {
                visibility: visible;
                color: #000 !important;
            }
            #orderDetails {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                border: none;
                background: none;
            }
            .btn {
                display: none;
            }
            table {
                color: #000 !important;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <h3 class="cafe-title">Cafe Brown</h3>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#" onclick="showOrders()">Orders</a>
                    <a class="nav-link" href="#" onclick="showAddMenu()">Add Menu Items</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 content">
                <!-- Orders Section -->
                <div id="ordersSection">
                    <h2>Tables</h2>
                    <div class="row" id="tables">
                        <?php for($i = 1; $i <= 7; $i++): ?>
                            <div class="col-md-3 mb-4">
                                <div class="table-card" id="table-<?php echo $i; ?>" onclick="showTableOrders(<?php echo $i; ?>)">
                                    <h4>Table <?php echo $i; ?></h4>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <div id="orderDetails" style="display: none;">
                        <h3>Table Orders</h3>
                        <div id="ordersList"></div>
                    </div>
                </div>

                <!-- Add Menu Section -->
                <div id="addMenuSection" style="display: none;">
                    <h2>Add Menu Item</h2>
                    <form id="menuForm" class="mt-4">
                        <div class="mb-3">
                            <label for="itemName" class="form-label">Item Name</label>
                            <input type="text" class="form-control" id="itemName" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemImage" class="form-label">Item Image</label>
                            <input type="file" class="form-control" id="itemImage" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" required>
                                <option value="">Select Category</option>
                                <option value="pizza">Pizza</option>
                                <option value="fries">Fries</option>
                                <option value="coffee">Coffee</option>
                                <option value="maggie">Maggie</option>
                                <option value="sandwich">Sandwich</option>
                                <option value="shake">Shake</option>
                                <option value="combo">Combo</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" id="addItemButton">Add Item</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to fetch orders for all tables
        function fetchOrdersForAllTables() {
            const tableCount = 7; // Assuming there are 7 tables
            for (let i = 1; i <= tableCount; i++) {
                fetch('fetch_orders.php', {
                    method: 'POST',
                    body: JSON.stringify({ table_no: i }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error(data.message);
                        return;
                    }

                    // Check for new orders and update table color
                    const tableElement = document.querySelector(`#table-${i}`);
                    if (tableElement) {
                        if (data.new_orders) {
                            tableElement.style.backgroundColor = 'blue'; // New orders available
                        } else if (!data.new_orders && !tableElement.classList.contains('confirmed')) {
                            tableElement.style.backgroundColor = ''; // Reset color if no new orders and not confirmed
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching orders for table ' + i + ':', error);
                });
            }
        }

        // Call fetchOrdersForAllTables on page load
        window.onload = function() {
            showOrders(); // Ensure orders are displayed on load
            fetchOrdersForAllTables(); // Initial fetch
            setInterval(fetchOrdersForAllTables, 5000); // Fetch every 5 seconds
            loadConfirmedOrders(); // Load confirmed orders from local storage
        };

        function showOrders() {
            document.getElementById('ordersSection').style.display = 'block';
            document.getElementById('addMenuSection').style.display = 'none';
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            document.querySelector('.nav-link:first-child').classList.add('active');
        }

        function showAddMenu() {
            document.getElementById('ordersSection').style.display = 'none';
            document.getElementById('addMenuSection').style.display = 'block';
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            document.querySelector('.nav-link:last-child').classList.add('active');
        }

        function showTableOrders(tableNumber) {
            document.getElementById('orderDetails').style.display = 'block';
            
            // Fetch orders from the database for the specific table
            fetch('fetch_orders.php', {
                method: 'POST',
                body: JSON.stringify({ table_no: tableNumber }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message);
                    return;
                }

                let orders = data.orders;
                let orderHTML = '';

                // Check for new orders and update table color
                const tableElement = document.querySelector(`#table-${tableNumber}`);
                if (tableElement && data.new_orders) {
                    tableElement.style.backgroundColor = 'blue';
                }
                
                // Display orders separately based on order ID
                const orderIds = [...new Set(orders.map(order => order.order_id))]; // Get unique order IDs
                orderIds.forEach(orderId => {
                    const order = orders.find(order => order.order_id === orderId);
                    orderHTML += `<div class="order" id="order-${orderId}" style="margin-bottom: 20px;">
                                    <h5>Order ID: ${orderId} <span class="confirmation-message" id="confirmation-${orderId}"></span></h5>
                                    <p>Customer Name: ${order.customer_name}</p>
                                    <p>Customer Phone: ${order.customer_phone}</p>
                                    <table class="table table-dark">
                                        <thead>
                                            <tr>
                                                <th>Item Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                    let total = 0;
                    orders.forEach(order => {
                        if (order.order_id === orderId) {
                            const itemTotal = order.quantity * order.price_per_item;
                            orderHTML += `<tr>
                                            <td>${order.item_name}</td>
                                            <td>${order.quantity}</td>
                                            <td>${order.price_per_item}</td>
                                            <td>${itemTotal.toFixed(2)}</td>
                                          </tr>`;
                            total += itemTotal;
                        }
                    });
                    orderHTML += `</tbody>
                                  </table>
                                  <div class="mt-3">
                                      <button class="btn btn-warning me-2" onclick="confirmOrder(${tableNumber}, ${orderId})">Confirm Order</button>
                                      <button class="btn btn-success me-2" onclick="markPaymentDone(${orderId})">Payment Done</button>
                                      <button class="btn btn-primary" onclick="printBill(${orderId})">Print Bill</button>
                                  </div>
                                  <div class="text-end"><strong>Grand Total: ₹${total.toFixed(2)}</strong></div>
                                  </div>`;
                });

                document.getElementById('ordersList').innerHTML = orderHTML;

                // Display "Order confirmed" text for confirmed orders
                orders.forEach(order => {
                    if (order.confirm_order === 'Yes') {
                        const confirmationMessage = document.querySelector(`#confirmation-${order.order_id}`);
                        if (confirmationMessage) {
                            confirmationMessage.textContent = 'Order confirmed';
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching orders:', error);
                alert('An error occurred while fetching orders.');
            });
        }

function confirmOrder(tableNumber, orderId) {
    fetch('update_status.php', {
        method: 'POST',
        body: JSON.stringify({ order_id: orderId }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const confirmationMessage = document.querySelector(`#confirmation-${orderId}`);
            if (confirmationMessage) {
                confirmationMessage.textContent = 'Order confirmed'; // Add confirmation text
                localStorage.setItem(`orderConfirmed-${orderId}`, 'true'); // Store confirmation in local storage
                console.log(`Order ${orderId} confirmed and stored in local storage.`); // Debug log
            }
        } else {
            alert('Error confirming order: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while confirming the order.');
    });
}


        function loadConfirmedOrders() {
            const orderElements = document.querySelectorAll('.order');
            orderElements.forEach(orderElement => {
                const orderId = orderElement.id.split('-')[1]; // Extract order ID from element ID
                if (localStorage.getItem(`orderConfirmed-${orderId}`)) {
                    const confirmationMessage = document.querySelector(`#confirmation-${orderId}`);
                    if (confirmationMessage) {
                        confirmationMessage.textContent = 'Order confirmed'; // Load confirmation text
                        console.log(`Order ${orderId} loaded from local storage.`); // Debug log
                    }
                }
            });
        }

        function markPaymentDone(orderId) {
            fetch('update_payment.php', {
                method: 'POST',
                body: JSON.stringify({ order_id: orderId, payment: 'Yes' }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Payment marked as completed!');
                    document.getElementById('orderDetails').style.display = 'none';

                    // Fetch order details to send WhatsApp message
                    fetch('fetch_order_details.php', {
                        method: 'POST',
                        body: JSON.stringify({ order_id: orderId }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(orderData => {
                        if (orderData.success) {
                            const order = orderData.order;
                            let orderDetails = '';
                            order.items.forEach(item => {
                                orderDetails += `Item: ${item.item_name}, Quantity: ${item.quantity}, Price: ₹${item.price_per_item}, Total: ₹${item.total}\n`;
                            });

                            const message = `Dear ${order.customer_name},\n\nThank you for your order!\n\nOrder ID: ${order.order_id}\n\nOrder Details:\n${orderDetails}\nTotal Amount: ₹${order.total_amount}\n\nThank you for coming, visit once again!\n\nBest regards,\nCafe Brown`;
                            const whatsappNumber = order.customer_phone;
                            const whatsappLink = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;

                            window.open(whatsappLink, "_blank");
                        } else {
                            alert('Error fetching order details: ' + orderData.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while fetching order details.');
                    });
                } else {
                    alert('Error updating payment status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating payment status.');
            });
        }

        function printBill() {
            window.print();
        }

        // Handle menu form submission
        document.getElementById('menuForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const itemName = document.getElementById('itemName').value;
            const itemImage = document.getElementById('itemImage').files[0];
            const description = document.getElementById('description').value;
            const price = document.getElementById('price').value;
            const category = document.getElementById('category').value;

            // Validate image size
            if (itemImage.size > 60 * 1024) { // 60KB
                alert('Image size must not exceed 60KB.');
                return;
            }

            const formData = new FormData();
            formData.append('itemName', itemName);
            formData.append('itemImage', itemImage);
            formData.append('description', description);
            formData.append('price', price);
            formData.append('category', category);

            // Send the form data to the server using fetch
            fetch('add_new_item.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                alert(text);
                this.reset();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the menu item.');
            });

            this.reset();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>