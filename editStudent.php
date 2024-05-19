<?php
session_start();
include('config/db_conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editUserbtn'])) {
    $student_id = $_POST['edit_student_id'];
    $name = $_POST['edit_name'];
    $email = $_POST['edit_email'];
    $course = $_POST['edit_course'];

    // Update student's information in the database
    $update_query = "UPDATE student_regis SET full_name = ?, email = ?, course = ? WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $course, $student_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['status'] = "Student information updated successfully";
    } else {
        $_SESSION['status'] = "Error updating student information: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header('Location: student_list.php'); // Redirect back to the index page
    exit();
} else {
    echo "Form not submitted correctly.";
}
?>
