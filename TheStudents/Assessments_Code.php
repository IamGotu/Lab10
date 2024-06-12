<?php
ob_start();
session_start();

// Check if user is logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: ../loginform.php');
    exit();
}

// Check if student ID is set in session
if (!isset($_SESSION['student_id'])) {
    $_SESSION['auth_status'] = "Student ID is not set. Please log in again.";
    header('Location: ../logout.php');
    exit();
}

// Specifically student access only
$required_role = 'student';
if ($_SESSION['role'] !== $required_role) {
    $_SESSION['auth_status'] = "You do not have permission to access this page";
    header('Location: ../logout.php');
    exit();
}

$user_details = $_SESSION['user_details'];
$student_id = $_SESSION['student_id']; // Ensure student_id is set

// Include necessary files
include('../database/db_conn.php');
include('../includes/header.php');
include('topbar.php');
include('sidebar.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

// Get the assessment ID from the URL
$assessment_id = $_GET['assessment_id'];

// Fetch the assessment details
$query = "SELECT title, description FROM assessments WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$result = $stmt->get_result();
$assessment = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $submission_file = $_FILES['submission_file']['name'];
    $target_dir = "../SubmittedFile/";
    $target_file = $target_dir . basename($_FILES["submission_file"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check file size (example: limit to 5MB)
    if ($_FILES["submission_file"]["size"] > 5000000) {
        $_SESSION['auth_status'] = "Sorry, your file is too large.";
        header('Location: Assessments_Code.php?assessment_id='.$assessment_id.'&error=Sorry, your file is too large.');
        exit();
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($fileType != "doc" && $fileType != "docx" && $fileType != "pdf") {
        $_SESSION['auth_status'] = "Sorry, only DOC, DOCX, & PDF files are allowed.";
        header('Location: Assessments_Code.php?assessment_id='.$assessment_id.'&error=Sorry, only DOC, DOCX, & PDF files are allowed.');
        exit();
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $_SESSION['auth_status'] = "Sorry, your file was not uploaded.";
        header('Location: Assessments_Code.php?assessment_id='.$assessment_id.'&error=Sorry, your file was not uploaded.');
        exit();
    } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES["submission_file"]["tmp_name"], $target_file)) {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO submissions (assessment_id, student_id, submission_file) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $assessment_id, $student_id, $target_file);

            // Execute the statement
            if ($stmt->execute()) {
                // Send confirmation email
                $user_email = $_SESSION['email']; // Assuming the user's email is stored in session
                $assessment_title = $assessment['title'];
                if (sendConfirmationEmail($user_email, $assessment_title)) {
                    $_SESSION['auth_status'] = "Activity submitted successfully";
                    header('Location: Assessments_View.php?assessment_id='.$assessment_id.'&success=Activity submitted successfully');
                    exit();
                } else {
                    $_SESSION['auth_status'] = "Error occurred while sending confirmation email.";
                    header('Location: Assessments_Code.php?assessment_id='.$assessment_id.'&error=Error occurred while sending confirmation email.');
                    exit();
                }
            } else {
                $_SESSION['auth_status'] = "Error: " . $stmt->error;
                header('Location: Assessments_Code.php?assessment_id='.$assessment_id.'&error=Error occurred while submitting the activity');
                exit();
            }

            // Close connections
            $stmt->close();
            $conn->close();
            exit();
        } else {
            $_SESSION['auth_status'] = "Sorry, there was an error uploading your file.";
            header('Location: Assessments_Code.php?assessment_id='.$assessment_id.'&error=Sorry, there was an error uploading your file.');
            exit();
        }
    }
}

$stmt->close();
ob_end_flush();

// Function to send confirmation email
function sendConfirmationEmail($to, $assessment_title) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;
        $mail->Username   = 'cocnambawan@gmail.com'; // Your Gmail
        $mail->Password   = 'bkvm sirf keww nswm'; // Your Gmail app password
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465; // TCP port to connect to

        //Recipients
        $mail->setFrom('cocnambawan@gmail.com', 'Submission');
        $mail->addAddress($to);  // Add a recipient
        $mail->addReplyTo('cocnambawan@gmail.com', 'Support');

        // Content
        $mail->isHTML(true);  // Set email format to HTML
        $mail->Subject = 'Task Submission Confirmation';
        $mail->Body    = "Dear Student,<br><br>Thank you for submitting your task. Here are the details of your submission:<br><br>";
        $mail->Body   .= "<strong>Task:</strong> $assessment_title<br><br>";
        $mail->Body   .= "We appreciate your effort and look forward to your continued participation.<br><br>Best Regards,<br>Your School";
        $mail->AltBody = "Dear Student,\n\nThank you for submitting your task. Here are the details of your submission:\n\n";
        $mail->AltBody .= "Task: $assessment_title\n\n";
        $mail->AltBody .= "We appreciate your effort and look forward to your continued participation.\n\nBest Regards,\nYour School";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Submit Activity</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Submit Activity</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <?php
    include ('../includes/message.php');
    ?>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Submit Activity</h3>
                        </div>
                        <div class="card-body">
                            <h1>Submit Activity for: <?php echo htmlspecialchars($assessment['title']); ?></h1>
                            <p><?php echo htmlspecialchars($assessment['description']); ?></p>
                            <form action="Assessments_Code.php?assessment_id=<?php echo $assessment_id; ?>" method="post" enctype="multipart/form-data">
                                <label for="submission_file">Upload your file:</label>
                                <input type="file" name="submission_file" id="submission_file" required><br><br>
                                <input type="submit" value="Submit">
                            </form>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
    </section>

</div>

<?php 
include("../includes/script.php");
?>
<?php 
include("footer.php");
?>
