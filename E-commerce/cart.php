<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// add to cart
if (isset($_POST['addToCart'])) {
    $product_id = $_POST['product_id'];
    $image_url = $_POST['image_url'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // check kung nasa cart na yung product
    $product_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $product_id) {
            $item['quantity'] += 1;
            $product_exists = true;
            break;
        }
    }

    if (!$product_exists) {
        $_SESSION['cart'][] = [
            'product_id' => $product_id,
            'image_url' => $image_url,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'quantity' => 1
        ];
    }

    header('Location: cart.php');
    exit();
}

// Update quantity or remove item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id'];
        $new_quantity = (int)$_POST['quantity'];

        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id) {
                if ($new_quantity > 0) {
                    $item['quantity'] = $new_quantity;
                } else {

                    $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($product_id) {
                        return $item['product_id'] != $product_id;
                    });
                }
                break;
            }
        }
    } elseif (isset($_POST['remove_item'])) {
        $product_id = $_POST['product_id'];

        $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($product_id) {
            return $item['product_id'] != $product_id;
        });
    }

    header('Location: cart.php');
    exit();
}

// Calculate total
$subtotal = 0;
$tax_rate = 0.12;
$shipping = 0;

foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$tax = $subtotal * $tax_rate;
$total = $subtotal + $tax + $shipping;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>JL Computer Parts - Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/style.css">
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
                        <?php
                        $total_items = 0;
                        if (isset($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                $total_items += $item['quantity'];
                            }
                        }
                        echo $total_items;
                        ?>
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
        <h1 class="text-3xl font-extrabold text-violet-600 mb-8">Your Shopping Cart</h1>

        <?php if (empty($_SESSION['cart'])): ?>
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-shopping-cart text-5xl text-gray-300 mb-4"></i>
                <h2 class="text-xl font-semibold mb-2">Your cart is empty</h2>
                <p class="text-gray-600 mb-6">Looks like you haven't added any items to your cart yet.</p>
                <a href="index.php#products" class="inline-block bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-full px-6 py-3 transition">
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Cart Items -->
                <div class="divide-y divide-gray-200">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <div class="p-6 flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-32 h-32 flex-shrink-0">
                                <img src="assets/images/<?php echo $item['image_url']; ?>"
                                    alt="<?php echo $item['name']; ?>"
                                    class="w-full h-full object-contain">
                            </div>
                            <div class="flex-grow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-semibold"><?php echo $item['name']; ?></h3>
                                        <p class="text-gray-600 text-sm mt-1"><?php echo $item['description']; ?></p>
                                    </div>
                                    <span class="text-lg font-bold text-violet-600">
                                        ₱<?php echo number_format($item['price'], 2); ?>
                                    </span>
                                </div>

                                <div class="mt-4 flex items-center justify-between">
                                    <form method="POST" class="flex items-center">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                        <button type="button" class="quantity-btn px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-l-full border border-gray-300" data-action="decrease">-</button>
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1"
                                            class="quantity-input w-16 text-center border-t border-b border-gray-300 py-1">
                                        <button type="button" class="quantity-btn px-3 py-1 text-gray-600 hover:bg-gray-100 rounded-r-full border border-gray-300" data-action="increase">+</button>
                                        <button type="submit" name="update_quantity" class="ml-4 text-sm text-blue-600 hover:text-blue-800">
                                            Update
                                        </button>
                                    </form>
                                    <form method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                        <button type="submit" name="remove_item" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </form>
                                </div>
                                <div class="mt-2 text-right">
                                    <span class="font-semibold">
                                        Subtotal: ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Cart Summary -->

                <div class="bg-gray-50 p-6 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-semibold">Subtotal (<?php
                                                                $total_items = 0;
                                                                foreach ($_SESSION['cart'] as $item) {
                                                                    $total_items += $item['quantity'];
                                                                }
                                                                echo $total_items;
                                                                ?> items)</span>
                        <span class="font-bold text-lg">₱<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-semibold">Shipping</span>
                        <span class="font-bold text-lg">₱<?php echo number_format($shipping, 2); ?></span>
                    </div>
                    <div class="flex justify-between items-center mb-6">
                        <span class="font-semibold">Tax (12%)</span>
                        <span class="font-bold text-lg">₱<?php echo number_format($tax, 2); ?></span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-200 pt-4">
                        <span class="font-bold text-lg">Total</span>
                        <span class="font-bold text-xl text-violet-600" value="<?php echo number_format($total, 2); ?>" name="totaldb">₱<?php echo number_format($total, 2); ?></span>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="index.php#products" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition">
                            Continue Shopping
                        </a>
                        <form method="POST" action="checkout.php">
                            <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                            <input type="hidden" name="shipping" value="<?php echo $shipping; ?>">
                            <input type="hidden" name="tax" value="<?php echo $tax; ?>">
                            <input type="hidden" name="total" value="<?php echo $total; ?>">
                            <button type="submit" name="checkoutBtn" class="flex-1 text-center bg-violet-600 hover:bg-violet-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                                Proceed to Checkout
                            </button>
                        </form>
                        <?php
                        // Store the prices in session before redirecting
                        $_SESSION['checkout_totals'] = [
                            'subtotal' => $subtotal,
                            'shipping' => $shipping,
                            'tax' => $tax,
                            'total' => $total
                        ];
                        ?>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </main>

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
            // for mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });

            // Quantity buttons functionality
            document.querySelectorAll('.quantity-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const action = this.getAttribute('data-action');
                    const input = this.closest('.flex').querySelector('.quantity-input');
                    let value = parseInt(input.value);

                    if (action === 'increase') {
                        value++;
                    } else if (action === 'decrease' && value > 1) {
                        value--;
                    }

                    input.value = value;
                });
            });
        });
    </script>
</body>

</html>