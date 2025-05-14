<?php
include("server/connection.php");
session_start();

if (isset($_POST['send_btn'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  if (!empty($name) && !empty($email) && !empty($message)) {

    $stmt = $conn->prepare("INSERT INTO inquiries (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
      echo "<script>alert('Message sent successfully!')</script>";
    } else {
      echo "Error: " . $stmt->error;
    }
  }
}

$stmt1 = $conn->prepare("SELECT * FROM products");
$stmt1->execute();
$result = $stmt1->get_result();

?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>JL Computer Parts</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-gray-50 text-slate-900">
  <header class="sticky top-0 z-50 bg-white shadow-md transition-all duration-300" id="navbar">
    <div class="container mx-auto max-w-7xl flex flex-wrap items-center justify-between py-5 px-5 md:px-8 gap-4">

      <!-- Logo -->
      <a class="flex items-center gap-3" href="#">
        <span class="text-2xl font-extrabold text-violet-600 select-none">JL Computer Parts</span>
      </a>

      <!-- Desktop Nav -->
      <nav class="hidden md:flex items-center space-x-6 font-medium text-slate-800">
        <a class="flex items-center gap-2 hover:text-violet-600 transition-colors duration-300" href="#">
          <i class="fas fa-home text-base"></i> Home
        </a>
        <a class="flex items-center gap-2 hover:text-violet-600 transition-colors duration-300" href="#products">
          <i class="fas fa-microchip text-base"></i> Products
        </a>
        <a class="flex items-center gap-2 hover:text-violet-600 transition-colors duration-300" href="#contact">
          <i class="fas fa-phone-alt text-base"></i> Contact
        </a>
      </nav>

      <!-- Search Input (Responsive) -->
      <div class="w-full md:w-auto order-3 md:order-none relative">
        <div class="relative">
          <input
            type="text"
            id="product-search"
            placeholder="Search for products..."
            class="w-full md:w-72 px-4 py-2 pl-10 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-violet-600 shadow-sm transition-all duration-200"
            aria-label="Search products" />
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
          </div>
          <button
            id="clear-search"
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
            style="display: none;">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div id="search-results-count" class="text-sm text-gray-500 mt-1 hidden"></div>
      </div>

      <!-- Right icons -->
      <div class="flex items-center space-x-6 text-slate-800 text-lg relative">
        <!-- User -->
        <a aria-label="User Account" class="hover:text-violet-600 transition-colors duration-300" href="#">
          <i class="fas fa-user"></i>
        </a>

        <!-- Cart -->
        <a href="cart.php" aria-label="Cart" class="relative hover:text-violet-600 transition-colors duration-300">
          <i class="fas fa-shopping-cart"></i>
          <span class="absolute -top-2 -right-3 bg-violet-600 text-white text-xs font-semibold rounded-full px-2 py-0.5 shadow-md" id="cart-count">
            <?php echo count($_SESSION['cart']); ?>
          </span>
        </a>
        <!-- Mobile menu button -->
        <button aria-label="Toggle menu"
          class="md:hidden text-slate-800 hover:text-violet-600 transition-colors duration-300" id="mobile-menu-button">
          <i class="fas fa-bars text-2xl"></i>
        </button>
      </div>
    </div>

    <!-- Mobile menu -->
    <nav aria-label="Mobile Navigation" class="hidden md:hidden bg-white border-t border-gray-200" id="mobile-menu">
      <ul class="flex flex-col space-y-3 py-4 px-5 font-medium text-slate-800">
        <li>
          <a class="flex items-center gap-3 hover:text-violet-600 transition-colors duration-300" href="#home">
            <i class="fas fa-home text-base"></i> Home
          </a>
        </li>
        <li>
          <a class="flex items-center gap-3 hover:text-violet-600 transition-colors duration-300" href="#products">
            <i class="fas fa-microchip text-base"></i> Products
          </a>
        </li>
        <li>
          <a class="flex items-center gap-3 hover:text-violet-600 transition-colors duration-300" href="#contact">
            <i class="fas fa-phone-alt text-base"></i> Contact
          </a>
        </li>
      </ul>
    </nav>
  </header>


  <!-- Hero Section -->
  <main class="container mx-auto max-w-7xl px-5 md:px-8 mt-12">
    <form action="" method="POST">
    </form>
    <section aria-label="Hero section with promotional content"
      class="flex flex-col-reverse md:flex-row items-center justify-between gap-10 md:gap-20 min-h-[80vh]">
      <div class="md:flex-1 text-center md:text-left animate-fadeInLeft" style="animation-duration: 1s">
        <h1
          class="text-4xl md:text-5xl font-extrabold leading-tight bg-gradient-to-r from-violet-600 to-yellow-500 text-transparent bg-clip-text mb-6">
          Find The Best Computer Parts Collection
        </h1>
        <p class="text-lg text-slate-700 max-w-lg mx-auto md:mx-0 mb-8">
          Up to 50% Off Sale<br>Don't Miss Out!!<br> Check Out Now!
        </p>
        <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4 max-w-xs mx-auto md:mx-0">
          <a class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-full px-8 py-4 shadow-lg transition-transform transform hover:-translate-y-1"
            href="#products">Shop Now</a>
        </div>
      </div>
      <div class="md:flex-1 animate-fadeInRight max-w-lg w-full" style="animation-duration: 1s">
        <img alt="Premium gaming PC with RGB lighting, high-end components, and sleek black case"
          class="rounded-lg shadow-2xl w-full object-cover hover:rotate-0 rotate-[-10deg] transition-transform duration-500"
          decoding="async" height="400" loading="lazy"
          src="assets/images/Case.jpg" width="600" />
      </div>
    </section>

    <!-- Products Section -->
    <section aria-label="Featured products section with premium computer parts" class="mt-20" id="products">
      <h2 class="text-3xl font-extrabold text-center text-violet-600 mb-12">
        This All Product is Discounted 50% Off!.
      </h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8" id="products-grid">
        <?php while ($row = $result->fetch_assoc()) { ?>
          <form action="cart.php" method="POST">
            <article
              aria-label="Product: <?php echo htmlspecialchars($row['name']); ?>"
              class="bg-white rounded-lg shadow-md p-5 flex flex-col product-card hover:shadow-lg transition-shadow duration-300"
              data-name="<?= htmlspecialchars(strtolower($row['name'])); ?>"
              data-description="<?= htmlspecialchars(strtolower($row['description'])); ?>"
              data-category="<?= isset($row['category']) ? htmlspecialchars(strtolower($row['category'])) : ''; ?>">
              <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['product_id']); ?>" />
              <input type="hidden" name="image_url" value="<?= htmlspecialchars($row['image_url']); ?>" />
              <input type="hidden" name="name" value="<?= htmlspecialchars($row['name']); ?>" />
              <input type="hidden" name="description" value="<?= htmlspecialchars($row['description']); ?>" />
              <input type="hidden" name="price" value="<?= htmlspecialchars($row['price']); ?>" />

              <img
                alt="<?php echo htmlspecialchars($row['description']); ?>"
                class="rounded-md mb-4 object-cover w-full h-48 hover:scale-105 transition-transform duration-300"
                src="assets/images/<?php echo htmlspecialchars($row['image_url']); ?>"
                loading="lazy" />
              <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($row['name']); ?></h3>
              <p class="text-slate-600 flex-grow text-sm"><?php echo htmlspecialchars($row['description']); ?></p>
              <div class="mt-4 flex items-center justify-between">
                <span class="text-violet-500 font-bold text-lg">₱<?php echo number_format($row['price'], 2); ?></span>
                <button
                  aria-label="Add <?php echo htmlspecialchars($row['name']); ?> to cart"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full font-semibold transition hover:scale-105 transform"
                  name="addToCart"
                  type="submit">
                  Add to Cart
                </button>
              </div>
            </article>
          </form>
        <?php } ?>
      </div>

      <div id="no-results-message" class="text-center py-10 hidden">
        <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600">No products found</h3>
        <p class="text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
      </div>
    </section>


    <!-- Contact Section -->
    <section aria-label="Contact section with form and company information" class="mt-24 mb-20" id="contact">
      <h2 class="text-3xl font-extrabold text-center text-violet-600 mb-12">
        Contact Us:</h2>
      <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-8 grid grid-cols-1 md:grid-cols-2 gap-10">


        <!--form for contact-->
        <form action="" method="POST" aria-label="Contact form for customer inquiries" class="flex flex-col space-y-6"
          novalidate id="contact-form">
          <div>
            <label class="block mb-2 font-semibold text-slate-700" for="name">Name</label>
            <input
              class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-violet-600"
              id="name" name="name" placeholder="Your Full Name" required type="text" />
          </div>
          <div>
            <label class="block mb-2 font-semibold text-slate-700" for="email">Email</label>
            <input
              class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-violet-600"
              id="email" name="email" placeholder="Email Address" required type="email" />
          </div>
          <div>
            <label class="block mb-2 font-semibold text-slate-700" for="message">Message</label>
            <textarea
              class="w-full border border-gray-300 rounded-md px-4 py-3 resize-none focus:outline-none focus:ring-2 focus:ring-violet-600"
              id="message" name="message" placeholder="Write your message here..." required rows="5"></textarea>
          </div>
          <button
            class="bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-full px-8 py-4 shadow-lg transition-transform transform hover:-translate-y-1"
            name="send_btn" type="submit">
            Send Message
          </button>
        </form>


        <div class="text-slate-700 flex flex-col justify-center space-y-6">
          <div>
            <h3 class="text-xl font-semibold mb-2">Our Address</h3>
            <p>Damong Maliit RoadNagkaisang Nayon,Novaliches.QuezonCity
            </p>
          </div>
          <div>
            <h3 class="text-xl font-semibold mb-2">Phone</h3>
            <p>0945-1004-760</p>
          </div>
          <div>
            <h3 class="text-xl font-semibold mb-2">Email</h3>
            <p>insaneee.lloyd@gmail.com</p>
          </div>
          <div>
            <h3 class="text-xl font-semibold mb-2">Business Hours</h3>
            <p>Mon - Fri: 10:30 AM - 7:00 PM</p>
            <p>Sat - Sun: Closed</p>
          </div>
        </div>
      </div>
    </section>
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
              <a class="hover:text-violet-500 transition-colors duration-300" href="#products">Corsair RM850x 850W</a>
            </li>
            <li>
              <a class="hover:text-violet-500 transition-colors duration-300" href="#products">NZXT H510 Elite</a>
            </li>
            <li>
              <a class="hover:text-violet-500 transition-colors duration-300" href="#products">Noctua NH-D15 Chromax</a>
            </li>
          </ul>
        </div>
        <div>
          <h3 class="text-xl font-extrabold text-violet-500 mb-6">Support</h3>
          <ul class="space-y-3 text-slate-400">
            <li>
              <a class="hover:text-violet-500 transition-colors duration-300" href="#contact">Contact Us</a>
            </li>
          </ul>
        </div>
        <div>
          <h3 class="text-xl font-extrabold text-violet-500 mb-6">Newsletter</h3>
          <p class="mb-6 text-slate-400">
            Subscribe to get updates on new products and special offers.
          </p>
          <form aria-label="Newsletter subscription form" class="flex flex-col space-y-4">
            <input
              class="px-4 py-3 rounded-md border border-slate-700 bg-slate-800 text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500"
              placeholder="Your email address" required type="email" />
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


  <script src="assets/js/web.js">

  </script>

</body>

</html>