<?php
// authenticate.php

session_start();
include('db_connection.php'); // Include DB connection

// Get input data
$username = $_POST['username'];
$password = $_POST['password'];

// Query to check if user exists
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

// Check if user exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        
        // Redirect to a dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        // Invalid password
        echo "Invalid password!";
    }
} else {
    // User not found
    echo "User not found!";
}

$conn->close();
?>
