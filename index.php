<?php
// Start the session at the beginning of the script
session_start();

// Check if the 'table' parameter is set in the URL
if (isset($_GET['table'])) {
    // Store the 'table' parameter value in a session variable
    $_SESSION['table'] = $_GET['table'];

}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cafe Brown</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
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
                <div class="container my-5 py-5">
                    <div class="row align-items-center g-5">
                        <div class="col-lg-6 text-center text-lg-start">
                            <h1 class="display-3 text-white animated slideInLeft">Eat Meet<br>Celebrate</h1>
                            <p class="text-white animated slideInLeft mb-4 pb-2 fs-2">Where every sip and bite brings joy!</p>
                        </div>
                        <div class="col-lg-6 text-center text-lg-end overflow-hidden">
                            <img class="img-fluid" src="img/Coffeee.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->

        <!-- About Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.1s" src="img/about-1.jpg">
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.3s" src="img/about-2.jpg" style="margin-top: 25%;">
                            </div>
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.5s" src="img/about-3.jpg">
                            </div>
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.7s" src="img/about-4.jpg">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h5 class="section-title ff-secondary text-start text-primary fw-normal">About Us</h5>
                        <h1 class="mb-4">Welcome to <i class="fa fa-utensils text-primary me-2"></i>Cafe Brown</h1>
                        <p class="mb-4">Welcome to Café Brown! We are a cozy café that has been serving delicious coffee, pastries, and light bites to the local community for over 2 years. Our mission is to provide a warm, inviting space where people can relax, catch up with friends, or enjoy a quiet moment with a cup of coffee.</p>
                        <p class="mb-4">Our baristas are trained in the art of coffee making, and we source our beans from sustainable farms around the world. Whether you’re a fan of a strong espresso or a creamy cappuccino, we’ve got something to satisfy every coffee lover's taste.</p>
                        
                        <a class="btn btn-primary py-3 px-5 mt-2" href="">Read More</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->

        <!-- Menu Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="mt-2 text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h5 class="section-title ff-secondary text-center text-primary fw-normal">Food Menu</h5>
                </div>
                <div class="mt-5 text-center wow fadeInUp" data-wow-delay="0.1s">
                    <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                        <li class="nav-item" onclick="filterMenu('all')">
                            <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 active" data-bs-toggle="pill" href="#tab-all" onclick="fetchMenuItems()">
                                <div class="ps-3">
                                    <h6 class="mt-n1 mb-0">All Items</h6>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" onclick="filterMenu('pizza')">
                            <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3" data-bs-toggle="pill" href="#tab-pizza">
                                <div class="ps-3">
                                    <h6 class="mt-n1 mb-0">Pizza</h6>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" onclick="filterMenu('fries')">
                            <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3" data-bs-toggle="pill" href="#tab-fries">
                                <div class="ps-3">
                                    <h6 class="mt-n1 mb-0">Fries</h6>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" onclick="filterMenu('coffee')">
                            <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3" data-bs-toggle="pill" href="#tab-coffee">
                                <div class="ps-3">
                                    <h6 class="mt-n1 mb-0">Coffee</h6>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" onclick="filterMenu('maggie')">
                            <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3" data-bs-toggle="pill" href="#tab-maggie">
                                <div class="ps-3">
                                    <h6 class="mt-n1 mb-0">Maggie</h6>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" onclick="filterMenu('sandwich')">
                            <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3" data-bs-toggle="pill" href="#tab-sandwich">
                                <div class="ps-3">
                                    <h6 class="mt-n1 mb-0">Sandwich</h6>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" onclick="filterMenu('shake')">
                            <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3" data-bs-toggle="pill" href="#tab-shake">
                                <div class="ps-3">
                                    <h6 class="mt-n1 mb-0">Shake</h6>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" onclick="filterMenu('combo')">
                            <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3" data-bs-toggle="pill" href="#tab-combo">
                                <div class="ps-3">
                                    <h6 class="mt-n1 mb-0">Combo</h6>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="menu">
                    <?php include 'fetch_menu.php'; ?>
                    <!-- Add more menu items here with appropriate classes for filtering -->
                </div>
                </div>
            </div>
        </div>
        <!-- Menu End -->

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

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

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
    <script>
function addToCart(itemId, itemName, itemPrice) {
    // Send AJAX request to update cart
    fetch('cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `add_to_cart=true&item_id=${itemId}&item_name=${itemName}&item_price=${itemPrice}`
    })
    .then(response => response.json())
    .then(data => {
        // Show alert message
        alert(`${itemName} has been added to your cart!`);
    });
}

// Filter menu function
function filterMenu(category) {
    const menuContainer = document.querySelector('.menu');
    menuContainer.innerHTML = ''; // Clear the menu container

    fetch('fetch_menu.php')
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            const items = doc.querySelectorAll('.item');

            const filteredItems = Array.from(items).filter(item => item.classList.contains(category) || category === 'all');

            const row = document.createElement('div');
            row.classList.add('row');

            filteredItems.forEach(item => {
                row.innerHTML += item.outerHTML;
            });

            menuContainer.appendChild(row);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    fetchMenuItems();
});
let menuItems = [];

function fetchMenuItems() {
    const menuContainer = document.querySelector('.menu');
    menuContainer.innerHTML = ''; // Clear the menu container

    // Fetch menu items from the server
    fetch('fetch_menu.php')
        .then(response => response.text())
        .then(data => {
            menuContainer.innerHTML = data; // Append the menu items to the menu container
            menuItems = Array.from(document.querySelectorAll('.item')); // Store the menu items in an array
        });
}
    </script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
