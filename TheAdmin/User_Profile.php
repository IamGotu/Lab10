<?php
include('Authentication.php');
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
include('config/db_conn.php');
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
        include('message.php');
    ?>

<section class="content">
    <div class="container-fluid">
            
            <!-- User's Information -->
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User's Information</h3>
                    </div>
                    <br>
                    <div class="card-body">
                    <div style="display: flex; justify-content: center;">
                        <img src="<?php echo 'uploads/' . $profilePicture; ?>" class="profile-image img-circle elevation-3" style="opacity: .8; width: 200px; height: 200px;">
                    </div>

                    <?php
                    // Assuming you have validated the user's login credentials
                    $user_id = $_SESSION['auth_user']['user_id']; // Use the logged-in user's ID
                    
                    $query = "SELECT full_name, birthdate, email, phone_number, address, password FROM user_profile WHERE user_id = ?";
                    if ($stmt = mysqli_prepare($conn, $query)) {
                        mysqli_stmt_bind_param($stmt, "i", $user_id);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $full_name, $birthdate, $email, $phone_number, $address, $password);
                        mysqli_stmt_fetch($stmt);
                        mysqli_stmt_close($stmt);
                        
                        // Store the birthdate in the session
                        $_SESSION['auth_user']['full_name'] = $full_name;
                        $_SESSION['auth_user']['email'] = $email;
                        $_SESSION['auth_user']['phone_number'] = $phone_number;
                        $_SESSION['auth_user']['address'] = $address;
                        $_SESSION['auth_user']['birthdate'] = $birthdate;
                        $_SESSION['auth_user']['password'] = $password;
                    }
                    ?>

                    <br><br>
                        <label>Full Name</label>
                        <br>
                        <?php echo $_SESSION['auth_user']['full_name'] ?>
                        <br><br>
                        <label>Email</label
                        ><br>
                        <?php echo $_SESSION['auth_user']['email'] ?>
                        <br><br>
                        <label>Phone Number</label>
                        <br>
                        <?php echo $_SESSION['auth_user']['phone_number'] ?>
                        <br><br>
                        <label>Address</label>
                        <br>
                        <?php echo $_SESSION['auth_user']['address'] ?>
                        <br><br>
                        <label>Birthdate</label>
                        <br>
                        <?php echo $_SESSION['auth_user']['birthdate'] ?>


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

                            <input type="hidden" id="user_id" name="user_id" class="form-control" value="<?php echo $_SESSION['auth_user']['user_id'] ?>" required>
            
                            <div class="form-group">
                                <label for="profile_picture">Profile Picture</label>
                                <input type="file" id="profile_picture" name="profile_picture" class="form-control-file" required>
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
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo $_SESSION['auth_user']['user_id'] ?>">

                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Full Name" value="<?php echo $_SESSION['auth_user']['full_name'] ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="Phone Number" value="<?php echo $_SESSION['auth_user']['phone_number'] ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" class="form-control" placeholder="Address" value="<?php echo $_SESSION['auth_user']['address'] ?>" required>
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
                            <input type="hidden" id="user_id" name="user_id" class="form-control" value="<?php echo $_SESSION['auth_user']['user_id'] ?>" required>

                            <div class="form-group">
                                <label for="">Birthdate</label>
                                <input type="date" id="birthdate" name="birthdate" class="form-control" value="<?php echo $_SESSION['auth_user']['birthdate'] ?>" required>
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
                                    <input type="hidden" id="user_id" name="user_id" class="form-control" value="<?php echo $_SESSION['auth_user']['user_id'] ?>">

                                    <div class="col-md">
                                        <div class="form-group">
                                            <label for="new_password">New Password</label>
                                            <input type="password" name="new_password" class="form-control" placeholder="Password" required>
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

<?php include('includes/script.php'); ?>
<?php include('includes/footer.php'); ?>
