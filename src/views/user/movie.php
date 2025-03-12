<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../config/config.php';

$movieId = $_GET['id'] ?? null;
if (!$movieId) {
    echo "Movie ID not specified.";
    exit;
}

$apiKey = '34addef368644c5b44f086432426b1d9';
$movieUrl = "https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=en-US";
$trailerUrl = "https://api.themoviedb.org/3/movie/{$movieId}/videos?api_key={$apiKey}&language=en-US";
$castUrl = "https://api.themoviedb.org/3/movie/{$movieId}/credits?api_key={$apiKey}&language=en-US";
$similarUrl = "https://api.themoviedb.org/3/movie/{$movieId}/similar?api_key={$apiKey}&language=en-US";

$movie = json_decode(file_get_contents($movieUrl), true);
$trailerResponse = json_decode(file_get_contents($trailerUrl), true);
$trailerKey = $trailerResponse['results'][0]['key'] ?? null;

$castResponse = json_decode(file_get_contents($castUrl), true);
$cast = array_slice($castResponse['cast'], 0, 10);

$similarMovies = json_decode(file_get_contents($similarUrl), true)['results'];

function getImageUrl($path, $size = 'w500') {
    return "https://image.tmdb.org/t/p/{$size}{$path}";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?php echo htmlspecialchars($movie['title']); ?> | IUBMDb</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs" defer></script>
</head>
<body class="bg-[#0F172A] text-white min-h-screen px-6 py-10">
  <div class="max-w-6xl mx-auto space-y-10">
    <!-- Movie Banner -->
    <div class="relative">
      <img src="<?php echo getImageUrl($movie['backdrop_path'], 'original'); ?>" class="w-full h-[500px] object-cover rounded-xl shadow-lg">
      <div class="absolute inset-0 bg-black/60 flex flex-col justify-end p-6 rounded-xl">
        <h1 class="text-4xl font-bold"><?php echo $movie['title']; ?></h1>
        <p class="mt-2 text-gray-300 max-w-2xl"><?php echo $movie['overview']; ?></p>
        <div class="mt-4 flex gap-4">
          <form method="POST" action="/controllers/WatchlistController.php">
            <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
            <input type="hidden" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>">
            <input type="hidden" name="poster_path" value="<?php echo $movie['poster_path']; ?>">
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 px-4 py-2 rounded font-medium text-black">
              ➕ Add to Watchlist
            </button>
          </form>
          <?php if ($trailerKey): ?>
            <button @click="trailerOpen = true" class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded font-medium">
              ▶️ Watch Trailer
            </button>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Trailer Modal -->
    <?php if ($trailerKey): ?>
      <div x-data="{ trailerOpen: false }" x-show="trailerOpen" style="display: none"
           class="fixed inset-0 bg-black/80 flex items-center justify-center z-50">
        <div class="bg-black w-full max-w-3xl rounded-xl overflow-hidden shadow-lg relative">
          <iframe width="100%" height="400"
                  src="https://www.youtube.com/embed/<?php echo $trailerKey; ?>"
                  frameborder="0" allowfullscreen></iframe>
          <button @click="trailerOpen = false" class="absolute top-2 right-2 text-white text-xl">✖</button>
        </div>
      </div>
    <?php endif; ?>

    <!-- Cast -->
    <div>
      <h2 class="text-2xl font-semibold mb-4">Top Cast</h2>
      <div class="grid grid-cols-2 sm:grid-cols-5 gap-6">
        <?php foreach ($cast as $actor): ?>
          <div class="text-center">
            <img src="<?php echo getImageUrl($actor['profile_path']); ?>" alt="<?php echo $actor['name']; ?>"
                 class="w-full h-36 object-cover rounded-lg shadow">
            <p class="mt-2 font-medium"><?php echo $actor['name']; ?></p>
            <p class="text-sm text-gray-400"><?php echo $actor['character']; ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Similar Movies -->
    <div>
      <h2 class="text-2xl font-semibold mb-4">Recommended</h2>
      <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
        <?php foreach (array_slice($similarMovies, 0, 12) as $sim): ?>
          <a href="movie.php?id=<?php echo $sim['id']; ?>" class="hover:scale-105 transition duration-300">
            <img src="<?php echo getImageUrl($sim['poster_path']); ?>" class="w-full h-48 object-cover rounded-lg shadow">
            <p class="mt-1 text-sm"><?php echo $sim['title']; ?></p>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</body>
</html>
