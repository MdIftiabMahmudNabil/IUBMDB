<?php
session_start();
require_once __DIR__ . '/../config/config.php';

if (isset($_POST['update_profile'])) {
    $user_id = $_SESSION['user_id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $bio = trim($_POST['bio']);
    $profile_picture = null;

    $stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($_FILES['profile_picture']['name'])) {
        $ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG files are allowed.";
            header("Location: /views/user/profile.php");
            exit;
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
            $_SESSION['error'] = "File upload failed.";
            header("Location: /views/user/profile.php");
            exit;
        }
    }

    $query = "UPDATE users SET username = ?, email = ?, bio = ?";
    $params = [$username, $email, $bio];

    if (!empty($_POST['new_password'])) {
        $password_hash = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
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

    $_SESSION['success'] = "Profile updated successfully.";
    header("Location: /views/user/profile.php");
    exit;
}

if (isset($_POST['delete_account'])) {
    $user_id = $_SESSION['user_id'];

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