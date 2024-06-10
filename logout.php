<?php
session_start();

include ('database/db_conn.php');

if (isset($_SESSION['email'])) {
    // Update user status to 'Offline'
    $email = $_SESSION['email'];
    $update_status_query = "UPDATE users SET active = 'Offline' WHERE email = ?";
    $update_status_stmt = $conn->prepare($update_status_query);
    $update_status_stmt->bind_param("s", $email);
    $update_status_stmt->execute();
}

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: loginform.php');
exit();
?>
