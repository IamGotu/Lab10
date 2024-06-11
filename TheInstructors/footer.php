<?php
// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
  $_SESSION['auth_status'] = "You need to be logged in to access this page";
  header('Location: ../loginform.php');
  exit();
}

// Specificaly instructor access only
$required_role = 'instructor';

// Check if the user has the required role
if ($_SESSION['role'] !== $required_role) {
    $_SESSION['auth_status'] = "You do not have permission to access this page";
    header('Location: ../logout.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website Title</title>
    <!-- Add your CSS links here -->
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<!-- Your existing code -->

<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div>


</body>
</html>
    <div class="float-right">
        <ul class="list-inline">
            <li class="list-inline-item"><a href="../TheInstructors/terms.php">Terms of Service</a></li>
            <li class="list-inline-item"><a href="../TheInstructors/cookie.php">Cookie Notice</a></li>
            <li class="list-inline-item"><a href="../TheInstructors/privacy.php">Privacy Policy</a></li>
            <li class="list-inline-item">Creators:
                <a href="https://www.facebook.com/Russellxd.newbie">Russell</a>,
                <a href="https://www.facebook.com/ken.bacaresas">Kenneth</a>,
                <a href="https://www.facebook.com/profile.php?id=100074081229184">Mark John<b></a>
            </li>
        </ul>
    </div>
</footer>
</div>
<!-- /.wrapper -->

</body>
</html>
