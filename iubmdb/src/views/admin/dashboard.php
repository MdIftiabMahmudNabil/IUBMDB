<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /login.php");
    exit;
}

require_once "../../config/config.php";

$stmt = $pdo->prepare("SELECT username, profile_picture FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1C1F33] text-white min-h-screen">

  <header class="flex justify-between items-center px-6 py-4 bg-[#1C1F33] shadow-md">

    <a href="/views/admin/dashboard.php" class="flex items-center gap-3">
      <img src="/src/assets/Logo/IUBMDb Logo.gif" alt="IUBMDb Logo" class="w-12 h-12">
      <span class="text-xl font-bold">IUBMDb Admin</span>
    </a>


    <div class="relative">
      <div class="flex items-center gap-4">
        <h2 class="text-white font-medium">Welcome Admin, <?= htmlspecialchars($user['username']) ?></h2>
        <?php if (!empty($user['profile_picture'])): ?>
          <button id="profileToggle" class="focus:outline-none">
            <img class="w-10 h-10 rounded-full border-2 border-[#D33E43] object-cover" src="/assets/uploads/<?= $user['profile_picture'] ?>" alt="Profile">
          </button>
        <?php endif; ?>
      </div>

      <div id="dropdown" class="absolute hidden right-0 mt-2 w-48 bg-[#2c2c3e] text-white rounded shadow-lg z-10">
        <a href="/views/user/profile.php" class="block px-4 py-2 hover:bg-[#D33E43]">👤 Profile</a>
        <a href="/views/logout.php" class="block px-4 py-2 hover:bg-[#FF220C]">🚪 Logout</a>
      </div>
    </div>
  </header>


  <main class="p-8">
    <div class="bg-[#666370] p-6 rounded-lg shadow-md">
      <h2 class="text-xl font-semibold mb-2 text-white">Dashboard</h2>
      <p class="text-white">This is your admin panel. Manage users, movies, and site content.</p>
    </div>
  </main>

  <script>
    function toggleDropdown() {
      const dropdown = document.getElementById("dropdown");
      dropdown.classList.toggle("hidden");
    }

    document.getElementById("profileToggle")?.addEventListener("click", function (e) {
      e.stopPropagation();
      toggleDropdown();
    });

    window.addEventListener("click", function () {
      const dropdown = document.getElementById("dropdown");
      if (!dropdown.classList.contains("hidden")) {
        dropdown.classList.add("hidden");
      }
    });
  </script>
</body>
</html>