<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    echo json_encode(["error" => "You need to be logged in to access this page"]);
    exit();
}

// Specifically instructor access only
$required_role = 'instructor';

// Check if the user has the required role
if ($_SESSION['role'] !== $required_role) {
    echo json_encode(["error" => "You do not have permission to access this page"]);
    exit();
}

// Check if submission_id and grade are set
if (!isset($_POST['submission_id']) || !isset($_POST['grade'])) {
    echo json_encode(["error" => "Submission ID or grade not provided"]);
    exit();
}

// Include necessary files
include('../database/db_conn.php');

// Sanitize and validate data
$submission_id = filter_var($_POST['submission_id'], FILTER_SANITIZE_NUMBER_INT);
$grade = filter_var($_POST['grade'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

// Validate input
if (!$submission_id || $grade === false) {
    echo json_encode(["error" => "Invalid submission ID or grade"]);
    exit();
}

// Update grade in the database
$query = "UPDATE submissions SET grade = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("di", $grade, $submission_id);

if ($stmt->execute()) {
    // Fetch additional details for CSV
    $fetch_query = "SELECT s.student_id, s.submission_file, s.submitted_at, u.full_name 
                    FROM submissions s 
                    INNER JOIN students u ON s.student_id = u.student_id 
                    WHERE s.id = ?";
    $fetch_stmt = $conn->prepare($fetch_query);
    $fetch_stmt->bind_param("i", $submission_id);
    $fetch_stmt->execute();
    $fetch_result = $fetch_stmt->get_result();
    $submission_data = $fetch_result->fetch_assoc();
    
    if ($submission_data) {
        $submission_data['grade'] = $grade;
        writeToCSV($submission_data);
        echo json_encode(["success" => "Grade submitted successfully"]);
    } else {
        echo json_encode(["error" => "Failed to fetch submission data"]);
    }

    $fetch_stmt->close();
} else {
    echo json_encode(["error" => "Failed to submit grade"]);
}

$stmt->close();
$conn->close();

function writeToCSV($data) {
    $file = 'assessments.csv';
    $file_exists = file_exists($file);
    
    // Open the file in append mode
    $handle = fopen($file, 'a');

    // If the file does not exist, write the headers
    if (!$file_exists) {
        fputcsv($handle, ['Student ID', 'Student Name', 'Submission File', 'Submitted At', 'Grade']);
    }

    // Write the data
    fputcsv($handle, [$data['student_id'], $data['full_name'], $data['submission_file'], $data['submitted_at'], $data['grade']]);

    // Close the file handle
    fclose($handle);
}
?>
