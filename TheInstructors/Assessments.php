<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth'])) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: loginform.php');
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
            <h1 class="m-0">Assessments </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="teachers_Home.php">Home</a></li>
              <li class="breadcrumb-item active">Assessments</li>
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
            <?php   
            if (isset($_SESSION['status'])) {
              echo "<h4>".$_SESSION['status']."</h4>" ;
              unset($_SESSION['status']);
            }

            ?>

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
