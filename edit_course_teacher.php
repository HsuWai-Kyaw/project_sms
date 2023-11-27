<?php
require "server/db.php";
require "component/header.php";
$errors = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];

}

$sql = "SELECT * FROM course_teacher WHERE course_teacher_id = :course_teacher_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":course_teacher_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit'])) {
        $course_teacher_id = $_POST['course_teacher_id'];
        $course_id = $_POST['course_id'];
        $teacher_id = $_POST['teacher_id'];
       
        $edit_course_teacher = "UPDATE `course_teacher` SET `course_id`=:course_id, `teacher_id`=:teacher_id WHERE `course_teacher_id`=:id";

        $edit_course_teacherstatement = $pdo->prepare($edit_course_teacher);
        $edit_course_teacherstatement->bindParam(":id", $course_teacher_id, PDO::PARAM_STR);
        $edit_course_teacherstatement->bindParam(":course_id", $course_id, PDO::PARAM_STR);
        $edit_course_teacherstatement->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
        $res = $edit_course_teacherstatement->execute();

        if ($res) {
            // header("Location: class_index.php?id=" . $class_id);
            header("Location: course_teacher_index.php");
            exit();
        }
    }
}

// Fetch all courses for the select options
$course_query = "SELECT course_id, course_name FROM courses";
$course_statement = $pdo->query($course_query);
$courses = $course_statement->fetchAll(PDO::FETCH_ASSOC);

$teacher_query = "SELECT teacher_id, firstname, lastname FROM teacher";
$teacher_stmt = $pdo->query($teacher_query);
$teachers = $teacher_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<form action="edit_course_teacher.php" method="POST" enctype="multipart/form-data">
    <div class="container shadow shadow">

    <?php
foreach ($result as $key => $value) {
?>
    <label for="course_teacher_id">course_teacher_id</label>
    <input type="text" name="course_teacher_id" value="<?= $value['course_teacher_id']?? ""?>"><br>
    <label for="course_id">course_id</label>
    <select name="course_id">
        <?php
        $selected_course_id = $value['course_id'];
        foreach ($courses as $course) {
            $course_name = $course['course_name'];
            $selected = ($course['course_id'] == $selected_course_id)?'selected' : '';
            echo '<option value="'. $course['course_id']. '" '. $selected. '>'. $course_name. '</
            option>';
        }
        ?>
        </select><br>
    <label for="teacher_id">teacher_id</label>
    <select name="teacher_id">
        <?php
        $selected_teacher_id = $value['teacher_id'];
        foreach ($teachers as $teacher) {
            $teacher_fullname = $teacher['firstname'].''. $teacher['lastname'];
            $selected = ($teacher['teacher_id'] == $selected_teacher_id)?'selected' : '';
            echo '<option value="'. $teacher['teacher_id']. '" '. $selected. '>'. $teacher_fullname. '</
            option>';
        }
        ?>
        </select><br>

    <input type="submit" value="Edit" name="edit" class="btn btn-info">
<?php } ?>

     </div>
</form>


<?php
require "component/footer.php";
?>
