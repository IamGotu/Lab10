<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
  $_SESSION['auth_status'] = "You need to be logged in to access this page";
  header('Location: ../loginform.php');
  exit();
}

// Specificaly admin access only
$required_role = 'admin';

// Check if the user has the required role
if ($_SESSION['role'] !== $required_role) {
    $_SESSION['auth_status'] = "You do not have permission to access this page";
    header('Location: ../logout.php');
    exit();
}

include('../database/db_conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteSubjectbtn'])) {
    $student_id = $_POST['student_id'];

    // Perform the deletion of the subject
    $delete_query = "UPDATE students SET subjects = NULL WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $student_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['auth_status'] = "Subject deleted successfully";
    } else {
        $_SESSION['auth_status'] = "Error deleting subject: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header('Location: ../TheAdmin/Subjects_enroll.php');
    exit();
} else {
    $_SESSION['auth_status'] = "Invalid request";
    header('Location: ../TheAdmin/Subjects_enroll.php');
    exit();
}
?>
