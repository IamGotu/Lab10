<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: ../loginform.php');
    exit();
    }

$user_details = $_SESSION['user_details'];

// Specificaly admin access only
$required_role = 'admin';

// Check if the user has the required role
if ($_SESSION['role'] !== $required_role) {
    $_SESSION['auth_status'] = "You do not have permission to access this page";
    header('Location: Dashboard.php');
    exit();
}

include('../includes/header.php');
include('topbar.php');
include('sidebar.php');
include('../database/db_conn.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">   

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profile</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <?php
        include('../includes/message.php');
    ?>

<section class="content">
    <div class="container-fluid">
            
            <!-- User's Information -->
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User's Profile</h3>
                    </div>
                    <br>
                    <div class="card-body">
                    <div style="display: flex; justify-content: center;">
                        <img src="<?php echo 'assets/dist/img/' . $user_details['profile_picture']; ?>" class="profile-image img-circle elevation-3" style="opacity: .8; width: 200px; height: 200px;">
                    </div>

                        <br><br>
                        <label>Full Name</label>
                        <br>
                        <?php echo $user_details['full_name'] ?>

                        <br><br>
                        <label>Birthdate</label>
                        <br>
                        <?php echo $user_details['birthdate'] ?>

                        <br><br>
                        <label>Age</label>
                        <br>
                        <?php echo $user_details['age'] ?>

                        <br><br>
                        <label>Gender</label>
                        <br>
                        <?php echo $user_details['gender'] ?>

                        <br><br>
                        <label>Email</label
                        ><br>
                        <?php echo $user_details['email'] ?>

                        <br><br>
                        <label>Phone Number</label>
                        <br>
                        <?php echo $user_details['phone_number'] ?>

                        <br><br>
                        <label>Address</label>
                        <br>
                        <?php echo $user_details['address'] ?>

                    </div>
                </div>
            </div>

            <!-- Updating User's Profile Picture -->
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update User's Profile Picture</h3>
                    </div>
                    <div class="card-body">
                        
                        <form action="Update_Profile.php" method="POST" enctype="multipart/form-data">

                            <input type="hidden" id="admin_id" name="admin_id" class="form-control" value="<?php echo $user_details['admin_id'] ?>" required>
            
                            <div class="form-group">
                                <label for="profile_picture">Profile Picture</label>
                                <input type="file" id="profile_picture" name="profile_picture" value="<?php echo $user_details['profile_picture'] ?>" class="form-control-file" required>
                            </div>

                            <div class="text-right">
                                <button type="submit" name="UpdatePicture" class="btn btn-info">Update</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- Updating User Information -->
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update User's Information</h3>
                    </div>
                    <div class="card-body">

                    <form action="Update_Profile.php" method="POST" enctype="multipart/form-data">

                        <input type="hidden" id="admin_id" name="admin_id" value="<?php echo $user_details['admin_id'] ?>">

                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" name="full_name" class="form-control" placeholder="Full Name" value="<?php echo $user_details['full_name'] ?>" title="Format: First Name Middle Name (if applicable) Last Name" required>
                            <small>Format: First Name Middle Name (if applicable) Last Name</small>
                        </div>

                        <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="MALE" <?php echo ($user_details['gender'] == 'MALE') ? 'selected' : ''; ?>>MALE</option>
                            <option value="FEMALE" <?php echo ($user_details['gender'] == 'FEMALE') ? 'selected' : ''; ?>>FEMALE</option>
                            <option value="PREFER NOT TO SAY" <?php echo ($user_details['gender'] == 'PREFER NOT TO SAY') ? 'selected' : ''; ?>>PREFER NOT TO SAY</option>
                        </select>
                        </div>

        
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="tel" id="phone_number" name="phone_number" class="form-control" placeholder="+63XXXXXXXXXX" value="<?php echo $user_details['phone_number'] ?>" pattern="^\+\d{1,3}\d{4,14}$" required>
                            <small>Format: +CountryCodePhoneNumber (e.g., +63XXXXXXXXXXX)</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" class="form-control" placeholder="Address" value="<?php echo $user_details['address'] ?>" required>
                        </div>   

                        <div class="text-right">
                            <button type="submit" name="UpdateInfo" class="btn btn-info">Update</button>
                        </div>
                    </form>

                    </div>
                </div>
            </div>

            <!-- Updating User's Birthdate -->
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update User's Birthdate</h3>
                    </div>
                    <div class="card-body">
                        
                        <form action="Update_Profile.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="admin_id" name="admin_id" class="form-control" value="<?php echo $user_details['admin_id'] ?>" required>

                            <div class="form-group">
                                <label for="">Birthdate</label>
                                <input type="date" id="birthdate" name="birthdate" class="form-control" value="<?php echo $user_details['birthdate'] ?>" required>
                            </div>

                                <div class="text-right">
                                <button type="submit" name="UpdateBirthdate" class="btn btn-info">Update</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>


                <!-- Updating User Password -->
                 <div class="col-md">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Update User's Password</h3>
                            </div>
                            <div class="card-body">

                                <form action="Update_Profile.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" id="admin_id" name="admin_id" class="form-control" value="<?php echo $user_details['admin_id'] ?>">

                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="new_password">New Password</label>
                                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder=" New Password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{5,}" title="Password must contain at least one uppercase letter, one lowercase letter, one special character, and be at least 5 characters long." required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="confirm_password">Confirm Password</label>
                                            <input type="password" name="confirm_password" class="form-control" placeholder="Password" required>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" name="UpdatePass" class="btn btn-info">Update</button>
                                    </div>
                                </form>
                                    
                            </div>
                        </div>
                    </div>

    </div>
</section>
</div>

<?php include('../includes/script.php'); ?>
<?php include('footer.php'); ?>
