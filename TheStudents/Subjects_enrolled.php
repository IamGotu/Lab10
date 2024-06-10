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
                        <li class="breadcrumb-item"><a href="../TheStudents/student_Home.php">Home</a></li>
                        <li class="breadcrumb-item active">Enrolled Subjects</li>
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
                        echo "<h4>" . $_SESSION['status'] . "</h4>";
                        unset($_SESSION['status']);
                    }
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Enroll Subjects</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="info" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Subjects Enrolled</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT student_id, full_name, course, subjects FROM student_list";
                                    $run_query = mysqli_query($conn, $query);
                                    if ($run_query) {
                                        if(mysqli_num_rows($run_query) > 0) {
                                            while ($row = mysqli_fetch_assoc($run_query)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['student_id'] ?></td>
                                                    <td><?php echo $row['full_name'] ?></td>
                                                    <td><?php echo $row['course'] ?></td>
                                                    <td>
                                                        <?php
                                                        if ($row['subjects']) {
                                                            // Convert the comma-separated string to an array
                                                            $enrolled_subjects = explode(', ', $row['subjects']);
                                                            // Output the subjects as a styled list
                                                            echo '<ul>';
                                                            foreach ($enrolled_subjects as $subject) {
                                                                echo '<li>' . $subject . '</li>';
                                                            }
                                                            echo '</ul>';
                                                        } else {
                                                            echo "No subjects enrolled";
                                                        }
                                                        ?>
                                                    </td>
                                                <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>No records found</td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>Error: " . mysqli_error($conn) . "</td></tr>";
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

<?php include("../includes/script.php"); ?>
<?php include("footer.php"); ?>
