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

// Fetch subjects from the database
$stmt = $conn->prepare("SELECT subject_id, title FROM subjects");
$stmt->execute();
$result = $stmt->get_result();

$subjects = array();
while ($row = $result->fetch_assoc()) {
    $subjects[] = $row;
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Assessments</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Create Assessments</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <?php include('../includes/message.php'); ?>

      <!-- Main content -->
      <section class="content">
          <div class="container-fluid">
              <div class="row">
                  <div class="col-md-12"> <!-- Adjusted column width -->
                      <div class="card">
                          <div class="card-header">
                              <h3 class="card-title">Create Assessments</h3>
                          </div>
                          <div class="card-body">
                              <form action="Assessments_Create_Code.php" method="POST">
                                  <div class="form-group">
                                      <label for="subject">Subject:</label>
                                      <select class="form-control" id="subject" name="subject" required>
                                          <option value="">Select Subject</option>
                                          <?php foreach ($subjects as $subject): ?>
                                              <option value="<?php echo $subject['subject_id']; ?>"><?php echo $subject['title']; ?></option>
                                          <?php endforeach; ?>
                                      </select>
                                  </div>

                                  <div class="form-group">
                                      <label for="title">Title:</label>
                                      <input type="text" class="form-control" id="title" name="title" required>
                                  </div>

                                  <div class="form-group">
                                      <label for="description">Description:</label>
                                      <textarea class="form-control" id="description" name="description" required></textarea>
                                  </div>

                                  <div class="text-right">
                                      <button type="submit" class="btn btn-primary">Create Assessment</button>
                                  </div>
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
