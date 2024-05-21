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

<!-- Enroll Student Modal -->
<div class="modal fade" id="enrollStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Enroll Student</h1>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="close"></button>
      </div>
      <form action="add_StudentCode.php" method="post">
      <div class="modal-body">
        <div class="form-group">
          <input type="hidden" id="student_id" name="student_id" class="form-control">
        </div>
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" class="form-control" placeholder="Name">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Email">
        </div>
        <div class="form-group">
          <label for="course">Course</label>
          <input type="text" id="course" name="course" class="form-control" placeholder="Course">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="addUser" class="btn btn-primary">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Student</h1>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="close"></button>
      </div>
      <form action="edit_Student.php" method="post">
      <div class="modal-body">
        <input type="hidden" id="edit_student_id" name="edit_student_id">
        <div class="form-group">
          <label for="edit_name">Name</label>
          <input type="text" id="edit_name" name="edit_name" class="form-control" placeholder="Name">
        </div>
        <div class="form-group">
          <label for="edit_email">Email</label>
          <input type="email" id="edit_email" name="edit_email" class="form-control" placeholder="Email">
        </div>
        <div class="form-group">
          <label for="edit_course">Course</label>
          <input type="text" id="edit_course" name="edit_course" class="form-control" placeholder="Course">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="editUserbtn" class="btn btn-primary">Save Changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Student Modal -->
<div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Student</h1>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="close"></button>
      </div>
      <form action="add_StudentCode.php" method="post">
      <div class="modal-body">
        <input type="hidden" id="delete_student_id" name="delete_student_id">
        <p>Are you sure you want to delete this student?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="deleteUserbtn" class="btn btn-primary">Yes, Delete!</button>
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
        <h1 class="m-0">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Enrolled Students</li>
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
          echo "<h4>".$_SESSION['status']."</h4>";
          unset($_SESSION['status']);
        }
        ?>
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Enrolled Students</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Course</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $query = "SELECT * FROM student_list";
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
                      <td><?php echo $row['email'] ?></td>
                      <td><?php echo $row['course'] ?></td>
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
include("../includes/footer.php");
?>