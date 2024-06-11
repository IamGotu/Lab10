<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: ../loginform.php');
    exit();
}

// Specifically admin access only
$required_role = 'admin';

// Check if the user has the required role
if ($_SESSION['role'] !== $required_role) {
    $_SESSION['auth_status'] = "You do not have permission to access this page";
    header('Location: ../logout.php');
    exit();
}

// Include necessary files
include('../database/db_conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['addSubject'])) {
        // Add Subject
        $subject_id = $_POST['subject_id'];
        $title = $_POST['title'];
        $credits = $_POST['credits'];

        // Check if subject_id already exists
        $check_query = "SELECT * FROM subjects WHERE subject_id = '$subject_id'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $_SESSION['auth_status'] = "Subject ID already exists";
            header('Location: Subjects.php');
            exit();
        }

        // Insert subject into the database
        $query = "INSERT INTO subjects (subject_id, title, credits) VALUES ('$subject_id', '$title', '$credits')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $_SESSION['auth_status'] = "Subject added successfully";
        } else {
            $_SESSION['auth_status'] = "Error adding subject";
        }
        header('Location: Subjects.php');
        exit();
    } elseif (isset($_POST['deleteSubject'])) {
        // Delete Subject
        $subject_id = $_POST['subject_id'];

        // Delete subject from the database
        $query = "DELETE FROM subjects WHERE subject_id = '$subject_id'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $_SESSION['auth_status'] = "Subject deleted successfully";
        } else {
            $_SESSION['auth_status'] = "Error deleting subject";
        }
        header('Location: Subjects.php');
        exit();
    } elseif (isset($_POST['updateSubject'])) {
        // Update Subject
        $subject_id = $_POST['subject_id'];
        $title = $_POST['title'];
        $credits = $_POST['credits'];

        // Update subject in the database
        $query = "UPDATE subjects SET title = '$title', credits = '$credits' WHERE subject_id = '$subject_id'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $_SESSION['auth_status'] = "Subject updated successfully";
        } else {
            $_SESSION['auth_status'] = "Error updating subject";
        }
        header('Location: Subjects.php');
        exit();
    }
}
?>
