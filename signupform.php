<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Create Account</title>
    <link rel="icon" href="assets/dist/img/RMK.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .section {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #fff;
            border-bottom: none;
            font-weight: bold;
            font-size: 32px; /* Increased font size */
            color: #000000; /* Black text color */
            display: flex;
            align-items: center; /* Align logo and text vertically */
        }
        .card-header img {
            max-height: 60px; /* Increased logo size */
            margin-right: 10px;
        }
        .card-body {
            padding: 30px;
        }
        .alert {
            border-radius: 10px;
        }
        .btn-signup {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-signup:hover {
            color: #fff;
            background-color: #138496;
            border-color: #117a8b;
        }
    </style>
</head>
<body>
<div class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 my-5">
                <div class="card my-5">
                    <div class="card-header text-center">
                        <img src="assets/dist/img/RMK.png" alt="Rumaken University Logo" class="img-fluid">
                        <span style="font-size: 32px; margin-top: 8px;">Rumaken University</span>
                    </div>
                    <div class="card-body">
                        <?php
                            include('includes/message.php');
                        ?>
                        <!-- Your sign-up form goes here -->
                        <form action="signupcode.php" method="POST" id="signupForm">

                            <label for="cars">Choose a role:</label>
                                <select id="cars">
                                    <option value="student">Student</option>
                                    <option value="instructor">instructor</option>
                                </select>
                                <div class="form-group">
                                <label for="">Email</label>
                                <input type="text" name="email" class="form-control" placeholder="Email" required>
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
                            </div>

                            <input type="hidden" name="user_id" class="form-control" required>
                            
                            <div class="form-group">
                                <label for="">Full Name</label>
                                <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                            </div>

                            <div class="form-group">
                                <label for="">Birthdate</label>
                                <input type="date" name="birthdate" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control" placeholder="Phone Number" required>
                            </div>

                            <div class="form-group">
                                <label for="">Address</label>
                                <input type="text" name="address" class="form-control" placeholder="Address" required>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="profile_picture" class="form-control-file" >
                                <input type="hidden" name="Status" class="form-control">
                                <input type="hidden" name="Active" class="form-control">
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
</body>
</html>


<?php include('includes/script.php'); ?>
