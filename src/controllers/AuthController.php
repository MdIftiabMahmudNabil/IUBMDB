<?php
session_start();
require_once __DIR__ . '/../config/config.php';

function redirectWithError($msg, $path) {
    $_SESSION['error'] = $msg;
    header("Location: $path");
    exit;
}

if ($_GET['action'] === 'register') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        redirectWithError("All fields are required.", "/views/register.php");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectWithError("Invalid email address.", "/views/register.php");
    }

    $allowed_roles = ['user', 'admin'];
    if (!in_array($role, $allowed_roles)) {
        redirectWithError("Invalid role selected.", "/views/register.php");
    }

    $check = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        redirectWithError("Email already registered.", "/views/register.php");
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $hashed_password, $role]);

    header("Location: /views/login.php?registered=1");
    exit;
}

if ($_GET['action'] === 'login') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Please enter email and password.";
        header("Location: /views/login.php");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        if (isset($_POST['remember'])) {
            setcookie('remember_user', $user['id'], time() + (86400 * 30), "/");
        }

        $redirect = ($user['role'] === 'admin') ? "/views/admin/dashboard.php" : "/views/user/dashboard.php";
        header("Location: $redirect");
        exit;
    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: /views/login.php");
        exit;
    }
}
