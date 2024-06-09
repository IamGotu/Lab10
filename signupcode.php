<?php
session_start();
include('database/db_conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if (isset($_POST['signup_btn'])) {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    $user_id = validate($_POST['user_id']);
    $role = validate($_POST['role']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $confirm_password = validate($_POST['confirm_password']);
    $status = 'Pending'; // Set default value
    $active = 'Offline'; // Set default value
    $verify_token = bin2hex(random_bytes(2)); // Generate a unique verification token
    
    $profile_picture = 'user.png'; // Set default value
    $full_name = validate($_POST['full_name']);
    $birthdate = validate($_POST['birthdate']);
    $phone_number = validate($_POST['phone_number']);
    $address = validate($_POST['address']);
    $gender = validate($_POST['gender']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid email format.";
        header("Location: signupform.php?error=Invalid email format");
        exit();
    }

    // Check if the email already exists in the database
    $sql_check_email = "SELECT * FROM users WHERE email='$email'";
    $result_check_email = mysqli_query($conn, $sql_check_email);
    if (mysqli_num_rows($result_check_email) > 0) {
        $_SESSION['status'] = "Email already exists.";
        header("Location: signupform.php?error=Email already exists");
        exit();
    }

    // Check if new password and confirm password match
    if ($password !== $confirm_password) {
        $_SESSION['status'] = "Password do not match. Please try again.";
        header('Location: signupform.php?error=Password do not match. Please try again.');
        exit(0);
    }
    
    // Validate phone number format
    if (!preg_match("/^\+\d{1,3}\d{4,14}$/", $phone_number)) {
        $_SESSION['status'] = "Invalid phone number format.";
        header("Location: signupform.php?error=Invalid phone number format");
        exit();
    }

    // Validate password format
    if (!preg_match("/(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{5,}/", $password)) {
        $_SESSION['status'] = "Password must contain at least one uppercase letter, one lowercase letter, one special character, and be at least 5 characters long.";
        header("Location: signupform.php?error=Invalid password format");
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

        // Insert into the users table
        $user_sql = "INSERT INTO users (user_id, role, email, password, status, active, verify_token) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($user_sql);
        $stmt->bind_param("issssss", $user_id, $role, $email, $password, $status, $active, $verify_token);

        if ($stmt->execute()) {
            // Get the inserted user's ID
            $user_id = $stmt->insert_id;

            // Insert data into the appropriate table based on the role
            if ($role === 'student') {
                $student_sql = "INSERT INTO students (student_id, profile_picture, full_name, birthdate, age, gender, email, phone_number, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($student_sql);
                $stmt->bind_param("isssissss", $user_id, $profile_picture, $full_name, $birthdateStr, $age, $gender, $email, $phone_number, $address);
            } elseif ($role === 'instructor') {
                $instructor_sql = "INSERT INTO instructors (instructor_id, profile_picture, full_name, birthdate, age, gender, email, phone_number, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($instructor_sql);
                $stmt->bind_param("isssissss", $user_id, $profile_picture, $full_name, $birthdateStr, $age, $gender, $email, $phone_number, $address);
            } else {
                $_SESSION['status'] = "Please select your role properly.";
                header("Location: signupform.php?error=Please select your role properly.");
                exit();
            }

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
        } else {
            echo "Invalid request";
        }
    }
}
?>
