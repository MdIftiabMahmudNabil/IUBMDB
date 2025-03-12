<?php
session_start();
require_once "../../config/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: /views/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Your Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toggleBtn = document.getElementById('profileToggle');
      const dropdown = document.getElementById('profileDropdown');

      toggleBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
      });

      document.addEventListener('click', function () {
        dropdown.classList.add('hidden');
      });
    });

    function confirmDelete() {
      return confirm('Are you sure you want to permanently delete your account? This cannot be undone.');
    }
  </script>
</head>
<body class="bg-[#1C1F33] text-white min-h-screen">

<header class="bg-[#1C1F33] shadow-md px-6 py-4 flex justify-between items-center">
  <div class="flex items-center gap-4">
    <img src="/assets/logo/IUBMDblogo.gif" alt="Logo" class="w-12 h-12 rounded-full">
    <button class="text-white text-lg font-semibold">☰ Menu</button>
    <div class="flex items-center bg-white rounded ml-4 overflow-hidden">
      <select class="text-sm px-2 py-1 bg-gray-200">
        <option>All</option>
      </select>
      <input type="text" placeholder="Search IUBMDb" class="px-3 py-1 focus:outline-none">
      <button class="px-3 text-gray-600">🔍</button>
    </div>
  </div>
  <div class="relative">
    <div class="flex items-center gap-4">
      <a href="#" class="text-white font-medium">Watchlist</a>
      <span class="text-white font-medium">Welcome, <?= htmlspecialchars($user['username']) ?></span>
      <button id="profileToggle" class="focus:outline-none">
        <?php if (!empty($user['profile_picture'])): ?>
          <img src="/assets/uploads/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile" class="w-10 h-10 rounded-full border-2 border-[#D33E43] object-cover">
        <?php endif; ?>
      </button>
    </div>
    <div id="profileDropdown" class="absolute hidden right-0 mt-2 w-48 bg-[#1C1F33] text-white rounded shadow-lg z-10">
      <a href="profile.php" class="block px-4 py-2 hover:bg-[#D33E43]">Your profile</a>
      <a href="#" class="block px-4 py-2 hover:bg-[#D33E43]">Your watchlist</a>
      <a href="#" class="block px-4 py-2 hover:bg-[#D33E43]">Your ratings</a>
      <a href="#" class="block px-4 py-2 hover:bg-[#D33E43]">Your lists</a>
      <a href="#" class="block px-4 py-2 hover:bg-[#D33E43]">Account settings</a>
      <a href="/views/logout.php" class="block px-4 py-2 hover:bg-[#FF220C]">Sign out</a>
    </div>
  </div>
</header>

<main class="p-8 max-w-2xl mx-auto">
  <h2 class="text-2xl font-semibold mb-6">Edit Profile</h2>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-600 text-white px-4 py-2 rounded mb-4">
      <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-600 text-white px-4 py-2 rounded mb-4">
      <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="/controllers/ProfileController.php" enctype="multipart/form-data" class="bg-[#2A2D45] p-6 rounded-lg shadow-md space-y-4">
    <div>
      <label class="block mb-1 text-sm">Username</label>
      <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="w-full px-4 py-2 rounded bg-gray-200 text-black">
    </div>

    <div>
      <label class="block mb-1 text-sm">Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="w-full px-4 py-2 rounded bg-gray-200 text-black">
    </div>

    <div>
      <label class="block mb-1 text-sm">Change Password (optional)</label>
      <input type="password" name="password" placeholder="New Password" class="w-full px-4 py-2 rounded bg-gray-200 text-black">
    </div>

    <div>
      <label class="block mb-1 text-sm">Bio</label>
      <textarea name="bio" rows="3" class="w-full px-4 py-2 rounded bg-gray-200 text-black"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
    </div>

    <div>
      <label class="block mb-1 text-sm">Profile Picture</label>
      <?php if ($user['profile_picture']): ?>
        <img src="/assets/uploads/<?= $user['profile_picture'] ?>" width="100" class="mb-2 rounded border border-gray-400">
      <?php endif; ?>
      <input type="file" name="profile_picture" accept=".jpg,.jpeg,.png" class="text-sm text-gray-300">
    </div>

    <div class="flex gap-4 items-center">
      <button type="submit" name="update_profile" class="bg-[#D33E43] hover:bg-[#FF220C] text-white px-4 py-2 rounded">
        Save Changes
      </button>

      <form method="POST" action="/controllers/ProfileController.php" onsubmit="return confirmDelete();">
        <button type="submit" name="delete_account" class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded">
          Delete Account
        </button>
      </form>
    </div>
  </form>
</main>
</body>
</html>
