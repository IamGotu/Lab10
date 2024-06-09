<?php
session_start();
include('database/db_conn.php');

if (isset($_POST['login_btn'])) {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $role = validate($_POST['role']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    // Check if user exists in the database and verify the credentials
    $query = "SELECT * FROM users WHERE email = ? AND role = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Assuming passwords are stored hashed, verify the password
        if (password_verify($password, $user['password'])) {
            // Authentication successful
            $_SESSION['role'] = $role;
            $_SESSION['email'] = $email;

            if ($role == 'admin') {
                header('Location: ../TheAdmin/admin_Home.php');
                exit();
            } elseif ($role == 'teachers') {
                header('Location: ../TheTeachers/teachers_Home.php');
                exit();
            } elseif ($role == 'student_list') {
                header('Location: ../TheStudents/student_Home.php');
                exit();
            } else {
                // Handle unknown role
                $_SESSION['auth_status'] = "Unknown role.";
                header('Location: ../loginform.php?error=Unknown role.');
                exit();
            }
        } else {
            // Invalid password
            $_SESSION['auth_status'] = "Invalid email or password";
            header('Location: ../loginform.php?error=Invalid email or password');
            exit();
        }
    } else {
        // Invalid email or role
        $_SESSION['auth_status'] = "Invalid email or role";
        header('Location: loginform.php?error=Invalid email or role');
        exit();
    }
} else {
    // If login button not pressed, redirect to login page
    header('Location: ../loginform.php?error=error.');
    exit();
}
?>
