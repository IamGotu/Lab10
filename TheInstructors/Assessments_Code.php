<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: ../loginform.php');
    exit();
}

// Specifically instructor access only
$required_role = 'instructor';

// Check if the user has the required role
if ($_SESSION['role'] !== $required_role) {
    $_SESSION['auth_status'] = "You do not have permission to access this page";
    header('Location: ../logout.php');
    exit();
}

// Include file for database connection
include('../database/db_conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    
    if (empty($title) || empty($description)) {
        $_SESSION['auth_status'] = "Title and description cannot be empty.";
        header('Location: Assessments_Create.php?error=Title and description cannot be empty.');
        exit();
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO assessments (title, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $description);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['auth_status'] = "New assessment created successfully";
        header('Location: Assessments_Create.php');
        exit();      
    } else {
        $_SESSION['auth_status'] = "Assessment failed to create. Please try again later.";
        header('Location: Assessments_Create.php?error=Assessment failed to create. Please try again later.');
        exit();
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
