<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: ../loginform.php');
    exit();
  }

$user_details = $_SESSION['user_details'];

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


// Validation function to sanitize input data
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = isset($_POST['admin_id']) ? validate($_POST['admin_id']) : null;

    switch (true) {
        case isset($_POST['UpdatePicture']):
            $profile_picture = $_FILES['profile_picture']['name'];
            $allowed_extension = array('png', 'jpg', 'jpeg');
            $file_extension = pathinfo($profile_picture, PATHINFO_EXTENSION);
    
            if (!in_array($file_extension, $allowed_extension)) {
                $_SESSION['auth_status'] = "You are allowed with only jpg, png, jpeg image";
                header('Location: User_Profile.php');
                exit(0);
            }

            $update_sql = "UPDATE admin SET profile_picture = ? WHERE admin_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("si", $profile_picture, $admin_id);
        
            if ($stmt->execute()) {
                move_uploaded_file($_FILES['profile_picture']['tmp_name'], 'assets/dist/img/'.$profile_picture);
        
                // Fetch the updated profile picture
                $fetch_sql = "SELECT * FROM admin WHERE admin_id = ?";
                $fetch_stmt = $conn->prepare($fetch_sql);
                $fetch_stmt->bind_param("i", $admin_id);
                $fetch_stmt->execute();
                $result = $fetch_stmt->get_result();
                $updatedUserDetails = $result->fetch_assoc();
        
                // Update the session with the new user details
                $_SESSION['user_details'] = $updatedUserDetails;
                $_SESSION['auth_status'] = "Profile Update Successfully";
        
                header('Location: User_Profile.php');
                exit(0);
            } else {
                $_SESSION['auth_status'] = "Profile Picture Updating Failed";
                header('Location: User_Profile.php');
                exit(0);
            }
            break;

        case isset($_POST['UpdateInfo']):
            $full_name = validate($_POST['full_name']);
            $gender = validate($_POST['gender']);
            $phone_number = validate($_POST['phone_number']);
            $address = validate($_POST['address']);

            $update_sql = "UPDATE admin SET full_name = ?, gender = ?, phone_number = ?, address = ? WHERE admin_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssssi", $full_name, $gender, $phone_number, $address, $admin_id);

            if ($stmt->execute()) {

                // Fetch the updated user details
                $fetch_sql = "SELECT * FROM admin WHERE admin_id = ?";
                $fetch_stmt = $conn->prepare($fetch_sql);
                $fetch_stmt->bind_param("i", $admin_id);
                $fetch_stmt->execute();
                $result = $fetch_stmt->get_result();
                $updatedUserDetails = $result->fetch_assoc();
        
                // Update the session with the new user details
                $_SESSION['user_details'] = $updatedUserDetails;
                $_SESSION['auth_status'] = "User Information Updated Successfully";
                header('Location: User_Profile.php');
                exit(0);
            } else {
                $_SESSION['auth_status'] = "User Information Update Failed";
                header("Location: User_Profile.php");
                exit(0);
            }
            break;

        case isset($_POST['UpdateBirthdate']):
            $birthdate = validate($_POST['birthdate']);
            $birthday = new DateTime($birthdate);
            $currentDate = new DateTime();
            $age = $currentDate->diff($birthday)->y;

            if ($age < 14) {
                $_SESSION['auth_status'] = "Only 14 years old or above are allowed.";
                header("Location: User_Profile.php?error=Only 14 years old or above are allowed.");
                exit();
            } else {
                $birthdateStr = $birthday->format('Y-m-d');
                $update_sql = "UPDATE admin SET birthdate = ?, age = ? WHERE admin_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("sii", $birthdateStr, $age, $admin_id);

                if ($stmt->execute()) {

                    // Fetch the updated user details
                    $fetch_sql = "SELECT * FROM admin WHERE admin_id = ?";
                    $fetch_stmt = $conn->prepare($fetch_sql);
                    $fetch_stmt->bind_param("i", $admin_id);
                    $fetch_stmt->execute();
                    $result = $fetch_stmt->get_result();
                    $updatedUserDetails = $result->fetch_assoc();
            
                    // Update the session with the new user details
                    $_SESSION['user_details'] = $updatedUserDetails;

                    $_SESSION['auth_status'] = "Birthdate Update Successfully";
                    header('Location: User_Profile.php');
                    exit(0);
                } else {
                    $_SESSION['auth_status'] = "Birthdate Update Failed";
                    header("Location: User_Profile.php");
                    exit(0);
                }
            }
            break;

            // Check if the form submitted is for updating the password
            case isset($_POST['UpdatePass']):
                $new_password = validate($_POST['new_password']);
                $confirm_password = validate($_POST['confirm_password']);
    
                // Check if new password and confirm password match
                if ($new_password !== $confirm_password) {
                    $_SESSION['auth_status'] = "New password and confirm password do not match";
                    header('Location: User_Profile.php');
                    exit(0);
                }
        
                // Update the password in the users table where user_id = admin_id
                $update_sql = "UPDATE users SET password = ? WHERE user_id = ?";
                $stmt = $conn->prepare($update_sql);
    
                if (!$stmt) {
                    $_SESSION['auth_status'] = "Password Update Failed: Preparation failed - " . $conn->error;
                    header("Location: User_Profile.php");
                    exit(0);
                }
    
                $stmt->bind_param("si", $new_password, $admin_id);
    
                if ($stmt->execute()) {
                    $_SESSION['auth_status'] = "Password Updated Successfully";
                } else {
                    $_SESSION['auth_status'] = "Password Update Failed: " . $stmt->error;
                }
    
                $stmt->close();
                $conn->close();
    
                header('Location: User_Profile.php');
                exit(0);
                
        default:
            $_SESSION['auth_status'] = "No valid action specified";
            header("Location: User_Profile.php");
            exit(0);
    }
} else {
    $_SESSION['auth_status'] = "Invalid request method";
    header("Location: User_Profile.php");
    exit(0);
}
?>
