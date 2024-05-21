<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth'])) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: loginform.php');
    exit();
}

// Include necessary files
include('../config/db_conn.php');
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
                        <li class="breadcrumb-item"><a href="../TheTeachers/teachers_Home.php">Home</a></li>
                        <li class="breadcrumb-item active">Enroll Subjects</li>
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
                                        <th>Add Subjects</th>
                                        <th>Remove all subject for this student</th>
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
                                                    <td>
                                                        <a href="../TheTeachers/teachersbject.php?student_id=<?php echo $row['student_id'] ?>" class="btn btn-info btn-sm">Add</a>
                                                    </td>
                                                    <td>
                                                    
                                                        <!-- Delete Subject Button -->
                                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteSubjectModal<?php echo $row['student_id']; ?>">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Subject Modal -->
                                                <div class="modal fade" id="editSubjectModal<?php echo $row['student_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Subject</h1>
                                                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="edit_Subject.php" method="post">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
                                                                    <div class="form-group">
                                                                        <label for="subject_name">Subject Name</label>
                                                                        <input type="text" id="subject_name" name="subject_name" class="form-control" placeholder="Subject Name">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" name="editSubjectbtn" class="btn btn-primary">Save Changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Delete Subject Modal -->
                                                <div class="modal fade" id="deleteSubjectModal<?php echo $row['student_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Subject</h1>
                                                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="delete_Subject.php" method="post">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
                                                                    <p>Are you sure you want to delete this subject?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" name="deleteSubjectbtn" class="btn btn-primary">Yes, Delete!</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

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
<?php include("../includes/footer.php"); ?>
