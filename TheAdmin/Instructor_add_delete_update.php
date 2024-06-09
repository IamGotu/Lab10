<?php
session_start();

// Include file for database connection
include('../database/db_conn.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addInstructor'])) {
    // Function to validate input data
    function validate($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }
    // Ensure fields are initialized
    $user_id = validate($_POST['user_id']);
    $full_name = validate($_POST['full_name']);
    $birthdate = validate($_POST['birthdate']);
    $gender = validate($_POST['gender']);
    $email = validate($_POST['email']);
    $phone_number = validate($_POST['phone_number']);
    $address = validate($_POST['address']);
    $role = 'instructor';
    $password = 'Rumaken*';
    $status = 'Pending';
    $active = 'Offline';
    $profile_picture = 'user.png';

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid email format.";
        header("Location: signupform.php?error=Invalid email format");
        exit();
    }

    // Check if the email already exists in the database
    $sql_check_email = "SELECT * FROM users WHERE email=?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();

    if ($result_check_email->num_rows > 0) {
        $_SESSION['status'] = "Email already exists.";
        header("Location: signupform.php?error=Email already exists");
        exit();
    }

    // Check if new password and confirm password match
    if ($password !== $confirm_password) {
        $_SESSION['status'] = "Passwords do not match. Please try again.";
        header("Location: signupform.php?error=Passwords do not match. Please try again.");
        exit();
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
    }

    // Convert DateTime object to string for SQL
    $birthdateStr = $birthday->format('Y-m-d');

    // Insert into the users table
    $user_sql = "INSERT INTO users (user_id, role, email, password, status, active) VALUES (?, ? , ?, ?, ?, ?)";
    $stmt_user = $conn->prepare($user_sql);
    $stmt_user->bind_param("isssss", $user_id, $role, $email, $password, $status, $active);

    if ($stmt_user->execute()) {
        // Get the inserted user's ID
        $user_id = $stmt_user->insert_id;

        // Insert data into the instructors table
        $instructor_sql = "INSERT INTO instructors (instructor_id, profile_picture, full_name, birthdate, age, gender, email, phone_number, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_instructor = $conn->prepare($instructor_sql);
        $stmt_instructor->bind_param("isssissss", $user_id, $profile_picture, $full_name, $birthdateStr, $age, $gender, $email, $phone_number, $address);

        if ($stmt_instructor->execute()) {
            $_SESSION['status'] = "Instructor added successfully";
            header('Location: ../TheAdmin/Instructors.php');
            exit();
        } else {
            $_SESSION['status'] = "Error adding instructor.";
            header('Location: signupform.php?error=Error adding instructor');
            exit();
        }
    } else {
        $_SESSION['status'] = "Error adding user.";
        header('Location: signupform.php?error=Error adding user');
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteInstructor'])) {
    // Ensure field is initialized
    $user_id = $_POST['delete_user_id'];

    // Construct SQL query for deleting instructor data
    $sql = "DELETE FROM users WHERE user_id=?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $instructor_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['status'] = "Instructors deleted successfully";
        header('Location: ../TheAdmin/Instructors.php');
        exit(0);
    } else {
        $_SESSION['status'] = "Error deleting instructor";
        header('Location: ../TheAdmin/Instructors.php');
        exit(0);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateInstructor'])) {
    // Sanitize user input
    $instructor_id = mysqli_real_escape_string($conn, $_POST['edit_instructor_id']);
    $full_name = mysqli_real_escape_string($conn, $_POST['edit_full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['edit_email']);
    $address = mysqli_real_escape_string($conn, $_POST['edit_address']);
    $age = mysqli_real_escape_string($conn, $_POST['edit_age']);
    $gender = mysqli_real_escape_string($conn, $_POST['edit_gender']);
    $password = mysqli_real_escape_string($conn, $_POST['edit_password']);

    // Update instructor data in the database
    $update_query = "UPDATE instructors SET full_name='$full_name', email='$email', address='$address', age='$age', gender='$gender', password='$password' WHERE instructor_id='$instructor_id'";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        $_SESSION['status'] = "Instructor details updated successfully";
        header('Location: ../TheAdmin/Instructors.php');
        exit();
    } else {
        // Add error handling to display MySQL error
        $_SESSION['status'] = "Failed to update instructor details. Error: " . mysqli_error($conn);
        header('Location: ../TheAdmin/Instructors.php');
        exit();
    }
}
?>
