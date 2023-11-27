<?php
session_start();
require "server/db.php";
require "component/header.php";
$errors = [];

if (!isset($_SESSION['student_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve the logged-in student's username
$student_id = $_SESSION['student_id'];
$sql = "SELECT * FROM student WHERE student_id = :student_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":student_id", $student_id, PDO::PARAM_STR);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Check if the user exists
if (!$user) {
    // Redirect to the login page if the user doesn't exist
    header("Location: login.php");
    exit();
}

$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : null;

if (!$course_id) {
    // Redirect or handle the case where course_id is not provided
    // For example, you can redirect back to the course selection page
    header("Location: course_index.php");
    exit();
}

// Now you can use $course_id to fetch details of the specific course
// Perform your database query or other logic here
$sql = "SELECT * FROM courses WHERE course_id = :course_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":course_id", $course_id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

// Check if the query was successful and $result is not null
if ($result === false) {
    echo "Error fetching course details.";
} else {
?>
    <form action="course_detail.php" method="POST" enctype="multipart/form-data">
        <div class="container shadow shadow">
            <?php
            foreach ($result as $key => $value) {
            ?>
                <label for="course_id">Course ID</label>
                <input type="text" name="course_id" value="<?= $value['course_id'] ?>"> <br>
                <label for="course_name">Course Name</label>
                <input type="text" name="course_name" value="<?= $value['course_name'] ?>"><br>
                <label for="description">Description</label>
                <input type="text" name="description" value="<?= $value['description'] ?>"><br>

                <?php
                $department_id = $value['department_id'];
                $departmentQuery = "SELECT department FROM department WHERE department_id = :department_id";
                $departmentStatement = $pdo->prepare($departmentQuery);
                $departmentStatement->bindParam(":department_id", $department_id, PDO::PARAM_STR);
                $departmentStatement->execute();
                $dep_res = $departmentStatement->fetch(PDO::FETCH_ASSOC);
                ?>
                <label for="department_id">Department</label>
                <input type="text" name="department_id" value="<?= $dep_res['department'] ?? '' ?>" readonly><br>

                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" value="<?= $value['start_date'] ?? '' ?>"><br>

            <?php } ?>
            <a href="course_index.php" class="btn btn-primary">Back</a>
            <a href="enrollment.php?student_id=<?= $user['student_id'] ?>&course_id=<?= $course_id ?>" class="btn btn-dark">Enroll</a>
        </div>
    </form>

<?php
}

require "component/footer.php";
?>
