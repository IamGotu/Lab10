<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: ../loginform.php');
    exit();
}

// Specifically admin access only
$required_role = 'admin';

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

    <!-- Add Subject Modal -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="exampleModalLabel">Add Subject</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addSubjectForm" action="Subject_add_delete_update.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="subject_id">Subject ID</label>
                            <input type="text" name="subject_id" class="form-control" placeholder="Subject ID" required>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Subject Title" required>
                        </div>
                        <div class="form-group">
                            <label for="credits">Credits</label>
                            <input type="number" name="credits" class="form-control" placeholder="Credits" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="addSubject" class="btn btn-primary">Add Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Subject Modal -->
    <div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Subject</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editSubjectForm" action="Subject_add_delete_update.php" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_subject_id">Subject ID</label>
                            <input type="text" name="subject_id" id="edit_subject_id" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit_title">Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" placeholder="Subject Title" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_credits">Credits</label>
                            <input type="number" name="credits" id="edit_credits" class="form-control" placeholder="Credits" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="updateSubject" class="btn btn-primary">Update Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
    </div><!-- /.content-header -->

    <?php
    include('../includes/message.php');
    ?>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- PHP code for displaying session status -->
                    <?php
                    include ('../includes/message.php');
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Subjects</h3>
                            <!-- Button to trigger Add Subject Modal -->
                            <button type="button" class="btn btn-primary bt-sm float-right" data-toggle="modal" onclick="openAddSubjectModal()">Add Subject</button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Credit</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch subjects from the database
                                    $query = "SELECT * FROM subjects";
                                    $result = mysqli_query($conn, $query);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $row['subject_id'] . "</td>";
                                            echo "<td>" . $row['title'] . "</td>";
                                            echo "<td>" . $row['credits'] . "</td>";
                                            echo '<td>
                                                    <button class="btn btn-sm btn-info" onclick=\'openEditSubjectModal(' . json_encode($row) . ')\'>Edit</button>
                                                    <form action="Subject_add_delete_update.php" method="post" style="display:inline-block;">
                                                        <input type="hidden" name="subject_id" value="' . $row['subject_id'] . '">
                                                        <button type="submit" name="deleteSubject" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </td>';
                                            echo "</tr>";
                                        }
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

    <script>
        // Function to open add subject modal
        function openAddSubjectModal() {
            $('#addSubjectModal').modal('show');
        }

        // Function to open edit subject modal and populate form
        function openEditSubjectModal(subject) {
            $('#edit_subject_id').val(subject.subject_id);
            $('#edit_title').val(subject.title);
            $('#edit_credits').val(subject.credits);
            $('#editSubjectModal').modal('show');
        }
    </script>
</div>

<?php
include("../includes/script.php");
?>
<?php
include("footer.php");
?>

