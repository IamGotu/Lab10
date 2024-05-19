<?php
session_start();

// Include file for database connection
include('config/db_conn.php');

// Check if the request method is POST
if(isset($_POST['addTeacher'])) {
    // Function to validate input data
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Ensure fields are initialized
    $teacher_id = validate($_POST['teacher_id']); // Validate teacher_id
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $full_name = validate($_POST['full_name']);
    $address = validate($_POST['address']);
    $age = validate($_POST['age']);
    $gender = validate($_POST['gender']);

    // Check if email already exists
    $check_email_query = "SELECT * FROM teachers WHERE email=?";
    $stmt = mysqli_prepare($conn, $check_email_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    if($count > 0) {
        $_SESSION['status'] = "Email already exists in the database";
        header('Location: User_Profile.php');
        exit(0);
    }

    // Construct SQL query for inserting teacher data
    $sql = "INSERT INTO teachers (teacher_id, email, password, full_name, address, age, gender) VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "issssis", $teacher_id, $email, $password, $full_name, $address, $age, $gender);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['status'] = "Teacher added successfully";
        header('Location: User_Profile.php');
        exit(0);
    } else {
        $_SESSION['status'] = "Error adding teacher";
        header('Location: User_Profile.php');
        exit(0);
    }
} elseif(isset($_POST['deleteTeacher'])) {
    // Ensure field is initialized
    $teacher_id = $_POST['delete_teacher_id'];

    // Construct SQL query for deleting teacher data
    $sql = "DELETE FROM teachers WHERE teacher_id=?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $teacher_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['status'] = "Teacher deleted successfully";
        header('Location: User_Profile.php');
        exit(0);
    } else {
        $_SESSION['status'] = "Error deleting teacher";
        header('Location: User_Profile.php');
        exit(0);
    }
} else {
    // Display an error message if the form was not submitted
    $_SESSION['status'] = "Teacher Action Failed";
    header("Location: User_Profile.php");
    exit(0);
}
?>
