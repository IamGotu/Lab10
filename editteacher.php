<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth'])) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: loginform.php');
    exit();
}

// Include necessary files
include('config/db_conn.php');

if (isset($_POST['updateTeacher'])) {
    $teacher_id = $_POST['edit_teacher_id'];
    $full_name = $_POST['edit_name'];
    $email = $_POST['edit_email'];
    $password = $_POST['edit_password'];
    $address = $_POST['edit_address'];
    $age = $_POST['edit_age'];
    $gender = $_POST['edit_gender'];

    // Update teacher data in the database
    $update_query = "UPDATE teachers SET full_name='$full_name', email='$email', password='$password', address='$address', age='$age', gender='$gender' WHERE teacher_id='$teacher_id'";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        $_SESSION['status'] = "Teacher details updated successfully";
        header('Location: User_Profile.php');
        exit();
    } else {
        $_SESSION['status'] = "Failed to update teacher details. Please try again.";
        header('Location: User_Profile.php');
        exit();
    }
}
?>
