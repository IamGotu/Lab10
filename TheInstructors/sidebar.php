<?php
// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: ../loginform.php');
    exit();
}

$user_details = $_SESSION['user_details'];
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../TheTeachers/teachers_Home.php" class="brand-link">
        <img src="assets/dist/img/RMK.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Rumaken University</span>
    </a>

        <!-- Sidebar -->
        <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo 'assets/dist/img/' . $user_details['profile_picture']; ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="User_Profile.php" class="d-block"><?php echo $user_details['full_name']; ?></a>
            </div>
        </div>


        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="../TheTeachers/teachers_Home.php" class="nav-link"> <!-- Changed link here -->
                        <i class="nav-icon fas fa-school"></i> <!-- Changed icon here -->
                        <p>
                            University
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="../TheTeachers/instructors.php" class="nav-link">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Instructors
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../TheTeachers/Students.php" class="nav-link">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Students
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../TheTeachers/Subjects.php" class="nav-link">
                        <i class="nav-icon fa fa-book"></i>
                        <p>
                            Subjects
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../TheTeachers/Assessments.php" class="nav-link">
                        <i class="nav-icon fa fa-tasks"></i>
                        <p>
                            Assessments
                        </p>
                    </a>
                </li>


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
