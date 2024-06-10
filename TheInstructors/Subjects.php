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
            <h1 class="m-0">Subjects</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">Subjects</li>
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

            <!-- /.card-header -->
            <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Course ID</th>
                            <th>Title</th>
                            <th>Department</th>
                            <th>Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php 
                      // Hardcoded subject data
                      $subjects = array(
                          array("BIO-101", "Intro. to Biology", "Biology", 4),
                          array("BIO-301", "Genetics", "Biology", 4),
                          array("BIO-399", "Computational Biology", "Biology", 3),
                          array("CS-101", "Intro. to Computer Science", "Comp. Sci.", 4),
                          array("CS-190", "Game Design", "Comp. Sci.", 4),
                          array("CS-315", "Robotics", "Comp. Sci.", 3),
                          array("CS-319", "Image Processing", "Comp. Sci.", 3),
                          array("CS-347", "Database System Concepts", "Comp. Sci.", 3),
                          array("EE-181", "Intro. to Digital Systems", "Elec. Eng.", 3),
                          array("FIN-201", "Investment Banking", "Finance", 3),
                          array("HIS-351", "World History", "History", 3),
                          array("MU-199", "Music Video Production", "Music", 3),
                          array("PHY-101", "Physical Principles", "Physics", 4)
                      );

                      // Display subjects
                      foreach ($subjects as $subject) {
                          echo "<tr>";
                          echo "<td>" . $subject[0] . "</td>";
                          echo "<td>" . $subject[1] . "</td>";
                          echo "<td>" . $subject[2] . "</td>";
                          echo "<td>" . $subject[3] . "</td>";
                          echo "</tr>";
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
