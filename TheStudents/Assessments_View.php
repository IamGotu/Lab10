<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
  $_SESSION['auth_status'] = "You need to be logged in to access this page";
  header('Location: ../loginform.php');
  exit();
}

// Specifically student access only
$required_role = 'student';

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

// Fetch all assessments
$query = "SELECT id, title, description, created_at FROM assessments";
$result = $conn->query($query);

// Fetch student ID from session
$student_id = $_SESSION['student_id'];

// Fetch submissions for the logged-in student
$submission_query = "SELECT assessment_id FROM submissions WHERE student_id = ?";
$submission_stmt = $conn->prepare($submission_query);
$submission_stmt->bind_param("i", $student_id);
$submission_stmt->execute();
$submitted_assessments = $submission_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$submitted_ids = array_column($submitted_assessments, 'assessment_id');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View Assessments </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">View Assessments</li>
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
              <h3 class="card-title">View Assessments</h3>
            </div>
            <div class="card-body">
              <h1>Available Assessments</h1>
              <ul>
                <?php while($row = $result->fetch_assoc()): 
                  $is_submitted = in_array($row['id'], $submitted_ids);
                  ?>
                  <li>
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <p><small>Created at: <?php echo htmlspecialchars($row['created_at']); ?></small></p>
                    <?php if ($is_submitted): ?>
                      <p><strong>Status:</strong> Already Submitted</p>
                    <?php else: ?>
                      <a href="Assessments_Code.php?assessment_id=<?php echo $row['id']; ?>">Submit Activity</a>
                    <?php endif; ?>
                  </li>
                <?php endwhile; ?>
              </ul>
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
