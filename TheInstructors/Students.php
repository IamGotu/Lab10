<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
  $_SESSION['auth_status'] = "You need to be logged in to access this page";
  header('Location: ../loginform.php');
  exit();
}

// Specificaly instructor access only
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
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Students</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
          <li class="breadcrumb-item active">Students</li>
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
            <h3 class="card-title">Students</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                <th>ID</th>
                  <th>Name</th>
                  <th>Birthdate</th>
                  <th>Age</th>
                  <th>Gender</th>
                  <th>Email</th>
                  <th>Phone Number</th>
                  <th>Address</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $query = "SELECT students.*, users.user_id
                          FROM students JOIN users
                          ON students.student_id = users.user_id";

                $run_query = mysqli_query($conn, $query);
                
                if (!$run_query) {
                  die('Query Error: ' . mysqli_error($conn));
                }
                
                if(mysqli_num_rows($run_query) > 0) {
                  while ($row = mysqli_fetch_assoc($run_query)) {
              ?>
                    <tr>
                      <td><?php echo $row['student_id'] ?></td>
                      <td><?php echo $row['full_name'] ?></td>
                      <td><?php echo $row['birthdate'] ?></td>
                      <td><?php echo $row['age'] ?></td>
                      <td><?php echo $row['gender'] ?></td>
                      <td><?php echo $row['email'] ?></td>
                      <td><?php echo $row['phone_number'] ?></td>
                      <td><?php echo $row['address'] ?></td>
                    </tr>
                    <?php
                  }
                } else {
                  echo "<tr><td colspan='5'>No records found</td></tr>";
                }
                ?>
              </tbody>
            </table>
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