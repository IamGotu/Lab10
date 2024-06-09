<?php
session_start();
include('../database/db_conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteSubjectbtn'])) {
    $student_id = $_POST['student_id'];

    // Perform the deletion of the subject
    $delete_query = "UPDATE students SET subjects = NULL WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $student_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['status'] = "Subject deleted successfully";
    } else {
        $_SESSION['status'] = "Error deleting subject: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header('Location: ../TheAdmin/Subjects_enroll.php');
    exit();
} else {
    $_SESSION['status'] = "Invalid request";
    header('Location: ../TheAdmin/Subjects_enroll.php');
    exit();
}
?>
