<?php
session_start();

// Include file for database connection
include('config/db_conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Get the verification code and email from the form
     $verification_code = validate ($_POST['verification_code']);
    $email = validate ($_POST['email']);

    // SQL query to check if the verification code matches the one stored in the database
    $sql = "SELECT * FROM user_profile WHERE Email='$email' AND verify_token='$verification_code' ";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Update the user's record to mark them as verified
        $update_sql = "UPDATE user_profile SET Status='Verified' WHERE Email='$email' AND verify_token='$verification_code'";
        if (mysqli_query($conn, $update_sql)) {
            // Redirect the user to a page indicating successful verification
            $_SESSION['status'] = "You have successfully verified. You can now login your account";
            header("Location: Loginform.php?success=you have successfully verified");
            exit();
        } else {
            // Display an error message if the update fails
            $_SESSION['status'] = "Verification failed, please try again";
            header("Location: VerifyEmail.php?email=$email&error=Verification failed, please try again");
            exit();
        }
    } else {
        // Redirect the user to a page indicating invalid verification code
        $_SESSION['status'] = "Invalid verification code, please try again";
        header("Location: VerifyEmail.php?email=$email&error=Invalid verification code, please try again");
        exit();
    }
} else {
    // Redirect the user if they try to access this page directly
    $_SESSION['status'] = "You cannot access this site directly";
    header("Location: Loginform.php?error=You cannot access this site directly");
    exit();
}

mysqli_close($conn);
?>
