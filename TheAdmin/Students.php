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
      <form action="add_delete_Student.php" method="post">
      <div class="modal-body">
          <input type="hidden" id="student_id" name="student_id" class="form-control">
        <div class="form-group">
          <label for="full_name">Full Name</label>
          <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Full Name">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Email">
        </div>
        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" id="address" name="address" class="form-control" placeholder="Address">
        </div>
        <div class="form-group">
          <label for="age">Age</label>
          <input type="text" id="age" name="age" class="form-control" placeholder="Age">
        </div>
        <div class="form-group">
          <label for="gender">Gender</label>
          <select id="gender" name="gender" class="form-control" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label for="course">Course</label>
          <input type="text" id="course" name="course" class="form-control" placeholder="Course">
        </div>
          <input type="hidden" id="password" name="password" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="addStudent" class="btn btn-primary">Enroll</button>
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
          <input type="hidden" id="edit_student_id" name="edit_student_id" class="form-control">
        <div class="form-group">
          <label for="edit_full_name">Full Name</label>
          <input type="text" id="edit_full_name" name="edit_full_name" class="form-control" placeholder="Full Name" required>
        </div>
        <div class="form-group">
          <label for="edit_email">Email</label>
          <input type="email" id="edit_email" name="edit_email" class="form-control" placeholder="Email" required>
        </div>
        <div class="form-group">
          <label for="edit_address">Address</label>
          <input type="text" id="edit_address" name="edit_address" class="form-control" placeholder="Address" required>
        </div>
        <div class="form-group">
          <label for="edit_age">Age</label>
          <input type="number" id="edit_age" name="edit_age" class="form-control" placeholder="Age" required>
        </div>
        <div class="form-group">
          <label for="edit_gender">Gender</label>
          <select id="edit_gender" name="edit_gender" class="form-control" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label for="edit_course">Course</label>
          <input type="text" id="edit_course" name="edit_course" class="form-control" placeholder="Course" required>
        </div>
        <div class="form-group">
          <label for="edit_password">Password</label>
          <input type="password" id="edit_password" name="edit_password" class="form-control" placeholder="Password" required>
        </div>
        <div class="form-group">
          <label for="edit_confirm_password">Confirm Password</label>
          <input type="password" id="edit_confirm_password" name="edit_confirm_password" class="form-control" placeholder="Confirm Password" required>
          <span id="passwordError" style="color: red;"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="updateStudent" class="btn btn-primary">Save Changes</button>
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
      <form action="add_delete_Student.php" method="post">
      <div class="modal-body">
        <input type="hidden" id="delete_student_id" name="delete_student_id">
        <p>Are you sure you want to delete this student?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="deleteStudent" class="btn btn-primary">Yes, Delete!</button>
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
          <li class="breadcrumb-item"><a href="admin_Home.php">Home</a></li>
          <li class="breadcrumb-item active">Enroll Students</li>
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
            <h3 class="card-title">Enroll Students</h3>
            <!-- Enroll Student Button -->
            <button type="button" class="btn btn-primary bt-sm float-right" data-toggle="modal" data-target="#enrollStudentModal">Enroll Student</button>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Address</th>
                  <th>Age</th>
                  <th>Gender</th>
                  <th>Course</th>
                  <th>Actions</th>
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
                      <td><?php echo $row['address'] ?></td>
                      <td><?php echo $row['age'] ?></td>
                      <td><?php echo $row['gender'] ?></td>
                      <td><?php echo $row['course'] ?></td>
                      <td>
                        <!-- Edit Student Button -->
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editStudentModal" onclick="editStudent('<?php echo $row['student_id']; ?>', '<?php echo $row['full_name']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['address']; ?>', '<?php echo $row['age']; ?>', '<?php echo $row['gender']; ?>', '<?php echo $row['course']; ?>', '<?php echo $row['password']; ?>',)">Edit</button>
                        <!-- Delete Student Button -->
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteStudentModal" onclick="deleteStudent('<?php echo $row['student_id']; ?>')">Delete</button>
                      </td>
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

<script>
function editStudent(student_id, full_name, email, address, age, gender, course, password) {
  document.getElementById("edit_student_id").value = student_id;
  document.getElementById("edit_full_name").value = full_name;
  document.getElementById("edit_email").value = email;
  document.getElementById("edit_address").value = address;
  document.getElementById("edit_age").value = age;
  document.getElementById("edit_gender").value = gender;
  document.getElementById("edit_course").value = course;
  document.getElementById("edit_password").value = password;
}

function deleteStudent(student_id) {
  document.getElementById("delete_student_id").value = student_id;
}
</script>

<?php 
include("footer.php");
?>