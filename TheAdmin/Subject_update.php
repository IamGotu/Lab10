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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editSubjectbtn'], $_POST['student_id'], $_POST['subject'])) {
    $student_id = $_POST['student_id'];
    $subject = $_POST['subject'];

    // Update subjects field in student_regis table
    $update_query = "UPDATE student_regis SET subjects = ? WHERE student_id = ?";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $update_query);
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "si", $subject, $student_id);
    
    // Execute the statement
    $update_result = mysqli_stmt_execute($stmt);

    // Check if the update was successful
    if ($update_result) {
        $_SESSION['auth_status'] = "Subject updated successfully";
    } else {
        $_SESSION['auth_status'] = "Error updating subject: " . mysqli_error($conn);
    }

    // Redirect back to enroll_Subjects.php
    header('Location: ../TheAdmin/enroll_Subjects.php');
    exit();
} else {
    $_SESSION['auth_status'] = "Invalid request";
    header('Location: ../TheAdmin/enroll_Subjects.php');
    exit();
}
?>
