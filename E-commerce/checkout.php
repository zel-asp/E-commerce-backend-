<?php
include("server/connection.php");
session_start();

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}


$subtotal = isset($_SESSION['checkout_totals']['subtotal']) ? (float)$_SESSION['checkout_totals']['subtotal'] : 0;
$shipping = isset($_SESSION['checkout_totals']['shipping']) ? (float)$_SESSION['checkout_totals']['shipping'] : 0;
$tax = isset($_SESSION['checkout_totals']['tax']) ? (float)$_SESSION['checkout_totals']['tax'] : 0;
$total = isset($_SESSION['checkout_totals']['total']) ? (float)$_SESSION['checkout_totals']['total'] : 0;


$subtotal = max($subtotal, 0);
$shipping = max($shipping, 0);
$tax = max($tax, 0);
$total = max($total, 0);

// bilang ng product sa cart
$cart_count = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_count += $item['quantity'];
}


if (isset($_POST['placeOrder'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $zip = mysqli_real_escape_string($conn, $_POST['zip']);
    $reference = mysqli_real_escape_string($conn, $_POST['reference']);
    $totaldb = isset($_POST['totaldb']) ? (float)$_POST['totaldb'] : 0;

    $stmt = $conn->prepare("INSERT INTO checkout (fname, lname, address, city, state, zip,payment, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssd", $fname, $lname, $address, $city, $state, $zip, $reference, $totaldb);

    if ($stmt->execute()) {
        unset($_SESSION['cart']);
        unset($_SESSION['checkout_totals']);
        echo "<script>
    alert('Message sent successfully!');
    window.location.href = 'cart.php';
</script>";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>JL Computer Parts - Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: "Inter", sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-slate-900">
    <header class="sticky top-0 z-50 bg-white shadow-md transition-all duration-300" id="navbar">
        <div class="container mx-auto max-w-7xl flex flex-wrap items-center justify-between py-5 px-5 md:px-8 gap-4">
            <a class="flex items-center gap-3" href="index.php">
                <span class="text-2xl font-extrabold text-violet-600 select-none">JL Computer Parts</span>
            </a>

            <nav class="hidden md:flex items-center space-x-6 font-medium text-slate-800">
                <a class="flex items-center gap-2 hover:text-violet-600 transition-colors duration-300" href="index.php">
                    <i class="fas fa-home text-base"></i> Home
                </a>
                <a class="flex items-center gap-2 hover:text-violet-600 transition-colors duration-300" href="index.php#products">
                    <i class="fas fa-microchip text-base"></i> Products
                </a>
                <a class="flex items-center gap-2 hover:text-violet-600 transition-colors duration-300" href="index.php#contact">
                    <i class="fas fa-phone-alt text-base"></i> Contact
                </a>
            </nav>

            <div class="flex items-center space-x-6 text-slate-800 text-lg relative">
                <a aria-label="User Account" class="hover:text-violet-600 transition-colors duration-300" href="#">
                    <i class="fas fa-user"></i>
                </a>

                <a href="cart.php" aria-label="Cart" class="relative hover:text-violet-600 transition-colors duration-300">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="absolute -top-2 -right-3 bg-violet-600 text-white text-xs font-semibold rounded-full px-2 py-0.5 shadow-md" id="cart-count">
                        <?php echo $cart_count; ?>
                    </span>
                </a>

                <button aria-label="Toggle menu" class="md:hidden text-slate-800 hover:text-violet-600 transition-colors duration-300" id="mobile-menu-button">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <nav aria-label="Mobile Navigation" class="hidden md:hidden bg-white border-t border-gray-200" id="mobile-menu">
            <ul class="flex flex-col space-y-3 py-4 px-5 font-medium text-slate-800">
                <li>
                    <a class="flex items-center gap-3 hover:text-violet-600 transition-colors duration-300" href="index.php">
                        <i class="fas fa-home text-base"></i> Home
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-3 hover:text-violet-600 transition-colors duration-300" href="index.php#products">
                        <i class="fas fa-microchip text-base"></i> Products
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-3 hover:text-violet-600 transition-colors duration-300" href="index.php#contact">
                        <i class="fas fa-phone-alt text-base"></i> Contact
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container mx-auto max-w-7xl px-5 md:px-8 py-12">
        <div class="flex flex-col md:flex-row gap-8">
            <form action="" method="POST" class="flex flex-col md:flex-row gap-8">
                <!-- Checkout Form -->
                <div class="md:w-2/3">
                    <h1 class="text-3xl font-extrabold text-violet-600 mb-8">Checkout</h1>

                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-violet-600" name="fname" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-violet-600" name="lname" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-violet-600" name="address" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-violet-600" name="city" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-violet-600" name="state" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ZIP/Postal Code</label>
                                <input type="text" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-violet-600" name="zip" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-violet-600" name="phone" required>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-4">Payment Method</h2>

                        <div class="space-y-4">

                            <div class="flex items-center gap-3 p-4 border border-gray-300 rounded-lg">
                                <span for="gcash" class="flex-grow">GCash</span>
                                <input type="text " name="reference" id="gcash" class="h-5 w-100 border border-2 p-2" placeholder="Reference number">

                                <img src="assets/images/GCash-Logo.jpg" class="h-6" alt="PayPal">
                            </div>
                        </div>
                    </div>

                </div>


                <!-- Order Summary -->
                <div class="md:w-1/3">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">

                        <h2 class="text-xl font-semibold mb-4">Order Summary</h2>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">₱<?php echo $subtotal ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium">₱<?php echo $shipping ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-medium">₱<?php echo $tax ?></span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-4">
                                <span class="font-semibold">Total</span>
                                <span class="font-bold text-lg text-violet-600">₱<?php echo number_format($total, 2); ?></span>
                            </div>
                        </div>
                        <input type="hidden" name="totaldb" value="<?php echo number_format($total, 2, '.', ''); ?>">

                        <button class="w-full bg-violet-600 hover:bg-violet-700 text-white font-semibold py-3 px-6 rounded-lg transition" type="submit" name="placeOrder">
                            Place Order
                        </button>

                    </div>
                </div>
            </form>
        </div>


    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 py-16">
        <div class="container mx-auto max-w-7xl px-5 md:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-12 border-b border-slate-700 pb-12">
                <div>
                    <h3 class="text-xl font-extrabold text-violet-500 mb-6">JL Computer Parts</h3>
                    <p class="mb-6 max-w-xs">
                        One of the affordable computer components and accessories.
                    </p>
                    <div class="flex space-x-5">
                        <a aria-label="Facebook"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-700 hover:bg-violet-500 transition-colors"
                            href="https://www.facebook.com/share/1FHV6WdkG5/"><i class="fab fa-facebook-f text-white"></i></a>
                        <a aria-label="YouTube"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-700 hover:bg-violet-500 transition-colors"
                            href="#"><i class="fab fa-youtube text-white"></i></a>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-violet-500 mb-6">Shop</h3>
                    <ul class="space-y-3 text-slate-400">
                        <li>
                            <a class="hover:text-violet-500 transition-colors duration-300" href="index.php#products">Corsair RM850x 850W</a>
                        </li>
                        <li>
                            <a class="hover:text-violet-500 transition-colors duration-300" href="index.php#products">NZXT H510 Elite</a>
                        </li>
                        <li>
                            <a class="hover:text-violet-500 transition-colors duration-300" href="index.php#products">Noctua NH-D15 Chromax</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-violet-500 mb-6">Support</h3>
                    <ul class="space-y-3 text-slate-400">
                        <li>
                            <a class="hover:text-violet-500 transition-colors duration-300" href="index.php#contact">Contact Us</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-violet-500 mb-6">Newsletter</h3>
                    <p class="mb-6 text-slate-400">
                        Subscribe to get updates on new products and special offers.
                    </p>
                    <form class="flex flex-col space-y-4">
                        <input
                            class="px-4 py-3 rounded-md border border-slate-700 bg-slate-800 text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500"
                            placeholder="Your email address" type="email" />
                        <button
                            class="bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-full px-6 py-3 shadow-lg transition-transform transform hover:-translate-y-1"
                            type="submit">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
            <p class="text-center text-slate-500 mt-12 text-sm select-none">
                © 2025 JL Computer Parts
            </p>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>
</body>

</html>