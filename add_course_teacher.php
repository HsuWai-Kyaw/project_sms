<?php
require "server/db.php";
require "component/header.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['submit'])) {
        $course_id = $_POST['course_id'];
        $teacher_id = $_POST['teacher_id'];

        $add_course_teacher_query = "INSERT INTO `course_teacher`(`course_id`, `teacher_id`) VALUES (:course_id, :teacher_id)";
        $add_course_teacher_statement = $pdo->prepare($add_course_teacher_query);

        $add_course_teacher_statement->bindParam(":course_id", $course_id, PDO::PARAM_STR);
        $add_course_teacher_statement->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);

        $res = $add_course_teacher_statement->execute();

        if ($res) {
            header("location:course_teacher_index.php");
            exit();
        } else {
            $errors[] = "Error occurred while storing data";
        }
    }
}
?>

<form action="add_course_teacher.php" method="POST" enctype="multipart/form-data">
    <div class="container shadow shadow">
        <label for="course_id">Course </label>
        <select name="course_id">
            <?php
            $course_query = "SELECT course_id, course_name FROM courses";
            $course_stmt = $pdo->query($course_query);
            $courses = $course_stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($courses as $course) {
                echo '<option value="' . $course['course_id'] . '">' . $course['course_name'] . '</option>';
            }
            ?>
        </select><br>

        <label for="teacher_id">Teacher </label>
        <select name="teacher_id">
            <?php
            $teacher_query = "SELECT teacher_id, firstname, lastname FROM teacher";
            $teacher_stmt = $pdo->query($teacher_query);
            $teachers = $teacher_stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($teachers as $teacher) {
                echo '<option value="' . $teacher['teacher_id'] . '">' . $teacher['firstname'] . ' ' . $teacher['lastname'] . '</option>';
            }
            ?>
        </select><br>

        <input type="submit" value="Submit" name="submit" class="btn btn-info">
    </div>
</form>

<script src="./js/ajax.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
