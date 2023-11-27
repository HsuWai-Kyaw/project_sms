<?php
require "server/db.php";
require "component/header.php";
$errors = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$sql = "SELECT * FROM schedule WHERE schedule_id = :schedule_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":schedule_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);
// print_r($result);
?>

<form action="schedule_detail.php" method="POST" enctype="multipart/form-data">
    <div class="container shadow shadow">
        <label for="schedule_id">Schedule ID</label>
        <input type="text" name="schedule_id" value="<?= $result['schedule_id'] ?>" readonly><br>

        <label for="course_name">Course</label>
<?php  
    $courseSql = "SELECT c.course_name
    FROM course_teacher ct
    JOIN courses c ON ct.course_id = c.course_id
    WHERE ct.course_teacher_id = :course_teacher_id";
    $courseStatement = $pdo->prepare($courseSql);
    $courseStatement->bindParam(":course_teacher_id", $result['course_teacher_id'], PDO::PARAM_STR);
    $courseStatement->execute();
    $courseResult = $courseStatement->fetch(PDO::FETCH_ASSOC);
?>

<input type="text" name="course_name" value="<?= $courseResult['course_name'] ?>" readonly><br>

<br>

    <label for="lecturer">Lecturer</label>
    <?php  
    $teacherSql = "SELECT t.firstname,t.lastname
    FROM course_teacher ct
    JOIN teacher t ON ct.teacher_id = t.teacher_id
    WHERE ct.course_teacher_id = :course_teacher_id";
    $teacherStatement = $pdo->prepare($teacherSql);
    $teacherStatement->bindParam(":course_teacher_id", $result['course_teacher_id'], PDO::PARAM_STR);
    $teacherStatement->execute();
    $teacherResult = $teacherStatement->fetch(PDO::FETCH_ASSOC);
?>

<input type="text" name="lecturer" value="<?= $teacherResult['firstname'].''. $teacherResult['lastname']?>" readonly><br>
       
        <label for="room_id">Room ID</label>
        <select name="room_id" id="room_id">
            <?php
            $room_sql = "SELECT * FROM room";
            $room_result = $pdo->prepare($room_sql);
            $room_result->execute();
            $rooms = $room_result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rooms as $room) {
                echo '<option value="' . $room['room_id'] . '" ' . ($room['room_id'] == $result['room_id'] ? 'selected' : '') . '>' . $room['room_number'] . '</option>';
            }
            ?>
        </select><br>

        <label for="day_of_week">Days</label>
        <input type="text" name="day_of_week" value="<?= $result['day_of_week'] ?>" required><br>

        <label for="start_time">Start time</label>
        <input type="time" name="start_time" value="<?= $result['start_time'] ?>" required><br>

        <label for="end_time">End time</label>
        <input type="time" name="end_time" value="<?= $result['end_time'] ?>" required><br>

        <label for="notes">Notes</label>
        <input type="text" name="notes" value="<?= $result['notes'] ?>" required><br>
    </div>
</form>

<button class="btn btn-light" name="back"><a href="schedule_index.php">Back</a></button>

<?php
require "component/footer.php";
?>
