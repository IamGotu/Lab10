<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
  $_SESSION['auth_status'] = "You need to be logged in to access this page";
  header('Location: ../loginform.php');
  exit();
}

// Specificaly admin access only
$required_role = 'admin';

// Check if the user has the required role
if ($_SESSION['role'] !== $required_role) {
    $_SESSION['auth_status'] = "You do not have permission to access this page";
    header('Location: ../logout.php');
    exit();
}

// Include file for database connection
include('../database/db_conn.php');

// Adding Student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addStudent'])) { // Check if the request method is POST
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
    $role = 'student';
    $password = 'Rumaken*';
    $status = 'Pending';
    $active = 'Offline';
    $profile_picture = 'user.png';

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['auth_status'] = "Invalid email format.";
        header("Location: Students.php?error=Invalid email format");
        exit();
    }

    // Check if the email already exists in the database
    $sql_check_email = "SELECT * FROM users WHERE email=?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();

    if ($result_check_email->num_rows > 0) {
        $_SESSION['auth_status'] = "Email already exists.";
        header("Location: Students.php?error=Email already exists");
        exit();
    }
    
    // Validate phone number format
    if (!preg_match("/^\+\d{1,3}\d{4,14}$/", $phone_number)) {
        $_SESSION['auth_status'] = "Invalid phone number format.";
        header("Location: Students.php?error=Invalid phone number format");
        exit();
    }

    // Calculate age
    $birthday = new DateTime($birthdate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthday)->y;

    // Check if age is less than 14
    if ($age < 14) {
        $_SESSION['auth_status'] = "The user must be at least 14 years old to register.";
        header("Location: Students.php?error=The user must be at least 14 years old to register.");
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

        // Insert data into the students table
        $student_sql = "INSERT INTO students (student_id, profile_picture, full_name, birthdate, age, gender, email, phone_number, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_student = $conn->prepare($student_sql);
        $stmt_student->bind_param("isssissss", $user_id, $profile_picture, $full_name, $birthdateStr, $age, $gender, $email, $phone_number, $address);

        if ($stmt_student->execute()) {
            $_SESSION['auth_status'] = "Student added successfully";
            header('Location: ../TheAdmin/Students.php');
            exit();
        } else {
            $_SESSION['auth_status'] = "Error adding student.";
            header('Location: Students.php?error=Error adding student');
            exit();
        }
    } else {
        $_SESSION['auth_status'] = "Error adding user.";
        header('Location: Students.php?error=Error adding user');
        exit();
    }

// Deleting Student
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteStudent'])) {

    // Sanitize
    $user_id = mysqli_real_escape_string($conn, $_POST['delete_user_id']);


    // Construct SQL query for deleting student data
    $sql = "DELETE FROM users WHERE user_id = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $user_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['auth_status'] = "Student deleted successfully";
        header('Location: ../TheAdmin/Students.php');
        exit(0);
    } else {
        $_SESSION['auth_status'] = "Error deleting student: " . mysqli_error($conn);
        header('Location: ../TheAdmin/Students.php');
        exit(0);
    }
    
// updating Student
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateStudent'])) {

    // Sanitize
    $student_id = mysqli_real_escape_string($conn, $_POST['update_student_id']);
    $full_name = mysqli_real_escape_string($conn, $_POST['update_full_name']);
    $birthdate = mysqli_real_escape_string($conn, trim($_POST['update_birthdate'])); // Ensure the birthdate is retrieved correctly
    $gender = mysqli_real_escape_string($conn, $_POST['update_gender']);
    $phone_number = mysqli_real_escape_string($conn, trim($_POST['update_phone_number'])); // Ensure the phone number is retrieved correctly
    $address = mysqli_real_escape_string($conn, $_POST['update_address']);

    // Validate phone number format
    if (!preg_match("/^\+\d{1,3}\d{4,14}$/", $phone_number)) {
        $_SESSION['auth_status'] = "Invalid phone number format.";
        header("Location: Students.php?error=Invalid phone number format");
        exit();
    }    

    // Calculate age
    $birthday = new DateTime($birthdate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthday)->y;

    // Check if age is less than 14
    if ($age < 14) {
        $_SESSION['auth_status'] = "The user must be at least 14 years old.";
        header("Location: Students.php?error=You must be at least 14 years old.");
        exit();
    }

    // Convert DateTime object to string for SQL
    $birthdateStr = $birthday->format('Y-m-d');
    
    // Update student data in the database
    $update_sql = "UPDATE students SET full_name = ?, birthdate = ?, age = ?, gender = ?, phone_number = ?, address = ? WHERE student_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssisssi", $full_name, $birthdateStr, $age, $gender, $phone_number, $address, $student_id);

    if ($stmt->execute()) {
    $_SESSION['auth_status'] = "Student details updated successfully";
        header('Location: ../TheAdmin/Students.php');
        exit();
    } else {
        // Add error handling to display MySQL error
        $_SESSION['auth_status'] = "Failed to update student details. Error: " . mysqli_error($conn);
        header('Location: ../TheAdmin/Students.php');
        exit();
    }
} else {
    $_SESSION['auth_status'] = "Action Failed. Error: " . mysqli_error($conn);
    header('Location: ../TheAdmin/Students.php');
    exit();
}
?>
