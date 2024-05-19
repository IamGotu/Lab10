<?php
session_start();
include('config/db_conn.php');

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
        $_SESSION['status'] = "Subject updated successfully";
    } else {
        $_SESSION['status'] = "Error updating subject: " . mysqli_error($conn);
    }

    // Redirect back to enrollSubject.php
    header('Location: enrollSubject.php');
    exit();
} else {
    $_SESSION['status'] = "Invalid request";
    header('Location: enrollSubject.php');
    exit();
}
?>
