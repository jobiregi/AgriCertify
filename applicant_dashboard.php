<?php
session_start();

// Redirect to login if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Get username for display (or fallback)
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Applicant";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Applicant Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            nyeriGreen: '#006400',
            nyeriBrown: '#4e342e',
            nyeriBeige: '#fdfaf6'
          }
        }
      }
    }
  </script>
</head>
<body>
<body class="bg-[url('picture.webp')] bg-cover bg-center text-gray-800 min-h-screen flex flex-col">
  <!-- Navbar -->
  <nav class="bg-nyeriGreen text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <div>
        <img src="agriculture logo.jpg" alt="Nyeri County Logo" class="h-12 w-12 inline-block mr-2">
        <h1 class="text-xl font-bold">County Government Of Nyeri</h1>
        <p class="text-sm text-gray-200">Applicant Portal</p>
      </div>
      <div class="md:hidden">
        <button onclick="toggleMenu()" class="focus:outline-none">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
      <ul id="navLinks" class="hidden md:flex space-x-6 text-sm font-semibold">
        <li class="relative group">
          <a href="#" class="hover:text-yellow-300 border-b-2 border-white">Home</a>
        </li>
        <li class="relative group">
          <button onclick="toggleDropdown('applyDropdown')" class="hover:text-yellow-300">Apply</button>
          <div id="applyDropdown" class="absolute hidden bg-white text-gray-800 mt-2 py-2 px-4 shadow rounded w-48 z-10">
            <a href="coffee_nursery_form.html" class="block hover:text-nyeriGreen">Coffee Nursery Certificate</a>
            <a href="commercial coffee milling form.html" class="block hover:text-nyeriGreen">Commercial Coffee Milling Licence</a>
            <a href="warehouse form.html" class="block hover:text-nyeriGreen">Warehouse</a>
            <a href="coffee roaster licence.html" class="block hover:text-nyeriGreen">Coffee Roaster Licence</a>
            <a href="coffee grower notification.html" class="block hover:text-nyeriGreen">Coffee Growers Notification</a>
            <a href="pulping station.html" class="block hover:text-nyeriGreen">Pulping station</a>
            <a href="grower miller licence.html" class="block hover:text-nyeriGreen">Grower Miller Licence</a>
          </div>
        </li>
        <li><a href="track.html" class="hover:text-yellow-300">Track</a></li>
        <li class="relative group">
          <button onclick="toggleDropdown('profileDropdown')" class="hover:text-yellow-300">Profile</button>
          <div id="profileDropdown" class="absolute hidden bg-white text-gray-800 mt-2 py-2 px-4 shadow rounded w-48 z-10">
            <a href="update_profile.html" class="block hover:text-nyeriGreen">Update Profile</a>
          </div>
        </li>
        <li><a href="dashboard.php?logout=true" class="hover:text-red-300" onclick="return confirmLogout()">Logout</a></li>
      </ul>
    </div>
  </nav>

  <!-- Hero Section -->
  <header class="bg-[url('')] bg-cover bg-center text-black py-24 text-center px-4">
    <h2 class="text-4xl font-bold">Welcome <?php echo htmlspecialchars($username); ?> 👋</h2>
    <p class="mt-4 max-w-3xl mx-auto text-lg">
      Your personalized dashboard for managing all aspects of your certification journey. 
      Here, you can update your profile information, begin a new application or monitor the progress of your existing submissions.
      <br>Let’s grow excellence one coffee seedling at a time.</br>
    </p>
  </header>

   <!-- Footer -->
  <footer class="bg-text-black text-center py-6 mt-auto">
    &copy; 2025 County Government of Nyeri – All Rights Reserved
  </footer>
</body>

  <script>
    function toggleMenu() {
      document.getElementById('mobileLinks').classList.toggle('hidden');
    }

    function confirmLogout() {
      return confirm('Are you sure you want to log out?');
    }

    function toggleDropdown(id) {
      const dropdown = document.getElementById(id);
      const isHidden = dropdown.classList.contains('hidden');
      document.querySelectorAll('[id$="Dropdown"]').forEach(el => el.classList.add('hidden'));
      if (isHidden) dropdown.classList.remove('hidden');
    }
  </script>
</html>
