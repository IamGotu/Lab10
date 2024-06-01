<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth'])) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: ../loginform.php');
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
        <h1 class="m-0">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Teachers</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- PHP code for displaying session status -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Teachers</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Teacher ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Address</th>
                  <th>Age</th>
                  <th>Gender</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $query = "SELECT * FROM teachers";
                $run_query = mysqli_query($conn, $query);
                
                if (!$run_query) {
                  die('Query Error: ' . mysqli_error($conn));
                }
                
                if(mysqli_num_rows($run_query) > 0) {
                  while ($row = mysqli_fetch_assoc($run_query)) {
                    ?>
                    <tr>
                      <td><?php echo $row['teacher_id'] ?></td>
                      <td><?php echo $row['full_name'] ?></td>
                      <td><?php echo $row['email'] ?></td>
                      <td><?php echo $row['address'] ?></td>
                      <td><?php echo $row['age'] ?></td>
                      <td><?php echo $row['gender'] ?></td>
                    </tr>
                    <?php
                  }
                } else {
                  echo "<tr><td colspan='7'>No records found</td></tr>";
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
