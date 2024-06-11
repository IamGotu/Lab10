<?php
ob_start();
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: ../loginform.php');
    exit();
}

// Specifically admin access only
$required_role = 'admin';

// Check if the user has the required role
if ($_SESSION['role'] !== $required_role) {
    $_SESSION['auth_status'] = "You do not have permission to access this page";
    header('Location: ../logout.php');
    exit();
}

// Include necessary files
include('../database/db_conn.php');
include('../includes/header.php');
include('topbar.php');
include('sidebar.php');

// Function to calculate total credits for a student
function calculateTotalCredits($enrolled_subjects, $conn) {
    $total_credits = 0;
    foreach ($enrolled_subjects as $subject) {
        // Query the subjects table to get the credits for each enrolled subject
        $query = "SELECT credits FROM subjects WHERE subject_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $subject);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total_credits += $row['credits'];
        }
    }
    return $total_credits;
}

// Function to update total credits for a student
function updateTotalCredits($student_id, $conn) {
    $query = "SELECT subjects FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $enrolled_subjects = explode(', ', $row['subjects']);
        $total_credits = calculateTotalCredits($enrolled_subjects, $conn);
        
        // Update the total credits for the student
        $update_query = "UPDATE students SET total_credits = ? WHERE student_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("di", $total_credits, $student_id);
        return $update_stmt->execute();
    }
    return false;
}

// Function to remove all subjects from a student
function removeAllSubjects($student_id, $conn) {
    $update_query = "UPDATE students SET subjects = '', total_credits = 0 WHERE student_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("i", $student_id);
    return $update_stmt->execute();
}

// Check if the form is submitted to add a subject
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_subject_btn'])) {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    
    // Fetch the current subjects enrolled by the student
    $query = "SELECT subjects, total_credits FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $enrolled_subjects = explode(', ', $row['subjects']);
        $total_credits = $row['total_credits'];
        
        // Check if the subject is already enrolled by the student
        if (in_array($subject_id, $enrolled_subjects)) {
            $_SESSION['auth_status'] = "Subject already enrolled!";
        } else {
            // Fetch the credits of the subject to be enrolled
            $query = "SELECT credits FROM subjects WHERE subject_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $subject_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $subject_credits = $row['credits'];
                
                // Check if enrolling the subject exceeds maximum credits
                if ($total_credits >= 18) {
                    $_SESSION['auth_status'] = "Cannot enroll in more subjects. Total credits limit reached!";
                } elseif (($total_credits + $subject_credits) > 20) {
                    $_SESSION['auth_status'] = "Maximum credits limit exceeded!";
                } else {
                    // Enroll the subject
                    $enrolled_subjects[] = $subject_id;
                    $enrolled_subjects_str = implode(', ', $enrolled_subjects);
                    
                    // Update the subjects for the student
                    $update_query = "UPDATE students SET subjects = ? WHERE student_id = ?";
                    $update_stmt = $conn->prepare($update_query);
                    $update_stmt->bind_param("si", $enrolled_subjects_str, $student_id);
                    if ($update_stmt->execute()) {
                        // Update total credits
                        if (updateTotalCredits($student_id, $conn)) {
                            $_SESSION['auth_status'] = "Subject enrolled successfully!";
                        } else {
                            $_SESSION['auth_status'] = "Failed to update total credits!";
                        }
                    } else {
                        $_SESSION['auth_status'] = "Failed to enroll subject!";
                    }
                }
            } else {
                $_SESSION['auth_status'] = "Subject not found!";
            }
        }
    } else {
        $_SESSION['auth_status'] = "Student not found!";
    }
    header('Location: Subjects_enroll.php');
    exit();
}

// Check if the form is submitted to remove all subjects
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_all_subjects_btn'])) {
    $student_id = $_POST['student_id_remove'];
    
    if (removeAllSubjects($student_id, $conn)) {
        $_SESSION['auth_status'] = "All subjects removed successfully!";
    } else {
        $_SESSION['auth_status'] = "Failed to remove all subjects!";
    }
    header('Location: Subjects_enroll.php');
    exit();
}

ob_end_flush(); // Flush (send) the output buffer and turn off output buffering
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Enroll Subjects</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../TheAdmin/Dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active">Enroll Subjects</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <?php include ('../includes/message.php'); ?>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Add subject form -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="student_id">Select Student:</label>
                            <select class="form-control" id="student_id" name="student_id" required>
                                <?php
                                // Fetch all students
                                $query = "SELECT student_id, full_name FROM students";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['student_id'] . "'>" . $row['full_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subject_id">Select Subject:</label>
                            <select class="form-control" id="subject_id" name="subject_id" required>
                                <?php
                                // Fetch all subjects
                                $query = "SELECT subject_id, title FROM subjects";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['subject_id'] . "'>" . $row['title'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" name="add_subject_btn">Enroll Subject</button>
                    </form>

                    <!-- Remove all subjects form -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="mt-4">
                        <div class="form-group">
                            <label for="student_id_remove">Select Student to Remove All Subjects:</label>
                            <select class="form-control" id="student_id_remove" name="student_id_remove" required>
                                <?php
                                // Fetch all students
                                $query = "SELECT student_id, full_name FROM students";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['student_id'] . "'>" . $row['full_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-danger" name="remove_all_subjects_btn">Remove All Subjects</button>
                    </form>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include('../includes/footer.php'); ?>

<?php include("../includes/script.php"); ?>

</div>
<!-- ./wrapper -->
</body>
</html>
