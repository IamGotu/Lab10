<?php
session_start();
include('../database/db_conn.php');

// Check if the request method is POST
if(isset($_POST['addStudent'])) {
    // Function to validate input data
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Ensure fields are initialized
    $student_id = $_POST['student_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $password = '12345';

    // Check if email already exists
    $check_email_query = "SELECT * FROM student_list WHERE email=?";
    $stmt = mysqli_prepare($conn, $check_email_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    if($count > 0) {
        $_SESSION['status'] = "Email already exists in the database";
        header('Location: ../TheAdmin/Students.php');
        exit(0);
    }

    // Construct SQL query for inserting student data
    $sql = "INSERT INTO student_list (student_id, full_name, email, address, age, gender, course, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "isssisss", $student_id, $full_name, $email, $address, $age, $gender, $course, $password,);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['status'] = "Student added successfully";
        header('Location: ../TheAdmin/Students.php');
        exit(0);
    } else {
        $_SESSION['status'] = "Error adding student";
        header('Location: ../TheAdmin/Students.php');
        exit(0);
    }
} elseif(isset($_POST['deleteStudent'])) {
    // Ensure field is initialized
    $student_id = $_POST['delete_student_id'];

    // Construct SQL query for deleting student data
    $sql = "DELETE FROM student_list WHERE student_id=?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $student_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['status'] = "Student deleted successfully";
        header('Location: ../TheAdmin/Students.php');
        exit(0);
    } else {
        $_SESSION['status'] = "Error deleting student";
        header('Location: ../TheAdmin/Students.php');
        exit(0);
    }
} else {
    // Display an error message if the form was not submitted
    $_SESSION['status'] = "Student Action Failed";
    header("Location: ../TheAdmin/Students.php");
    exit(0);
}
?>
