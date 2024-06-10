<?php
session_start();
include('database/db_conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if (isset($_POST['login_btn'])) {
    function validate($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $role = validate($_POST['role']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    // Prepare the SQL statement to prevent SQL injection
    $query = "SELECT * FROM users WHERE email = ? AND role = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        exit();
    }
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        exit();
    }

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check if the user's status is 'Pending'
        if ($user['status'] === 'Pending') {
            // Resend verification code
            $verification_code = mt_rand(100000, 999999); // Generate a random 6-digit code

            // Update the user's record in the database with the new verification code
            $update_code_query = "UPDATE users SET verify_token = ? WHERE email = ?";
            $update_stmt = $conn->prepare($update_code_query);
            $update_stmt->bind_param("ss", $verification_code, $email);
            $update_stmt->execute();

            // Send verification code via email
            $subject = "Verification Code";
            $message = "Your verification code is: $verification_code";

            $mail = new PHPMailer(true);

            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'cocnambawan@gmail.com'; // Your Gmail email address
                $mail->Password = 'bkvm sirf keww nswm'; // Your Gmail password
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                // Sender and recipient
                $mail->setFrom('your_email@gmail.com', 'Email Verification');
                $mail->addAddress($email);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;

                // Send email
                $mail->send();

                // Redirect to a page with a success message
                $_SESSION['auth_status'] = "Verification code resent successfully.";
                header("Location: VerifyEmail.php?email=$email");
                exit();
            } catch (Exception $e) {
                // Log the error message
                error_log('Email sending failed: ' . $e->getMessage());
            
                // Redirect to the login form with an error message
                $_SESSION['auth_status'] = "Verification code could not be sent. Please try again later.";
                header("Location: loginform.php?error=Verification%20code%20could%20not%20be%20sent.%20Please%20try%20again%20later.");
                exit();
            }
        } else {
            // If the user's status is not 'Pending', proceed with login
            if ($password === $user['password']) {

                // Update user status to 'Online'
                $update_status_query = "UPDATE users SET active = 'Online' WHERE email = ?";
                $update_status_stmt = $conn->prepare($update_status_query);
                $update_status_stmt->bind_param("s", $email);
                $update_status_stmt->execute();
                
                // Authentication successful
                $_SESSION['auth'] = true;
                $_SESSION['role'] = $role;
                $_SESSION['email'] = $email;

                            // Determine which table to query for user details
            $details_query = "";
            if ($role === 'admin') {
                $details_query = "SELECT * FROM admin WHERE email = ?";
            } elseif ($role === 'instructor') {
                $details_query = "SELECT * FROM instructors WHERE email = ?";
            } elseif ($role === 'student') {
                $details_query = "SELECT * FROM students WHERE email = ?";
            }

            if ($details_query) {
                $details_stmt = $conn->prepare($details_query);
                $details_stmt->bind_param("s", $email);
                $details_stmt->execute();
                $details_result = $details_stmt->get_result();
                
                if ($details_result->num_rows === 1) {
                    $details = $details_result->fetch_assoc();
                    $_SESSION['user_details'] = $details;
                } else {
                    $_SESSION['auth_status'] = "User details not found.";
                    header('Location: ../loginform.php?error=User details not found.');
                    exit();
                }
            } else {
                $_SESSION['auth_status'] = "Unknown role.";
                header('Location: ../loginform.php?error=Unknown role.');
                exit();
            }

                // Redirect based on role
                switch ($role) {
                    case 'admin':
                        header('Location: ../TheAdmin/Dashboard.php');
                        break;
                    case 'instructor':
                        header('Location: ../TheInstructors/Dashboard.php');
                        break;
                    case 'student':
                        header('Location: ../TheStudents/Dashboard.php');
                        break;
                    default:
                        $_SESSION['auth_status'] = "Unknown role.";
                        header('Location: loginform.php?error=Unknown role.');
                        break;
                }
                exit();
            } else {
                // Invalid password
                $_SESSION['auth_status'] = "Invalid email or password";
                header('Location: loginform.php?error=Invalid email or password');
                exit();
            }
        }
    } else {
        // If login credentials are invalid, redirect to the login form with an error message
        $_SESSION['auth_status'] = "Invalid email or role";
        header('Location: loginform.php?error=Invalid email or role');
        exit();
    }
} else {
    // If login button not pressed, redirect to the login form
    header('Location: loginform.php');
    exit();
}
?>
