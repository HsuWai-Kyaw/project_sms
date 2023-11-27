<?php
session_start();
require "server/db.php";
$errors = [];

// Retrieve course_id from URL parameters
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : null;

// Fetch the list of students enrolled in the selected course
$studentsQuery = "SELECT
    s.student_id,
    s.username,
    a.attendance_date,
    a.status,
    a.total_percentage,
    c.course_name
FROM
    student s
JOIN
    attendance a ON s.student_id = a.student_id
JOIN
    course_teacher ct ON a.course_teacher_id = ct.course_teacher_id
JOIN
    teacher t ON ct.teacher_id = t.teacher_id
JOIN
    courses c ON ct.course_id = c.course_id
WHERE
    t.teacher_id = :teacher_id
    AND c.course_id = :course_id;
";

$studentsStatement = $pdo->prepare($studentsQuery);
$studentsStatement->bindParam(":course_id", $course_id, PDO::PARAM_STR);
// Assuming you have a teacher_id in your session or some other way to identify the teacher
$teacher_id = "T-001317600"; // Replace with the actual way to get teacher_id
$studentsStatement->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
$studentsStatement->execute();
$students = $studentsStatement->fetchAll(PDO::FETCH_ASSOC);

// Process attendance form submission
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {
    $attendance_date = date('Y-m-d H:i:s');

    foreach ($students as $student) {
        $student_id = $student['student_id'];
        $status = $_POST['status'][$student_id];
        $total_percentage = $_POST['total_percentage'][$student_id];

        $addAttendanceQuery = "INSERT INTO `attendance`(`student_id`, `course_teacher_id`, `attendance_date`, `status`, `total_percentage`)
                               VALUES (:student_id, :course_teacher_id, :attendance_date, :status, :total_percentage)";

        $addAttendanceStatement = $pdo->prepare($addAttendanceQuery);

        $addAttendanceStatement->bindParam(":student_id", $student_id, PDO::PARAM_STR);
        $addAttendanceStatement->bindParam(":course_teacher_id", $student['course_teacher_id'], PDO::PARAM_STR);
        $addAttendanceStatement->bindParam(":attendance_date", $attendance_date, PDO::PARAM_STR);
        $addAttendanceStatement->bindParam(":status", $status, PDO::PARAM_STR);
        $addAttendanceStatement->bindParam(":total_percentage", $total_percentage, PDO::PARAM_STR);

        $res = $addAttendanceStatement->execute();

        if (!$res) {
            $errors[] = "Error occurred while storing data for student with ID: $student_id";
        }
    }

    if (empty($errors)) {
        header("location: index.php");
    }
}
?>

<!-- Your HTML form for attendance -->
<form action="view_attendance.php?course_id=<?= $course_id ?>" method="POST">
    <h2>Student List for Course ID: <?= $course_id ?></h2>

    <table border="1">
        <tr>
            <th>No</th>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Course Name</th>
            <th>Date</th>
            <th>Status</th>
            <th>Percentage</th>
        </tr>

        <?php $key = 0; ?>
        <?php foreach ($students as $student) : ?>
            <tr>
                <td><?= ++$key ?></td>
                <td><?= $student['student_id'] ?></td>
                <td><?= $student['username'] ?></td>
                <td><?= $student['course_name'] ?></td>
                <td><?= $student['attendance_date'] ?></td>
                <td>
                    <label><input type="radio" name="status[<?= $student['student_id'] ?>]" value="present" checked> Present</label>
                    <label><input type="radio" name="status[<?= $student['student_id'] ?>]" value="absent"> Absent</label>
                </td>
                <td>
                    <input type="number" name="total_percentage[<?= $student['student_id'] ?>]" value="<?= isset($_POST['total_percentage'][$student['student_id']]) ? $_POST['total_percentage'][$student['student_id']] : '' ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <input type="submit" name="submit" value="Submit">
    <a href="enrollment_index.php" class="btn btn-primary">Back</a>
    <a href="logout.php" class="btn btn-dark">Logout</a>
</form>

<?php require "component/footer.php"; ?>
