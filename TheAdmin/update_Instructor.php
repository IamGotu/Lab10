<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth'])) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: loginform.php');
    exit();
}

// Include necessary files
include('../database/db_conn.php');

if (isset($_POST['updateTeacher'])) {
    // Sanitize user input
    $teacher_id = mysqli_real_escape_string($conn, $_POST['edit_teacher_id']);
    $full_name = mysqli_real_escape_string($conn, $_POST['edit_full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['edit_email']);
    $address = mysqli_real_escape_string($conn, $_POST['edit_address']);
    $age = mysqli_real_escape_string($conn, $_POST['edit_age']);
    $gender = mysqli_real_escape_string($conn, $_POST['edit_gender']);
    $password = mysqli_real_escape_string($conn, $_POST['edit_password']);

    // Update teacher data in the database
    $update_query = "UPDATE teachers SET full_name='$full_name', email='$email', address='$address', age='$age', gender='$gender', password='$password' WHERE teacher_id='$teacher_id'";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        $_SESSION['status'] = "Teacher details updated successfully";
        header('Location: ../TheAdmin/Instructors.php');
        exit();
    } else {
        // Add error handling to display MySQL error
        $_SESSION['status'] = "Failed to update teacher details. Error: " . mysqli_error($conn);
        header('Location: ../TheAdmin/Instructors.php');
        exit();
    }
}
?>
