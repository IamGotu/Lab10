<?php
session_start();

include('Authentication.php');
include('config/db_conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if (isset($_POST['signup_btn'])) {
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $full_name = validate($_POST['full_name']);
    $email = validate($_POST['email']);
    $birthdate = validate($_POST['birthdate']);
    $phone_number = validate($_POST['phone_number']);
    $address = validate($_POST['address']);
    $password = validate($_POST['password']);
    $verify_token = bin2hex(random_bytes(2)); // Generate a unique verification token
    $profile_picture = 'user.png'; // Set default value
    $Status = 'Pending'; // Set default value
    $Active = 'Offline'; // Set default value

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid email format.";
        header("Location: signupform.php?error=Invalid email format");
        exit();
    }

    // Check if the email already exists in the database
    $sql_check_email = "SELECT * FROM user_profile WHERE email='$email'";
    $result_check_email = mysqli_query($conn, $sql_check_email);
    if (mysqli_num_rows($result_check_email) > 0) {
        $_SESSION['status'] = "Email already exists.";
        header("Location: signupform.php?error=Email already exists");
        exit();
    }

    // Calculate age
    $birthday = new DateTime($birthdate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthday)->y;

    // Check if age is less than 14
    if ($age < 14) {
        $_SESSION['status'] = "You must be at least 14 years old to register.";
        header("Location: signupform.php?error=You must be at least 14 years old to register.");
        exit();
    } else {
    
    // Convert DateTime object to string for SQL
    $birthdateStr = $birthday->format('Y-m-d');

        // Insert user data into the database
        $insert_query = "INSERT INTO user_profile (full_name, email, birthdate, phone_number, address, password, profile_picture, Status, Active, verify_token)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Bind parameters
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssssssss", $full_name, $email, $birthdateStr, $phone_number, $address, $password, $profile_picture, $Status, $Active, $verify_token);

        // Execute stmt
        if ($stmt->execute()) {

        // Send verification email
        $subject = "Email Verification";
        $message = "Hello, $full_name. Your verification code is: $verify_token";

        // Create a PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'cocnambawan@gmail.com'; // Your gmail
            $mail->Password = 'bkvm sirf keww nswm'; // Your gmail app password
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            // Sender and recipient
            $mail->setFrom('cocnambawan@gmail.com', 'Email Verification');
            $mail->addAddress($email);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Send email
            $mail->send();

            // Redirect with a success message
            header("Location: VerifyEmail.php?email=$email");
            exit();
        } catch (Exception $e) {
            // Display an error message if the verification email could not be sent
            $_SESSION['status'] = "Verification email could not be sent. Please try again later.";
            header("Location: signupform.php?error=Verification email could not be sent. Please try again later.");
            exit();
        }
    } else {
        // Display an error message if the query fails
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    }
} else {
echo "Invalid request";
}
?>