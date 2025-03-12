<?php
session_start();
require_once __DIR__ . '/../config/config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: /views/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

function redirectWithMessage($type, $message) {
    $_SESSION[$type] = $message;
    header("Location: /views/user/profile.php");
    exit;
}


if (isset($_POST['update_profile'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $bio      = trim($_POST['bio']);
    $new_pass = $_POST['new_password'] ?? '';
    $profile_picture = null;


    if (empty($username) || empty($email)) {
        redirectWithMessage('error', 'Username and email are required.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectWithMessage('error', 'Invalid email format.');
    }

    
    $stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if (!empty($_FILES['profile_picture']['name'])) {
        $ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];
        $mimeType = mime_content_type($_FILES['profile_picture']['tmp_name']);

        if (!in_array($ext, $allowed) || !str_starts_with($mimeType, 'image/')) {
            redirectWithMessage('error', 'Only JPG, JPEG, PNG image files are allowed.');
        }

        $profile_picture = uniqid('profile_') . '.' . $ext;
        $upload_path = __DIR__ . '/../assets/uploads/' . $profile_picture;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
            
            if (!empty($existing['profile_picture'])) {
                $old_path = __DIR__ . '/../assets/uploads/' . $existing['profile_picture'];
                if (file_exists($old_path)) {
                    unlink($old_path);
                }
            }
        } else {
            redirectWithMessage('error', 'Failed to upload image. Please try again.');
        }
    }

    
    $query = "UPDATE users SET username = ?, email = ?, bio = ?";
    $params = [$username, $email, $bio];

    if (!empty($new_pass)) {
        $password_hash = password_hash($new_pass, PASSWORD_BCRYPT);
        $query .= ", password = ?";
        $params[] = $password_hash;
    }

    if ($profile_picture) {
        $query .= ", profile_picture = ?";
        $params[] = $profile_picture;
    }

    $query .= " WHERE id = ?";
    $params[] = $user_id;

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    redirectWithMessage('success', 'Profile updated successfully.');
}


if (isset($_POST['delete_account'])) {
    $stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if (!empty($user['profile_picture'])) {
        $profile_path = __DIR__ . '/../assets/uploads/' . $user['profile_picture'];
        if (file_exists($profile_path)) {
            unlink($profile_path);
        }
    }

    
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    session_destroy();
    header("Location: /views/login.php");
    exit;
}
?>
