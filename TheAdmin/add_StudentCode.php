<?php
session_start();
include('../database/db_conn.php');

// Check if the form was submitted to add a student
if (isset($_POST['addStudent'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $password = '12345';

    $insert_query = "INSERT INTO student_list (full_name, email, password, address, age, gender, course) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $insert_query);
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $course);
    
    // Execute the statement
    $insert_result = mysqli_stmt_execute($stmt);

    // Check if the insert was successful
    if ($insert_result) {
        $_SESSION['status'] = "Student added successfully";
    } else {
        $_SESSION['status'] = "Error adding student: " . mysqli_error($conn);
    }

    header('Location: ../TheAdmin/student_list.php');
    exit();
}

// Check if the form was submitted to delete a student
if (isset($_POST['deleteUserbtn'])) {
    // Get the ID of the student to be deleted
    $delete_id = $_POST['delete_student_id'];
    
    // Construct the SQL query to delete the student from the database
    $delete_query = "DELETE FROM student_list WHERE student_id = ?";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $delete_query);
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "s", $delete_id);
    
    // Execute the delete query
    $delete_result = mysqli_stmt_execute($stmt);

    // Check if the delete was successful
    if ($delete_result) {
        $_SESSION['status'] = "Student deleted successfully";
    } else {
        $_SESSION['status'] = "Error deleting student: " . mysqli_error($conn);
    }

    header('Location: ../TheAdmin/student_list.php');
    exit();
}

// If the form was not submitted to add or delete a student, redirect back to the student_list.php page
header('Location: ../TheAdmin/student_list.php');
exit();
?>
