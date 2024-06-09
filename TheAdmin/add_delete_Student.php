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
        $_SESSION['auth_status'] = "Email already exists in the database";
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
        $_SESSION['auth_status'] = "Student added successfully";
        header('Location: ../TheAdmin/Students.php');
        exit(0);
    } else {
        $_SESSION['auth_status'] = "Error adding student";
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
        $_SESSION['auth_status'] = "Student deleted successfully";
        header('Location: ../TheAdmin/Students.php');
        exit(0);
    } else {
        $_SESSION['auth_status'] = "Error deleting student";
        header('Location: ../TheAdmin/Students.php');
        exit(0);
    }
} elseif ( isset($_POST['updateStudent'])) {
        // Sanitize user input
        $student_id = mysqli_real_escape_string($conn, $_POST['edit_student_id']);
        $full_name = mysqli_real_escape_string($conn, $_POST['edit_full_name']);
        $email = mysqli_real_escape_string($conn, $_POST['edit_email']);
        $address = mysqli_real_escape_string($conn, $_POST['edit_address']);
        $age = mysqli_real_escape_string($conn, $_POST['edit_age']);
        $gender = mysqli_real_escape_string($conn, $_POST['edit_gender']);
        $course = mysqli_real_escape_string($conn, $_POST['edit_course']);
        $password = mysqli_real_escape_string($conn, $_POST['edit_password']);
    
        // Update student's information in the database
        $update_query = "UPDATE student_list SET full_name='$full_name', email='$email', address='$address', age='$age', gender='$gender', course='$course', password='$password' WHERE student_id='$student_id'";
        $update_result = mysqli_query($conn, $update_query);
    
        if ($update_result) {
            $_SESSION['auth_status'] = "Student details updated successfully";
            header('Location: ../TheAdmin/Students.php');
            exit();
        } else {
            $_SESSION['auth_status'] = "Failed to update student details. Please try again.";
            header('Location: ../TheAdmin/Students.php');
            exit();
        }
    } else {
    // Display an error message if the form was not submitted
    $_SESSION['auth_status'] = "Student Action Failed";
    header("Location: ../TheAdmin/Students.php");
    exit(0);
}
?>
