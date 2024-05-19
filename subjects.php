<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth'])) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: loginform.php');
    exit();
}

// Include necessary files
include('config/db_conn.php');
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<!-- Modal -->
<div class="modal fade" id="usersModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add User</h1>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="code.php" method="post">
      <div class="modal-body">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" class="form-control" placeholder="Name" >
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Email" >
        </div>
        <div class="form-group">
          <label for="phone">PhoneNo.</label>
          <input type="text" id="phone" name="phone" class="form-control" placeholder="PhoneNo." >
        </div>
        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" id="address" name="address" class="form-control" placeholder="Address" >
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="pass">Password</label>
              <input type="password" id="pass" name="pass" class="form-control" placeholder="Password" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="confirm_pass">Confirm Password</label>
              <input type="password" id="confirm_pass" name="confirm_pass" class="form-control" placeholder="Confirm Password" required>
            </div>
          </div>
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

<!-- Delete User Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete User</h1>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="code.php" method="post">
      <div class="modal-body">
        <input type="hidden" name="delete_id" class="delete_user_id" >
        <p>
          Are you sure. you want to delete this data?
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="deleteUserbtn" class="btn btn-primary">Yes, Delete.!</button>
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
              <li class="breadcrumb-item"><a href="#">Home</a></li>
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
include("includes/script.php");
?>

<script>
  $(document).ready(function () {
    $('.deletebtn').click(function (e){
      e.preventDefault()

      var user_id = $(this).val();
      //console.log(user_id);
      $('.delete_user_id').val(user_id);
      $('#DeleteModal').modal('show');

    });
  });
</script>

<?php 
include("includes/footer.php");
?>
