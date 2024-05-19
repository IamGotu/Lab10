<?php
session_start();
include('config/db_conn.php');

if(isset($_POST['login_btn'])) {
    $email = $_POST['student_id'];
    $password = $_POST['password'];

    // Prepare SQL statement
    $query = "SELECT * FROM teachers WHERE email = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);

    // Execute query
    mysqli_stmt_execute($stmt);

    // Get result
    $result = mysqli_stmt_get_result($stmt);

    // Check if user exists
    if(mysqli_num_rows($result) == 1) {
        // Fetch user data
        $row = mysqli_fetch_assoc($result);

        // Store user data in session
        $_SESSION['auth'] = true;
        $_SESSION['auth_user'] = $row;

        // Redirect to user profile page
        header('Location: User_Profile.php');
        exit();
    } else {
        // Invalid credentials, redirect to login page with error message
        $_SESSION['auth_status'] = "Invalid email or password";
        header('Location: loginform.php');
        exit();
    }
} else {
    // If login button not pressed, redirect to login page
    header('Location: loginform.php');
    exit();
}
?>
