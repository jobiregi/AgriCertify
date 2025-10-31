<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullname = $_POST['fullname'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];

  $stmt = $pdo->prepare("UPDATE applicants SET fullname = ?, phone = ?, address = ? WHERE id = ?");
  if ($stmt->execute([$fullname, $phone, $address, $user_id])) {
    $success = "Profile updated successfully!";
  }
}

$stmt = $pdo->prepare("SELECT fullname, phone, address FROM applicants WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Update Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            nyeriGreen: '#006400',
            nyeriBeige: '#fdfaf6',
            nyeriBrown: '#4e342e'
          }
        }
      }
    }
  </script>
</head>
<body class="bg-nyeriBeige text-gray-800 min-h-screen flex flex-col">

  <!-- Navbar -->
  <nav class="bg-nyeriGreen text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <div>
        <h1 class="text-xl font-bold">☕ Nyeri County</h1>
        <p class="text-sm text-gray-200">Coffee Nursery Portal</p>
      </div>
      <div class="md:hidden">
        <button onclick="toggleMenu()" class="focus:outline-none">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
      <ul id="navLinks" class="hidden md:flex space-x-6 text-sm font-semibold">
        <li><a href="dashboard.php" class="hover:text-yellow-300">Home</a></li>
        <li><a href="form.php" class="hover:text-yellow-300">Apply</a></li>
        <li><a href="track.php" class="hover:text-yellow-300">Track</a></li>
        <li><a href="profile.php" class="text-yellow-300 border-b-2 border-white">Profile</a></li>
        <li><a href="logout.php" class="hover:text-red-300">Logout</a></li>
      </ul>
    </div>
    <div id="mobileLinks" class="md:hidden hidden px-4 pb-4 space-y-2">
      <a href="dashboard.php" class="block text-white hover:text-yellow-300">Home</a>
      <a href="form.php" class="block text-white hover:text-yellow-300">Apply</a>
      <a href="track.php" class="block text-white hover:text-yellow-300">Track</a>
      <a href="profile.php" class="block text-yellow-300 font-semibold">Profile</a>
      <a href="logout.php" class="block text-red-200 hover:text-red-400">Logout</a>
    </div>
  </nav>

  <!-- Profile Form Section -->
  <main class="flex-grow px-4 py-10">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-md">
      <h2 class="text-2xl font-bold text-nyeriGreen mb-6 text-center">Update Your Profile</h2>

      <?php if ($success): ?>
        <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded mb-4">
          <?= $success ?>
        </div>
      <?php endif; ?>

      <form method="POST" class="space-y-5">
        <div>
          <label class="block font-medium mb-1">Full Name</label>
          <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>"
                 class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-nyeriGreen" required>
        </div>

        <div>
          <label class="block font-medium mb-1">Phone</label>
          <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>"
                 class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-nyeriGreen" required>
        </div>

        <div>
          <label class="block font-medium mb-1">Address</label>
          <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>"
                 class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-nyeriGreen" required>
        </div>

        <button type="submit"
                class="w-full bg-nyeriGreen hover:bg-green-800 text-white font-semibold py-2 px-4 rounded transition">
          Save Changes
        </button>
      </form>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-100 text-center py-6 text-sm text-gray-600">
    &copy; 2025 County Government of Nyeri – All Rights Reserved
  </footer>

  <!-- JavaScript: Toggle Navbar -->
  <script>
    function toggleMenu() {
      document.getElementById('mobileLinks').classList.toggle('hidden');
    }
  </script>
</body>
</html>
