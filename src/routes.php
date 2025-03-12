<?php


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


$uri = trim($uri, '/');

$routes = [
    '' => 'views/user/dashboard.php',
    'login' => 'views/login.php',
    'register' => 'views/register.php',
    'logout' => 'views/logout.php',
    'profile' => 'views/user/profile.php',
    'admin' => 'views/admin/dashboard.php',
    'movie' => 'views/user/movie.php',
];


if (array_key_exists($uri, $routes)) {
    require_once __DIR__ . '/' . $routes[$uri];
} else {
    http_response_code(404);
    echo "<h1 style='color:white;background:black;text-align:center;'>404 - Page Not Found</h1>";
}
