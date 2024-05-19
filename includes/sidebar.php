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
    <a href="index.php" class="brand-link">
        <img src="assets/dist/img/RMK.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Rumaken University</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="assets/dist/img/12.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="User_Profile.php" class="d-block">Admin</a>
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
                    <a href="index.php" class="nav-link"> <!-- Changed link here -->
                        <i class="nav-icon fas fa-school"></i> <!-- Changed icon here -->
                        <p>
                            University
                        </p>
                    </a>
                </li>

                <li class="nav-header">Function</li>
                <li class="nav-item">
                    <a href="User_Profile.php" class="nav-link">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Teachers
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="student_list.php" class="nav-link">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Student
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="enrollSubject.php" class="nav-link">
                        <i class="nav-icon fa fa-check-circle"></i>
                        <p>
                            Enroll Subject
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="subjects.php" class="nav-link">
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
