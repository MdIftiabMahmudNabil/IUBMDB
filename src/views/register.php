<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register | IUBMDb</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Theme & UI Enhancements -->
  <script defer src="/assets/js/theme.js"></script>
  <style>
    video { object-fit: cover; }
    .fade-in { animation: fadeIn 0.8s ease-in-out; }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center transition-all duration-500 bg-[#0F172A] text-white">

  <div class="w-full max-w-6xl flex rounded-xl shadow-lg overflow-hidden fade-in" id="formContainer">
    
    <!-- Video Section -->
    <div class="w-1/2 relative cursor-pointer" onclick="switchVideo()">
      <video id="registerVideo" class="w-full h-full" autoplay muted loop preload="auto">
        <source src="/assets/signup-bg/scene1.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <div class="absolute top-4 left-4 text-white text-xl font-bold z-10">IUBMDb</div>
      <div id="videoCaption" class="absolute bottom-6 left-6 text-white text-lg font-semibold z-10">
        Where Every Frame Finds Its Voice,<br>Uncover the stories behind the screen — one scene at a time
      </div>
    </div>

    <!-- Registration Form -->
    <div class="w-1/2 p-10 md:p-14 flex flex-col justify-center">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold">Create an account</h2>
        <button id="themeToggle" class="text-sm bg-gray-700 px-3 py-1 rounded hover:bg-gray-600 transition">
          <span id="themeIcon">🌙</span> Toggle Theme
        </button>
      </div>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="text-red-400 mb-4"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
      <?php endif; ?>

      <form method="POST" action="/controllers/AuthController.php?action=register" class="space-y-4">
        <div>
          <label class="block mb-1 text-sm">Username</label>
          <input type="text" name="username" required class="w-full px-4 py-2 rounded bg-gray-100 text-black">
        </div>

        <div>
          <label class="block mb-1 text-sm">Email</label>
          <input type="email" name="email" required class="w-full px-4 py-2 rounded bg-gray-100 text-black">
        </div>

        <div class="relative">
          <label class="block mb-1 text-sm">Password</label>
          <input type="password" name="password" id="password" required class="w-full px-4 py-2 pr-10 rounded bg-gray-100 text-black">
          <span onclick="togglePassword('password')" class="absolute right-3 top-9 cursor-pointer">🙈</span>
        </div>

        <div class="relative">
          <label class="block mb-1 text-sm">Confirm Password</label>
          <input type="password" name="confirm_password" id="confirm_password" required class="w-full px-4 py-2 pr-10 rounded bg-gray-100 text-black">
          <span onclick="togglePassword('confirm_password')" class="absolute right-3 top-9 cursor-pointer">🙈</span>
        </div>

        <div>
          <label class="block mb-1 text-sm">Select Role</label>
          <select name="role" required class="w-full px-4 py-2 rounded bg-gray-100 text-black">
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <div class="flex items-center space-x-2">
          <input type="checkbox" required class="accent-purple-600">
          <label class="text-sm">I agree to the <a href="#" class="underline">Terms & Conditions</a></label>
        </div>

        <button type="submit" class="w-full py-2 rounded bg-purple-600 hover:bg-purple-700 text-white">
          Create account
        </button>
      </form>

      <p class="mt-6 text-sm text-center text-gray-300">
        Already have an account? <a href="/views/login.php" class="text-blue-400 hover:underline">Log in</a>
      </p>
    </div>
  </div>

  <!-- Video switch script -->
  <script>
    const videos = [
      {
        src: '/assets/signup-bg/scene1.mp4',
        caption: 'Where Every Frame Finds Its Voice,<br>Uncover the stories behind the screen — one scene at a time'
      },
      {
        src: '/assets/signup-bg/scene2.mp4',
        caption: 'Your Gateway to Cinematic Journeys,<br>From timeless classics to tomorrow’s blockbusters — all in one place'
      }
    ];
    let currentIndex = 0;

    function switchVideo() {
      currentIndex = (currentIndex + 1) % videos.length;
      const video = document.getElementById('registerVideo');
      const caption = document.getElementById('videoCaption');
      video.src = videos[currentIndex].src;
      caption.innerHTML = videos[currentIndex].caption;
      video.play();
    }

    setInterval(switchVideo, 6000);

    function togglePassword(id) {
      const input = document.getElementById(id);
      input.type = input.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
