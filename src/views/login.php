<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login | IUBMDb</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Shared Theme Toggle -->
  <script defer src="/assets/js/theme.js"></script>
  <style>
    video {
      object-fit: cover;
    }
    .fade-in {
      animation: fadeIn 0.8s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
  </style>
</head>
<body id="pageBody" class="min-h-screen flex items-center justify-center transition duration-500 bg-[#0F172A] text-white">

  <div id="formContainer" class="w-full max-w-6xl flex rounded-xl shadow-lg overflow-hidden fade-in bg-[#1C1F33] transition duration-500">

    <!-- Video Section -->
    <div class="w-1/2 relative hidden md:block">
      <video autoplay loop muted preload="auto" class="absolute w-full h-full object-cover">
        <source src="/assets/login-bg/login-bg.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <div class="absolute top-4 left-4 z-10 text-white font-bold text-xl">IUBMDb</div>
      <div class="absolute bottom-6 left-6 z-10 text-white text-lg font-medium leading-snug">
        Discover. Rate. Watch.<br>Where Movie Lovers Belong.
      </div>
    </div>

    <!-- Login Form -->
    <div class="w-1/2 p-10 md:p-14 flex flex-col justify-center">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold">Log in</h2>
        <button id="themeToggle" class="text-sm bg-gray-700 px-3 py-1 rounded hover:bg-gray-600 transition">
          <span id="themeIcon">🌙</span> Toggle Theme
        </button>
      </div>

      <?php if (isset($_SESSION['login_error'])): ?>
        <div class="bg-red-500 text-white px-4 py-2 rounded mb-4">
          <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="/controllers/AuthController.php?action=login" class="space-y-5">
        <div>
          <label class="block text-sm font-medium mb-1">Your email</label>
          <input type="email" name="email" required class="w-full bg-gray-100 text-black border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-purple-500">
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Your password</label>
          <div class="relative">
            <input type="password" name="password" id="password" required class="w-full bg-gray-100 text-black border border-gray-300 rounded px-4 py-2 pr-10 focus:outline-none focus:border-purple-500">
            <span onclick="togglePassword()" class="absolute right-3 top-2.5 cursor-pointer text-black">🙈</span>
          </div>
        </div>

        <div class="flex items-center justify-between text-sm">
          <label class="flex items-center">
            <input type="checkbox" name="remember" class="mr-2"> Remember me
          </label>
          <a href="#" class="text-blue-400 hover:underline">Forgot password?</a>
        </div>

        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded">
          Log in
        </button>
      </form>

      <p class="mt-6 text-sm text-center text-gray-300">
        Don’t have an account?
        <a href="/views/register.php" class="text-blue-400 hover:underline">Register</a>
      </p>
    </div>
  </div>

  <script>
    function togglePassword() {
      const pwd = document.getElementById('password');
      pwd.type = pwd.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
