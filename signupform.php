<?php
session_start();
include('config/db_conn.php');
include('includes/header.php');
?>



<div class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 my-5">
                <div class="card my-5">
                    <div class="card-header bg-light">
                        <h5>Create Account</h5>
                    </div>
                    <div class="card-body">
                        <!-- Your sign-up form goes here -->
                        <form action="signupcode.php" method="POST" id="signupForm">

                        <?php
                        include('message.php');
                        ?>
    
                            <div class="form-group">
                                <label for="">Full Name</label>
                                <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                            </div>

                            <div class="form-group">
                                <label for="">Birthdate</label>
                                <input type="date" name="birthdate" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="text" name="email" class="form-control" placeholder="Email" required>
                            </div>

                            <div class="form-group">
                                <label for="">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control" placeholder="Phone Number" required>
                            </div>

                            <div class="form-group">
                                <label for="">Address</label>
                                <input type="text" name="address" class="form-control" placeholder="Address" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Password</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Confirm Password</label>
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>
                                        <span id="password_message" class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="profile_picture" class="form-control-file" >
                                    <input type="hidden" name="Status" class="form-control">
                                    <input type="hidden" name="Active" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <button type="submit" name="signup_btn" class="btn btn-primary btn-block">Sign Up</button>
                            </div>
                        </form>
                        <!-- Link to login page -->
                        <div class="text-center">
                            <p>Already have an account? <a href="Loginform.php">Log in</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/script.php'); ?>
<?php include('includes/footer.php'); ?>
