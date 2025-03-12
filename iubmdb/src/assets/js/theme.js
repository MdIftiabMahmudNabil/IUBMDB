// theme.js

document.addEventListener("DOMContentLoaded", function () {
    const body = document.body;
    const themeToggle = document.getElementById("themeToggle");
    const themeIcon = document.getElementById("themeIcon");
  
    function applyTheme(theme) {
      const isDark = theme === "dark";
  
      body.classList.toggle("bg-gray-100", !isDark);
      body.classList.toggle("text-gray-900", !isDark);
      body.classList.toggle("bg-[#0F172A]", isDark);
      body.classList.toggle("text-white", isDark);
  
      const formContainer = document.getElementById("formContainer");
      if (formContainer) {
        formContainer.classList.toggle("bg-white", !isDark);
        formContainer.classList.toggle("bg-[#1C1F33]", isDark);
      }
  
      themeIcon.textContent = isDark ? "🌙" : "🌞";
      localStorage.setItem("theme", theme);
    }
  
    // Load persisted theme
    const savedTheme = localStorage.getItem("theme") || "dark";
    applyTheme(savedTheme);
  
    // Toggle handler
    if (themeToggle) {
      themeToggle.addEventListener("click", () => {
        const newTheme = localStorage.getItem("theme") === "dark" ? "light" : "dark";
        applyTheme(newTheme);
      });
    }
  });
  