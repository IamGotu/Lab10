<?php 
// Include file for database connection
include('config/db_conn.php');
// Include file for authentication
include('Authentication.php');

// Validation function to sanitize input data
function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? validate($_POST['user_id']) : null;

    switch (true) {
        case isset($_POST['UpdatePicture']):
            $profile_picture = $_FILES['profile_picture']['name'];
            $allowed_extension = array('png', 'jpg', 'jpeg');
            $file_extension = pathinfo($profile_picture, PATHINFO_EXTENSION);
    
            if (!in_array($file_extension, $allowed_extension)) {
                $_SESSION['status'] = "You are allowed with only jpg, png, jpeg image";
                header('Location: User_Profile.php');
                exit(0);
            }

            $update_sql = "UPDATE user_profile SET profile_picture = ? WHERE user_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("si", $profile_picture, $user_id);

            if ($stmt->execute()) {
                move_uploaded_file($_FILES['profile_picture']['tmp_name'], 'uploads/'.$profile_picture);
                $_SESSION['status'] = "Profile Update Successfully";
                header('Location: User_Profile.php');
                exit(0);
            } else {
                $_SESSION['status'] = "Profile Picture Updating Failed";
                header('Location: User_Profile.php');
                exit(0);
            }
            break;

        case isset($_POST['UpdateInfo']):
            $full_name = validate($_POST['full_name']);
            $phone_number = validate($_POST['phone_number']);
            $address = validate($_POST['address']);

            $update_sql = "UPDATE user_profile SET full_name = ?, phone_number = ?, address = ? WHERE user_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("sssi", $full_name, $phone_number, $address, $user_id);

            if ($stmt->execute()) {
                $_SESSION['status'] = "User Information Updated Successfully";
                header('Location: User_Profile.php');
                exit(0);
            } else {
                $_SESSION['status'] = "User Information Update Failed";
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
                $_SESSION['status'] = "Only 14 years old or above are allowed.";
                header("Location: User_Profile.php?error=Only 14 years old or above are allowed.");
                exit();
            } else {
                $birthdateStr = $birthday->format('Y-m-d');
                $update_sql = "UPDATE user_profile SET birthdate = ? WHERE user_id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("si", $birthdateStr, $user_id);

                if ($stmt->execute()) {
                    $_SESSION['status'] = "Birthdate Update Successfully";
                    header('Location: User_Profile.php');
                    exit(0);
                } else {
                    $_SESSION['status'] = "Birthdate Update Failed";
                    header("Location: User_Profile.php");
                    exit(0);
                }
            }
            break;

        case isset($_POST['UpdatePass']):
            $current_password = validate($_POST['current_password']);
            $new_password = validate($_POST['new_password']);
            $confirm_password = validate($_POST['confirm_password']);

            // Check if new password and confirm password match
            if ($new_password !== $confirm_password) {
                $_SESSION['status'] = "New password and confirm password do not match";
                header('Location: User_Profile.php');
                exit(0);
            }

            // Update the password
            $update_sql = "UPDATE user_profile SET password = ? WHERE user_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("si", $new_password, $user_id);

            if ($stmt->execute()) {
                $_SESSION['status'] = "Password Updated Successfully";
                header('Location: User_Profile.php');
                exit(0);
            } else {
                $_SESSION['status'] = "Password Update Failed: " . $stmt->error;
                header("Location: User_Profile.php");
                exit(0);
            }
            break;

        default:
            $_SESSION['status'] = "No valid action specified";
            header("Location: User_Profile.php");
            exit(0);
    }
} else {
    $_SESSION['status'] = "Invalid request method";
    header("Location: User_Profile.php");
    exit(0);
}
?>
