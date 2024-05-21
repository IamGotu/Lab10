<?php
session_start();
// Include file for database connection
include('../database/db_conn.php');

// Check if the request method is POST
if(isset($_POST['UpdateTeacher'])) {
    // Function to validate input data
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Ensure fields are initialized
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $full_name = validate($_POST['full_name']);
    $address = validate($_POST['address']);

    // Check if email already exists
    $check_email_query = "SELECT * FROM teachers WHERE email=?";
    $stmt = mysqli_prepare($conn, $check_email_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    if($count > 0) {
        $_SESSION['status'] = "Email already exists"; // Set error message
        header('Location: ../TheAdmin/instructors.php');
        exit(0);
    }

    // Construct SQL query for updating teacher profile
    $sql = "UPDATE teachers SET email = ?, password = ?, full_name = ?, address = ? WHERE teacher_id = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ssssi", $email, $password, $full_name, $address);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['status'] = "Teacher Update Successfully"; // Set success message
        header('Location: ../TheAdmin/instructors.php');
        exit(0);
    } else {
        $_SESSION['status'] = "Teacher Updating Failed";
        header('Location: ../TheAdmin/instructors.php');
        exit(0);
    }
} else {
    // Display an error message if the form was not submitted
    $_SESSION['status'] = "Teacher Updating Failed";
    header("Location: ../TheAdmin/instructors.php");
    exit(0);
}
?>
