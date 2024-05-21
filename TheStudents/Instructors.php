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

<!-- Add Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Teacher</h1>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="teachcode.php" method="post" onsubmit="return validateForm()">
      <div class="modal-body">
        <div class="form-group">
          <input type="hidden" id="teacher_id" name="teacher_id" class="form-control">
        </div>
        <div class="form-group">
          <label for="full_name">Full Name</label>
          <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Full Name" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
          <span id="emailError" style="color: red;"></span>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
          <span id="passwordError" style="color: red;"></span>
        </div>
        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" id="address" name="address" class="form-control" placeholder="Address" required>
        </div>
        <div class="form-group">
          <label for="age">Age</label>
          <input type="number" id="age" name="age" class="form-control" placeholder="Age" required>
        </div>
        <div class="form-group">
          <label for="gender">Gender</label>
          <select id="gender" name="gender" class="form-control" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="addTeacher" class="btn btn-primary">Add Teacher</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Teacher Modal -->
<div class="modal fade" id="editTeacherModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Teacher</h1>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="edit_Instructor.php" method="post">
      <div class="modal-body">
        <div class="form-group">
          <input type="hidden" id="edit_teacher_id" name="edit_teacher_id" class="form-control">
        </div>
        <div class="form-group">
          <label for="edit_name">Full Name</label>
          <input type="text" id="edit_name" name="edit_name" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="edit_email">Email</label>
          <input type="email" id="edit_email" name="edit_email" class="form-control" required>
        </div>
        
        <div class="form-group">
          <label for="edit_address">Address</label>
          <input type="text" id="edit_address" name="edit_address" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="edit_age">Age</label>
          <input type="number" id="edit_age" name="edit_age" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="edit_gender">Gender</label>
          <select id="edit_gender" name="edit_gender" class="form-control" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="updateTeacher" class="btn btn-primary">Update Teacher</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Teacher Modal -->
<div class="modal fade" id="deleteTeacherModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Teacher</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="delete_InstructorCode.php" method="post">
        <div class="modal-body">
          <input type="hidden" id="delete_teacher_id" name="delete_teacher_id">
          <p>Are you sure you want to delete this teacher?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" name="deleteTeacher" class="btn btn-danger">Delete</button>
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
            <!-- Add Teacher Button -->
            <button type="button" class="btn btn-primary bt-sm float-right" data-toggle="modal" data-target="#addTeacherModal">Add Teacher</button>
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
                  <th>Actions</th>
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
                      <td>
                        <!-- Edit Teacher Button -->
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editTeacherModal" onclick="editTeacher('<?php echo $row['teacher_id']; ?>', '<?php echo $row['full_name']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['address']; ?>')">Edit</button>
                        <!-- Delete Teacher Button -->
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteTeacherModal" onclick="deleteTeacher('<?php echo $row['teacher_id']; ?>')">Delete</button>
                      </td>
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

<script>
function editTeacher(teacher_id, full_name, email, address) {
  document.getElementById("edit_teacher_id").value = teacher_id;
  document.getElementById("edit_name").value = full_name;
  document.getElementById("edit_email").value = email;
  document.getElementById("edit_address").value = address;
}

function deleteTeacher(teacher_id) {
  document.getElementById("delete_teacher_id").value = teacher_id;
}
</script>

<?php 
include("../includes/footer.php");
?>
