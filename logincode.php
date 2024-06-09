<?php
session_start();
include('database/db_conn.php');

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
        
        // Directly compare plain text passwords (Not recommended for production)
        if ($password === $user['password']) {
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
                    header('Location: ../TheAdmin/admin_Home.php');
                    break;
                case 'instructor':
                    header('Location: ../TheTeachers/teachers_Home.php');
                    break;
                case 'student':
                    header('Location: ../TheStudents/student_Home.php');
                    break;
                default:
                    $_SESSION['auth_status'] = "Unknown role.";
                    header('Location: ../loginform.php?error=Unknown role.');
                    break;
            }
            exit();
        } else {
            // Invalid password
            $_SESSION['auth_status'] = "Invalid email or password";
            header('Location: ../loginform.php?error=Invalid email or password');
            exit();
        }
    } else {
        // Invalid email or role
        $_SESSION['auth_status'] = "Invalid email or role";
        header('Location: ../loginform.php?error=Invalid email or role');
        exit();
    }
} else {
    // If login button not pressed, redirect to login page
    header('Location: ../loginform.php?error=Please login.');
    exit();
}
?>
