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

// Include necessary files
include('../database/db_conn.php');
include('../includes/header.php');
include('topbar.php');
include('sidebar.php');

// Get assessment ID from URL parameter
if (isset($_GET['assessment_id'])) {
    $assessment_id = $_GET['assessment_id'];
} else {
    $_SESSION['auth_status'] = "Assessment ID not provided.";
    header('Location: Assessment_View.php');
    exit();
}

// Fetch assessment details
$query = "SELECT title FROM assessments WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$result = $stmt->get_result();
$assessment = $result->fetch_assoc();

// Fetch submissions for this assessment
$submissions_query = "SELECT s.id, s.student_id, s.submission_file, s.submitted_at, s.grade, u.full_name 
                     FROM submissions s 
                     INNER JOIN students u ON s.student_id = u.student_id 
                     WHERE s.assessment_id = ?";
$submissions_stmt = $conn->prepare($submissions_query);
$submissions_stmt->bind_param("i", $assessment_id);
$submissions_stmt->execute();
$submissions_result = $submissions_stmt->get_result();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Grade Submissions - <?php echo htmlspecialchars($assessment['title']); ?></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="Assessment.php">Assessments</a></li>
                        <li class="breadcrumb-item active">Grade Submissions</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <?php
    include('../includes/message.php');
    ?>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Grade Submissions</h3>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Submission File</th>
                                        <th>Submitted At</th>
                                        <th>Grade</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($submission = $submissions_result->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($submission['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($submission['submission_file']); ?></td>
                                            <td><?php echo htmlspecialchars($submission['submitted_at']); ?></td>
                                            <td>
                                                <!-- Add input field for grading -->
                                                <input type="text" name="grade_<?php echo $submission['id']; ?>" value="<?php echo $submission['grade']; ?>">
                                            </td>
                                            <td>
                                                <!-- Add button to submit grade -->
                                                <button onclick="submitGrade(<?php echo $submission['id']; ?>)">Submit Grade</button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<script>
    function submitGrade(submissionId) {
        // Get the value of the grade input field
        var gradeInput = document.getElementsByName("grade_" + submissionId)[0];
        var grade = gradeInput.value;

        // Send an AJAX request to submit the grade
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "submit_grade.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert(response.success);
                        location.reload();
                    } else {
                        alert(response.error);
                    }
                } else {
                    console.error("Error submitting grade: " + xhr.responseText);
                }
            }
        };
        xhr.send("submission_id=" + encodeURIComponent(submissionId) + "&grade=" + encodeURIComponent(grade));
    }
</script>

<?php
include("../includes/script.php");
?>
<?php
include("footer.php");
?>
