<?php
session_start();
require_once __DIR__ . '/../config/config.php';


if ($_GET['action'] === 'register') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role     = $_POST['role'];

    $check = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $_SESSION['error'] = "Email already registered.";
        header("Location: /views/register.php");
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $password, $role]);

    header("Location: /views/login.php?registered=1");
    exit;
}

if ($_GET['action'] === 'login') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

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

        if ($user['role'] === 'admin') {
            header("Location: /views/admin/dashboard.php");
        } else {
            header("Location: /views/user/dashboard.php");
        }
        exit;
    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
        header("Location: /views/login.php");
        exit;
    }
}
