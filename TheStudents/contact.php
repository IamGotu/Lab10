<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
  $_SESSION['auth_status'] = "You need to be logged in to access this page";
  header('Location: ../loginform.php');
  exit();
}

// Specificaly admin access only
$required_role = 'student';

// Check if the user has the required role
if ($_SESSION['role'] !== $required_role) {
    $_SESSION['auth_status'] = "You do not have permission to access this page";
    header('Location: Dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <!-- Add your CSS links here -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 800px;
            background-color: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .container:hover {
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        h2 {
            color: #343a40;
            margin-top: 30px;
        }

        p {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .contact-info {
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }

        .contact-info h3 {
            color: #007bff;
            margin-bottom: 10px;
        }

        .contact-info img {
            width: 100px; 
            margin-right: 20px;
            border: 2px solid #007bff; /* Added border */
            border-radius: 50%; /* Rounded border */
        }

        .contact-info-details {
            flex: 1;
        }

        .social-links {
            text-align: center;
            margin-top: 20px;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
            font-size: 24px;
            color: #007bff;
            transition: color 0.3s ease;
        }

        .social-links a:hover {
            color: #00bfff;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(45deg, #007bff, #00bfff);
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 25px;
            transition: background 0.3s ease;
            margin-right: 10px;
        }

        .btn:hover {
            background: linear-gradient(45deg, #00bfff, #007bff);
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Contact</h1>
    <div class="contact-info">
        <img src="assets/dist/img/rus.jpg" alt="Logo">
        <div class="contact-info-details">
            <h3>Russell B. Osias</h3>
            <p>Email: osiasrussell@gmail.com</p>
            <p>Phone: 09123456768</p>
            <a href="https://www.facebook.com/Russellxd.newbie" class="btn" target="_blank">Facebook</a>
        </div>
    </div>
    <div class="contact-info">
        <img src="assets/dist/img/ken.jpg" alt="Logo">
        <div class="contact-info-details">
            <h3>Kenneth Bacaresas</h3>
            <p>Email: ken.ae26@gmail.com</p>
            <p>Phone: 09512489947</p>
            <a href="https://www.facebook.com/ken.bacaresas" class="btn" target="_blank">Facebook</a>
        </div>
    </div>
    <div class="contact-info">
        <img src="assets/dist/img/s.jpg" alt="Logo">
        <div class="contact-info-details">
            <h3>Mark John Jopia</h3>
            <p>Email: markjohnjopia1@gmail.com</p>
            <p>Phone: 09514810354</p>
            <a href="https://www.facebook.com/profile.php?id=100074081229184" class="btn" target="_blank">Facebook</a>
        </div>
    </div>
    <p style="text-align: center; margin-bottom: 20px;">For any inquiries or issues regarding the website, please feel free to contact any of the creators.</p>
    <div style="text-align: center;">
        <a href="../TheStudents/Dashboard.php" class="btn">Back to Home</a>
    </div>
</div>

</body>
</html>
