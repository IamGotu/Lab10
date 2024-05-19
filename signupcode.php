<?php
session_start();
include('config/db_conn.php');

if (isset($_POST['signup_btn'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL statement to insert data into the teachers table
    $query = "INSERT INTO teachers (full_name, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, "sss", $full_name, $email, $password);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION['status'] = "Account created successfully.";
        header('Location: loginform.php');
        exit();
    } else {
        $_SESSION['status'] = "Failed to create account. Please try again.";
        header('Location: signupform.php');
        exit();
    }
}
?>
