<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Login</title>
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
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header text-center">
                        <img src="assets/dist/img/RMK.png" alt="Rumaken University Logo" class="img-fluid">
                        <span style="font-size: 32px; margin-top: 8px;">Rumaken University</span>
                    </div>
                    <div class="card-body">
                        <?php
                            session_start();
                            include('includes/message.php');
                        ?>
                        <form action="logincode.php" method="POST">

                            <div class="form-group">
                                <label for="role">Choose A Role:</label>
                                        <select name="role" id="role">
                                            <option value="student">STUDENT</option>
                                            <option value="instructor">INSTRUCTOR</option>
                                            <option value="admin">ADMIN</option>
                                        </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <button type="submit" name="login_btn" class="btn btn-primary btn-block">Login</button>
                            </div> 

                        </form>
                            <div class="text-center">
                                <p>Don't have an account? <a href="signupform.php" class="btn-sm">Sign Up</a></p>
                            </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
