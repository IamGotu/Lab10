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

if ( isset($_POST['updateStudent'])) {
    // Sanitize user input
    $student_id = mysqli_real_escape_string($conn, $_POST['edit_student_id']);
    $full_name = mysqli_real_escape_string($conn, $_POST['edit_full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['edit_email']);
    $address = mysqli_real_escape_string($conn, $_POST['edit_address']);
    $age = mysqli_real_escape_string($conn, $_POST['edit_age']);
    $gender = mysqli_real_escape_string($conn, $_POST['edit_gender']);
    $course = mysqli_real_escape_string($conn, $_POST['edit_course']);
    $password = mysqli_real_escape_string($conn, $_POST['edit_password']);

    // Update student's information in the database
    $update_query = "UPDATE student_list SET full_name='$full_name', email='$email', address='$address', age='$age', gender='$gender', course='$course', password='$password' WHERE student_id='$student_id'";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        $_SESSION['status'] = "Student details updated successfully";
        header('Location: ../TheAdmin/Students.php');
        exit();
    } else {
        $_SESSION['status'] = "Failed to update student details. Please try again.";
        header('Location: ../TheAdmin/Students.php');
        exit();
    }
}
?>
