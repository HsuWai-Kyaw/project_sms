<?php
session_start();
require "server/db.php"; // Adjust the path as needed

// Check if the user is logged in
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

// Retrieve course_id from URL parameters
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : null;

// Function to generate a unique enrollment_id
function generateEnrollmentId() {
    // Prefix for the enrollment ID
    $prefix = 'En';

    // Generate a random number (you can customize the range)
    $randomNumber = mt_rand(100000000, 999999999);

    // Concatenate the prefix, a colon, and the random number
    $enrollmentId = $prefix . ':' . str_pad($randomNumber, 9, '0', STR_PAD_LEFT);

    return $enrollmentId;
}

$enrollment_id = generateEnrollmentId();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data when the form is submitted
    $enrollment_id = $_POST["enrollment_id"];
    $student_id = $_POST["student_id"];
    $course_id = $_POST["course_id"];
    $enrollment_date = $_POST["enrollment_date"];
    $status = $_POST["status"];

        // Prepare and execute the INSERT query
        $stmt = $pdo->prepare("INSERT INTO enrollment (enrollment_id, student_id, course_id, enrollment_date, status) VALUES (:enrollment_id, :student_id, :course_id, :enrollment_date, :status)");
        $stmt->bindParam(':enrollment_id', $enrollment_id, PDO::PARAM_STR);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_STR);
        $stmt->bindParam(':enrollment_date', $enrollment_date, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);

        $stmt->execute();

        // Redirect to the enrollment index page after successful enrollment
        header("Location: enrollment_index.php");
        exit();
   
}
?>

<!-- Your HTML form for enrollment -->
<form action="enrollment.php" method="POST">
    <label for="enrollment_id">Enrollment Number</label>
    <input type="text" name="enrollment_id" value="<?= $enrollment_id; ?>" readonly><br>

    <label for="student_id">Student Name</label>
    <input type="hidden" name="student_id" value="<?= $student_id; ?>">
    <input type="text" name="username" value="<?= $user["username"]; ?>"> <br>

    <label for="course_id">Course</label>
    <?php
    $sql = "SELECT * FROM courses WHERE course_id = :course_id";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(":course_id", $course_id, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $key => $value) {
    ?>
        <input type="hidden" name="course_id" value="<?= $course_id; ?>">
        <input type="text" name="course_name" value="<?= $value["course_name"];?>"> <br>
    <?php }?>
    
    <label for="enrollment_date">Enrollment Date</label>
    <input type="text" name="enrollment_date" value="<?= date('Y-m-d H:i:s') ?>"><br>

    <label for="status">Status</label>
    <input type="text" name="status" value="pending" readonly><br>

    <input type="submit" name="submit" value="Submit">
    <a href="enrollment_index.php" class="btn btn-primary">Back</a>
    <a href="logout.php" class="btn btn-dark">Logout</a>
</form>

<?php
require "component/footer.php";
?>
