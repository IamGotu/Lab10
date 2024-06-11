<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: ../loginform.php');
    exit();
}

// Specifically instructor access only
$required_role = 'instructor';

// Check if the user has the required role
if ($_SESSION['role'] !== $required_role) {
    $_SESSION['auth_status'] = "You do not have permission to access this page";
    header('Location: ../logout.php');
    exit();
}

// Check if the request is made via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if submission_id and grade are set
    if (isset($_POST["submission_id"]) && isset($_POST["grade"])) {
        // Get the submission ID and grade from the POST data
        $submission_id = $_POST["submission_id"];
        $grade = $_POST["grade"];

        // Include the database connection
        include('../database/db_conn.php');

        // Prepare and execute the SQL query to update the grade for the submission
        $update_query = "UPDATE submissions SET grade = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("di", $grade, $submission_id);
        $stmt->execute();

        // Close the database connection
        $stmt->close();
        $conn->close();

        // Return a success message
        $_SESSION['auth_status'] = "Grade submitted successfully.";
        header("Location: Assessments_Submitted.php?assessment_id=$assessment_id");
        exit(0);
    } else {
        // Return an error message if submission_id or grade is not set
        $_SESSION['auth_status'] = "Error: Submission ID or grade is missing.";
        header("Location: Assessments_Submitted.php?assessment_id=$assessment_id");
        exit(0);
    }
} else {
    // Return an error message if the request method is not POST
    $_SESSION['auth_status'] = "Error: Invalid request method.";
    header("Location: Assessments_Submitted.php?assessment_id=$assessment_id");
    exit(0);
}
?>
