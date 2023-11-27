<?php
require "server/db.php";
$errors = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$sql = "SELECT * FROM courses WHERE course_id = :course_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":course_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit'])) {
        $course_id = $_POST['course_id'];
        $course_name = $_POST['course_name'];
        $description = $_POST['description'];
        $department_id = $_POST['department_id'];
        $start_date = $_POST['start_date'];

        $editcourse = "UPDATE `courses` SET `course_name`=:course_name, `description`=:description, `department_id`=:department_id, `start_date`=:start_date WHERE `course_id`=:course_id";

        $editcoursestatement = $pdo->prepare($editcourse);
        $editcoursestatement->bindParam(":course_id", $course_id, PDO::PARAM_STR);
        $editcoursestatement->bindParam(":course_name", $course_name, PDO::PARAM_STR);
        $editcoursestatement->bindParam(":description", $description, PDO::PARAM_STR);
        $editcoursestatement->bindParam(":department_id", $department_id, PDO::PARAM_STR);
        $editcoursestatement->bindParam(":start_date", $start_date, PDO::PARAM_STR);
        $res = $editcoursestatement->execute();

        if ($res) {
            // header("Location: course_index.php?id=" . $course_id);
            header("Location: course_index.php");
            exit();
        }
    }
}
?>


<form action="edit_course.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow table-primary">
     <?php
foreach ($result as $key => $value) {
?>
    <label for="course_id">Course ID</label>
    <input type="text" name="course_id" value="<?= $value['course_id'] ?? "" ?>"> <br>
    <label for="course_name">course_name</label>
    <input type="text" name="course_name" value="<?= $value['course_name'] ?? "" ?>"><br>
    <label for="description">Description</label>
    <input type="text" name="description" value="<?= $value['description'] ?? "" ?>"><br>
    <label for="department_id">Department</label>
<select name="department_id">
    <?php
    $selected_department_id = $value['department_id'];

    $department_query = "SELECT department_id, department FROM department";
    $department_stmt = $pdo->query($department_query);
    $departments = $department_stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($departments as $dept) {
        $selected = ($dept['department_id'] == $selected_department_id) ? 'selected' : '';
        echo '<option value="' . $dept['department_id'] . '" ' . $selected . '>' . $dept['department'] . '</option>';
    }
    ?>
</select><br>

        <label for="start_date">Start Date</label>
<input type="date" name="start_date" value="<?= $value['start_date'] ?? '' ?>"><br>


    <input type="submit" value="Edit" name="edit" class="btn btn-info">
<?php } ?>

     </div>
</form>
