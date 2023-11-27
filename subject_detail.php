<?php
require "server/db.php";
require "component/header.php";
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

?>

<form action="subject_detail.php" method="POST" enctype="multipart/form-data">
    <div class="container shadow shadow">
        <?php
        foreach ($result as $key => $value) { 
        ?>
            <label for="subject_id">Subject ID</label>
            <input type="text" name="subject_id" value="<?= $value['subject_id'] ?>"><br>
            <label for="subject_name">Subject Name</label>
            <input type="text" name="subject_name" value="<?= $value['subject_name'] ?>"><br>
            <label for="description">Description</label>
            <input type="text" name="description" value="<?= $value['description'] ?>"><br>
            <label for="start_time">Start Time</label>
            <input type="time" name="start_time" value="<?= $value['start_time'] ?>"><br>
            <label for="end_time">End Time</label>
            <input type="time" name="end_time" value="<?= $value['end_time'] ?>"><br>
            <label for="course_id">Course</label>
            
            <select name="course_id">
                <?php
                $course_query = "SELECT course_id, course_name FROM courses";
                $course_stmt = $pdo->query($course_query);
                $courses = $course_stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($courses as $course) {
                    $selected = ($value['course_id'] == $course['course_id']) ? 'selected' : '';
                    echo '<option value="' . $course['course_id'] . '" ' . $selected . '>' . $course['course_name'] . '</option>';
                }
                ?>
            </select><br>
        <?php } ?>
    </div>
</form>

<button class="btn btn-light" name="back"><a href="subject_index.php">Back</a></button>

<?php
require "component/footer.php";
?>
