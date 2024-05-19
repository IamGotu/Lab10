<?php 
// Include file for database connection
include('config/db_conn.php');

// Check if the request method is POST
if(isset($_POST['UpdateUser'])) {
    // Function to validate input data
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Ensure fields are initialized
    $num_student = validate($_POST['num_student']); // Ensure it's validated
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $full_name = validate($_POST['full_name']);
    $address = validate($_POST['address']);
    $prof_pic = $_FILES['profile_picture']['name']; // Original file name
    
    $allowed_extension = array('png', 'jpg', 'jpeg');
    $file_extension = pathinfo($prof_pic, PATHINFO_EXTENSION);
    
    if(!in_array($file_extension, $allowed_extension)) {
        $_SESSION['status'] = "You are allowed with only jpg, png, jpeg image";
        header('Location: User_Profile.php');
        exit(0);
    }

    // Construct SQL query for updating user profile
    $sql = "UPDATE student_regis SET email = ?, password = ?, full_name = ?, address = ?, prof_pic = ? WHERE student_id = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "sssssi", $email, $password, $full_name, $address, $prof_pic, $num_student);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], 'uploads/'.$prof_pic); // Move uploaded file with original name
        $_SESSION['status'] = "User Update Successfully"; // Set success message
        header('Location: User_Profile.php');
        exit(0);
    } else {
        $_SESSION['status'] = "Profile Picture Updating Failed";
        header('Location: User_Profile.php');
        exit(0);
    }
} else {
    // Display an error message if the form was not submitted
    $_SESSION['status'] = "User Updating Failed";
    header("Location: User_Profile.php");
    exit(0);
}
?>
