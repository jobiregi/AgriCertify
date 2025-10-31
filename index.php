<?php
session_start();
$isLoggedIn = isset($_SESSION['user_name']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AgriCertify - Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    html {
      scroll-behavior: smooth;
    }
  </style>
</head>
<body class="bg-gradient-to-b from-green-50 via-white to-white text-gray-800 font-sans">

  <!-- Navbar -->
  <nav class="bg-white shadow-md py-4 px-6 md:px-10 flex justify-between items-center sticky top-0 z-50">
    <div class="text-2xl md:text-3xl font-bold text-green-700">AgriCertify</div>
    <div class="space-x-4 text-base md:text-lg flex items-center">
      <a href="index.php" class="text-green-700 hover:text-green-900 font-medium transition duration-150">Home</a>
      
      <?php if ($isLoggedIn): ?>
        <span class="text-gray-600 hidden md:inline">Welcome, <strong><?= htmlspecialchars($userName) ?></strong></span>
        <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition duration-150">Logout</a>
      <?php else: ?>
        <a href="register.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition duration-150">Register</a>
        <a href="login.php" class="border border-green-600 text-green-700 hover:bg-green-600 hover:text-white px-4 py-2 rounded transition duration-150">Login</a>
      <?php endif; ?>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="text-center py-24 px-6 bg-green-100 rounded-b-3xl shadow-inner">
    <h1 class="text-4xl md:text-5xl font-extrabold text-green-900 mb-6 leading-tight">
      Welcome to AgriCertify
    </h1>
    <p class="text-lg md:text-xl text-gray-700 max-w-3xl mx-auto">
      Empowering farmers with digital certification tools. Begin your journey towards compliance, recognition, and growth in modern agriculture.
    </p>
  </section>

  <!-- Services Section -->
  <section class="grid grid-cols-1 md:grid-cols-2 gap-10 px-6 py-20 max-w-6xl mx-auto">
    <!-- Coffee Certification Card -->
    <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 border-l-4 border-green-600">
      <div class="flex items-center gap-3 mb-4">
        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M12 2L2 12h3v7h7v-3h2v3h7v-7h3z"/>
        </svg>
        <h2 class="text-2xl font-bold text-green-800">Coffee Nursery Certification</h2>
      </div>
      <p class="text-gray-600 mb-6 text-base md:text-lg">
        Get certified to run a compliant coffee nursery and support high-quality coffee farming.
      </p>
      <a href="coffee-certification.php" class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition">
        Explore Certification
      </a>
    </div>

    <!-- Dairy Certification Card -->
    <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 border-l-4 border-green-600">
      <div class="flex items-center gap-3 mb-4">
        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <h2 class="text-2xl font-bold text-green-800">Dairy Certification</h2>
      </div>
      <p class="text-gray-600 mb-6 text-base md:text-lg">
        Ensure your dairy operations meet current health, safety, and quality regulations.
      </p>
      <a href="dairy-certification.php" class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition">
        Explore Certification
      </a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="text-center text-gray-500 text-sm py-6 border-t">
    &copy; 2025 AgriCertify. All rights reserved.
  </footer>

</body>
</html>
