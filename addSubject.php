<?php
session_start();
include('config/db_conn.php');
include('includes/header.php');
include('includes/topbar.php');
include('authentication.php');


// Check if student_id is set in the URL
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Fetch student details from student_list table
    $query = "SELECT * FROM student_list WHERE student_id = $student_id";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        // Fetch the student details
        $student = mysqli_fetch_assoc($result);
    } else {
        // Handle query error
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Redirect if student_id is not set
    header('Location: enrollSubject.php');
    exit();
}

// Define hardcoded list of subjects
$subjects = array(
    "Intro. to Biology",
    "Genetics",
    "Computational Biology",
    "Intro. to Computer Science",
    "Game Design",
    "Robotics",
    "Image Processing",
    "Database System Concepts",
    "Intro. to Digital Systems",
    "Investment Banking",
    "World History",
    "Music Video Production",
    "Physical Principles"
);

// Include sidebar and footer after processing the form
include("includes/sidebar.php");
include("includes/footer.php");
?>

<!-- HTML form to add subjects -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Subjects for <?php echo isset($student) ? $student['full_name'] : ''; ?></h1>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Select Subjects</h3>
                        </div>
                        <div class="card-body">
                            <form action="enrollcode.php" method="post">
                                <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                                <div class="form-group">
                                    <label>Select Subjects:</label><br>
                                    <?php
                                    foreach ($subjects as $subject) {
                                        echo '<div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="subjects[]" id="' . strtolower(str_replace(' ', '_', $subject)) . '" value="' . $subject . '">
                                            <label class="form-check-label" for="' . strtolower(str_replace(' ', '_', $subject)) . '">
                                                ' . $subject . '
                                            </label>
                                        </div>';
                                    }
                                    ?>
                                </div>
                                <button type="submit" name="addSubject" class="btn btn-primary">Add Subjects</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include("includes/script.php");
?>
