<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
require_once __DIR__ . '/../../config/config.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, profile_picture FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();


$apiKey = '34addef368644c5b44f086432426b1d9';
$tmdbUrl = "https://api.themoviedb.org/3/trending/movie/day?api_key=$apiKey";
$response = file_get_contents($tmdbUrl);
$trendingMovies = json_decode($response, true)['results'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>IUBMDb Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-color: #0F172A;
      font-family: 'Segoe UI', sans-serif;
    }
    .dropdown-enter {
      opacity: 0;
      transform: translateY(-10px);
    }
    .dropdown-enter-active {
      opacity: 1;
      transform: translateY(0);
      transition: all 0.3s ease-in-out;
    }
  </style>
</head>
<body class="text-white">


<header class="bg-[#0F172A] px-6 py-4 flex justify-between items-center border-b border-gray-600">
  <div class="flex items-center gap-4">
    <img src="/assets/logo/IUBMDblogo.gif" alt="IUBMDb Logo" class="w-10 h-10 rounded">
    <h1 class="text-yellow-400 font-bold text-xl">IUBMDb</h1>
  </div>

  <div class="flex items-center gap-6 relative">
    <a href="#" class="hover:underline">Watchlist</a>
    <div class="relative">
      
      <button id="profileToggle" class="flex items-center space-x-2 focus:outline-none">
        <img src="/assets/uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>"
             alt="Profile" class="w-10 h-10 rounded-full border-2 border-yellow-500 object-cover">
        <span class="font-semibold"><?php echo htmlspecialchars($user['username']); ?></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
      </button>

    
      <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-gray-800 text-white rounded-md shadow-lg hidden z-50">
        <a href="profile.php" class="block px-4 py-2 hover:bg-gray-700">Your profile</a>
        <a href="#" class="block px-4 py-2 hover:bg-gray-700">Your watchlist</a>
        <a href="#" class="block px-4 py-2 hover:bg-gray-700">Your ratings</a>
        <a href="#" class="block px-4 py-2 hover:bg-gray-700">Your lists</a>
        <a href="#" class="block px-4 py-2 hover:bg-gray-700">Account settings</a>
        <a href="/views/logout.php" class="block px-4 py-2 hover:bg-red-600">Sign out</a>
      </div>
    </div>
  </div>
</header>

<main class="p-8">

  <section class="mb-8">
    <h2 class="text-2xl text-yellow-400 font-bold mb-4">🔥 Up Next</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php foreach (array_slice($trendingMovies, 0, 3) as $movie): ?>
        <a href="movie.php?id=<?= $movie['id'] ?>" class="bg-[#1C1F33] rounded-lg overflow-hidden shadow hover:scale-105 transform transition-all duration-300">
          <img src="https://image.tmdb.org/t/p/w500<?= $movie['backdrop_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="text-lg font-bold text-white"><?= htmlspecialchars($movie['title']) ?></h3>
            <p class="text-sm text-gray-300"><?= substr($movie['overview'], 0, 100) ?>...</p>
            <div class="mt-2 flex items-center text-sm gap-4">
              <span class="text-yellow-400">⭐ <?= $movie['vote_average'] ?></span>
              <span class="text-pink-400">❤️ <?= $movie['vote_count'] ?></span>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </section>


  <section>
    <h2 class="text-2xl text-yellow-400 font-bold mb-4">🎬 Trending Today</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
      <?php foreach ($trendingMovies as $movie): ?>
        <a href="movie.php?id=<?= $movie['id'] ?>" class="transform hover:scale-105 transition duration-300">
          <div class="bg-[#1C1F33] rounded-lg overflow-hidden shadow">
            <img src="https://image.tmdb.org/t/p/w500<?= $movie['poster_path'] ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="w-full h-72 object-cover">
            <div class="p-2">
              <h4 class="text-sm font-semibold text-white"><?= htmlspecialchars($movie['title']) ?></h4>
              <div class="flex items-center justify-between mt-1 text-sm">
                <span class="text-yellow-400">⭐ <?= $movie['vote_average'] ?></span>
                <span class="text-pink-400">❤️ <?= $movie['vote_count'] ?></span>
              </div>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </section>
</main>

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
</script>
</body>
</html>
