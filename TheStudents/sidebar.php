<?php


// Check if the user is not logged in and redirect to loginform.php
if (!isset($_SESSION['auth'])) {
    header("Location: loginform.php");
    exit();
}
?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../TheStudents/student_Home.php" class="brand-link">
        <img src="assets/dist/img/RMK.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Rumaken University</span>
    </a>

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
                    <a href="../TheStudents/student_Home.php" class="nav-link"> <!-- Changed link here -->
                        <i class="nav-icon fas fa-school"></i> <!-- Changed icon here -->
                        <p>
                            University
                        </p>
                    </a>
                </li>

                <li class="nav-header">Function</li>
                <li class="nav-item">
                    <a href="../TheAdmin/instructors.php" class="nav-link">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Teachers
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../TheAdmin/Students.php" class="nav-link">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Student
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../TheAdmin/enroll_Subjects.php" class="nav-link">
                        <i class="nav-icon fa fa-check-circle"></i>
                        <p>
                            Enroll Subject
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../TheAdmin/Subjects.php" class="nav-link">
                        <i class="nav-icon fa fa-book"></i>
                        <p>
                            Subjects
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
