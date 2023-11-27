<?php
require "server/db.php";
$errors = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$sql = "SELECT s.*, c.course_name FROM subject s
        LEFT JOIN courses c ON s.course_id = c.course_id
        WHERE s.subject_id = :subject_id";

$statement = $pdo->prepare($sql);
$statement->bindParam(":subject_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit'])) {
        $subject_id = $_POST['subject_id'];
        $subject_name = $_POST['subject_name'];
        $description = $_POST['description'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $course_id = $_POST['course_id'];
       
        $editsubject = "UPDATE `subject` SET `subject_name`=:subject_name,`description`=:description,`start_time`=:start_time,`end_time`=:end_time,`course_id`=:course_id WHERE `subject_id`=:subject_id";

        $editsubjectstatement = $pdo->prepare($editsubject);
        $editsubjectstatement->bindParam(":subject_id", $subject_id, PDO::PARAM_STR);
        $editsubjectstatement->bindParam(":subject_name", $subject_name, PDO::PARAM_STR);
        $editsubjectstatement->bindParam(":description", $description, PDO::PARAM_STR);
        $editsubjectstatement->bindParam(":start_time", $start_time, PDO::PARAM_STR);
        $editsubjectstatement->bindParam(":end_time", $end_time, PDO::PARAM_STR);
        $editsubjectstatement->bindParam(":course_id", $course_id, PDO::PARAM_STR);
        $res = $editsubjectstatement->execute();

        if ($res) {
            // header("Location: course_index.php?id=" . $course_id);
            header("Location: subject_index.php");
            exit();
        }
    }
}
?>


<form action="edit_subject.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow table-primary">
     <?php
foreach ($result as $key => $value) {
?>
    <label for="subject_id">Subject ID</label>
    <input type="text" name="subject_id" value="<?= $value['subject_id']?>" readonly><br>
    <label for="subject_name">Subject Name</label>
    <input type="text" name="subject_name" value="<?= $value['subject_name']?>"><br>
    <label for="description">Description</label>
    <input type="text" name="description" value="<?= $value['description']?>"><br>
    <label for="start_time">Start Time</label>
    <input type="time" name="start_time" value="<?= $value['start_time']?>"><br>
    <label for="end_time">End Time</label>
    <input type="time" name="end_time" value="<?= $value['end_time']?>"><br>
    <label for="course_id">Course</label>
    
    <select name="course_id">
        <?php
        $course_query = "SELECT course_id, course_name FROM courses";
        $course_stmt = $pdo->query($course_query);
        $courses = $course_stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($courses as $course) {
            $selected = ($value['course_id'] == $course['course_id'])?'selected' : '';
            echo '<option value="'. $course['course_id']. '" '. $selected. '>'. $course['course_name']. '</
            option>';
        }
        
   ?>
</select><br>

    <input type="submit" value="Edit" name="edit" class="btn btn-info">
<?php } ?>

     </div>
</form>
