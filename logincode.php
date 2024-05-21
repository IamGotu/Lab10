<?php
session_start();
include('database/db_conn.php');

if(isset($_POST['login_btn'])) {
    $email = $_POST['student_id'];
    $password = $_POST['password'];

    // Define an array of tables and their corresponding redirect URLs
    $tables = [
        'admin' => '../TheAdmin/admin_Home.php',
        'teachers' => '../TheTeachers/teachers_Home.php',
        'student_list' => '../TheStudents/student_Home.php'
    ];

    // Loop through each table to check for valid credentials
    foreach ($tables as $table => $redirect_url) {
        // Prepare SQL statement
        $query = "SELECT * FROM $table WHERE email = ? AND password = ?";
        $stmt = mysqli_prepare($conn, $query);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);

        // Execute query
        mysqli_stmt_execute($stmt);

        // Get result
        $result = mysqli_stmt_get_result($stmt);

        // Check if user exists
        if (mysqli_num_rows($result) == 1) {
            // Fetch user data
            $row = mysqli_fetch_assoc($result);

            // Store user data in session
            $_SESSION['auth'] = true;
            $_SESSION['auth_user'] = $row;

            // Redirect to the corresponding user profile page
            header("Location: $redirect_url");
            exit();
        }
    }

    // Invalid credentials, redirect to login page with error message
    $_SESSION['auth_status'] = "Invalid email or password";
    header('Location: loginform.php');
    exit();
} else {
    // If login button not pressed, redirect to login page
    header('Location: loginform.php');
    exit();
}
?>
