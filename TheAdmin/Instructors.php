<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
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

<!-- Add Instructor Modal -->
<div class="modal fade" id="addInstructorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Instructor</h1>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="Instructor_add_delete_update.php" method="post">
      <div class="modal-body">

        <div class="form-group">
          <input type="hidden" id="user_id" name="user_id" class="form-control">
          <input type="hidden" name="role" class="form-control" >
          <input type="hidden" id="password" name="password" class="form-control">
          <input type="hidden" name="user_id" class="form-control"> 
          <input type="hidden" name="Status" class="form-control">
          <input type="hidden" name="Active" class="form-control">
          <input type="hidden" name="profile_picture" class="form-control-file" >
        </div>

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" name="full_name" class="form-control" placeholder="Full Name" title="Format: First Name Middle Name (if applicable) Last Name" required>
            <small>Format: First Name Middle Name (if applicable) Last Name</small>
        </div>

        <div class="form-group">
          <label for="birthdate">Birthdate</label>
          <input type="date" id="birthdate" name="birthdate" class="form-control" placeholder="Birthdate" required>
        </div>

        <div class="form-group">
          <label for="gender">Gender</label>
          <select id="gender" name="gender" class="form-control" required>
            <option value="MALE">MALE</option>
            <option value="FEMALE">FEMALE</option>
            <option value="PREFER NOT TO SAY">PREFER NOT TO SAY</option>
          </select>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Email" title="Please use proper format (e.g email@gmail.com)" required>
          <span id="emailError" style="color: red;"></span>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="tel" name="phone_number" class="form-control" placeholder="+63XXXXXXXXXX" pattern="^\+\d{1,3}\d{4,14}$" required>
            <small>Format: +CountryCodePhoneNumber (e.g., +63XXXXXXXXXX)</small>
        </div>

        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" id="address" name="address" class="form-control" placeholder="Address" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="addInstructor" class="btn btn-primary">Add Instructor</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- update Instructor Modal -->
<div class="modal fade" id="updateInstructorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Update Instructor</h1>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="Instructor_add_delete_update.php" method="post">
        <div class="modal-body">

        <input type="hidden" id="update_instructor_id" name="update_instructor_id" class="form-control">

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" name="full_name" class="form-control" placeholder="Full Name" title="Format: First Name Middle Name (if applicable) Last Name" required>
            <small>Format: First Name Middle Name (if applicable) Last Name</small>
        </div>

        <div class="form-group">
          <label for="update_birthdate">Birthdate</label>
          <input type="date" id="update_birthdate" name="update_birthdate" class="form-control" placeholder="Birthdate" required>
        </div>
        
        <div class="form-group">
          <label for="update_gender">Gender</label>
          <select id="update_gender" name="update_gender" class="form-control" required>
            <option value="MALE">MALE</option>
            <option value="FEMALE">FEMALE</option>
            <option value="PREFER NOT TO SAY">PREFER NOT TO SAY</option>
          </select>
        </div>

        
        <div class="form-group">
          <label for="update_phone_number">Phone Number</label>
          <input type="tel" id="update_phone_number" name="update_phone_number" class="form-control" placeholder="+63XXXXXXXXXX" pattern="^\+\d{1,3}\d{4,14}$" required>
          <small>Format: +CountryCodePhoneNumber (e.g., +63XXXXXXXXXXX)</small>
        </div>
            
        <div class="form-group">
          <label for="update_address">Address</label>
          <input type="text" id="update_address" name="update_address" class="form-control" placeholder="Address" required>
        </div>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="updateInstructor" class="btn btn-primary">Update</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Instructor Modal -->
<div class="modal fade" id="deleteInstructorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Instructor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="Instructor_add_delete_update.php" method="post">
        <div class="modal-body">
          <input type="hidden" id="delete_user_id" name="delete_user_id">
          <p>Are you sure you want to delete this instructor?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" name="deleteInstructor" class="btn btn-danger">Delete</button>
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
        <h1 class="m-0">Instructors</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="admin_Home.php">Home</a></li>
          <li class="breadcrumb-item active">Instructors</li>
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
        <!-- PHP code for displaying session status -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Instructors</h3>
            <!-- Add Instructor Button -->
            <button type="button" class="btn btn-primary bt-sm float-right" data-toggle="modal" data-target="#addInstructorModal">Add Instructor</button>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Instructor ID</th>
                  <th>Name</th>
                  <th>Birthdate</th>
                  <th>Age</th>
                  <th>Gender</th>
                  <th>Email</th>
                  <th>Phone Number</th>
                  <th>Address</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $query = "SELECT instructors.*, users.user_id
                          FROM instructors JOIN users
                          ON instructors.instructor_id = users.user_id";

                $run_query = mysqli_query($conn, $query);
                
                if (!$run_query) {
                  die('Query Error: ' . mysqli_error($conn));
                }
                
                if(mysqli_num_rows($run_query) > 0) {
                  while ($row = mysqli_fetch_assoc($run_query)) {
                    ?>
                    <tr>
                      <td><?php echo $row['instructor_id'] ?></td>
                      <td><?php echo $row['full_name'] ?></td>
                      <td><?php echo $row['birthdate'] ?></td>
                      <td><?php echo $row['age'] ?></td>
                      <td><?php echo $row['gender'] ?></td>
                      <td><?php echo $row['email'] ?></td>
                      <td><?php echo $row['phone_number'] ?></td>
                      <td><?php echo $row['address'] ?></td>
                      <td>
                        <!-- Update Instructor Button -->
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#updateInstructorModal" onclick="updateInstructor('<?php echo $row['instructor_id']; ?>', '<?php echo $row['full_name']; ?>', '<?php echo $row['birthdate']; ?>', '<?php echo $row['gender']; ?>', '<?php echo $row['phone_number']; ?>', '<?php echo $row['address']; ?>')">Update</button>
                        <!-- Delete Instructor Button -->
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteInstructorModal" onclick="deleteInstructor('<?php echo $row['user_id']; ?>')">Delete</button>
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

<script>
function updateInstructor(instructor_id, full_name, birthdate, gender, phone_number, address) {
  document.getElementById("update_instructor_id").value = instructor_id;
  document.getElementById("update_full_name").value = full_name;
  document.getElementById("update_birthdate").value = birthdate;
  document.getElementById("update_gender").value = gender;
  document.getElementById("update_phone_number").value = phone_number;
  document.getElementById("update_address").value = address;
}

function deleteInstructor(user_id) {
  document.getElementById("delete_user_id").value = user_id;
}
</script>


<?php 
include("../includes/script.php");
?>
<?php 
include("footer.php");
?>
