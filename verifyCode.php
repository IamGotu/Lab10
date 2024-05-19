<?php
session_start();

// Include file for database connection
include('config/db_conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the verification code and email from the form
    $Token = $_POST['Token'];
    $email = $_POST['email'];

    // SQL query to check if the verification code matches the one stored in the database
    $sql = "SELECT * FROM student_regis WHERE Email='$email' AND Token='$Token' ";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        // Error handling for SQL query
        header("Location: signupform.php?message=Database error");
        exit();
    }

    if (mysqli_num_rows($result) > 0) {
        // Update the user's record to mark them as verified
        $update_sql = "UPDATE student_regis SET Status='Verified' WHERE Email='$email' AND Token='$Token'";
        if (mysqli_query($conn, $update_sql)) {
            // Display an error message indicating successful verification
            $_SESSION['status'] = "Email successfully verified";
            header('Location: loginform.php');
            exit();
        } else {
            // Display an error message indicating failed verification
            $_SESSION['status'] = "Verification failed. Please try again.";
            header('Location: VerifyForm.php?message=Error updating record');
            exit();
        }
    } else {
        // Display an error message indicating failed verification
        $_SESSION['status'] = "Verification failed. Please try again.";
        header('Location: VerifyForm.php?message=Error updating record');
        exit();
    }
} else {
    // Display an error message indicating failed verification
    $_SESSION['status'] = "Verification failed. Please try again.";
    header('Location: VerifyForm.php?message=Error updating record');
    exit();
}

mysqli_close($conn);
?>
