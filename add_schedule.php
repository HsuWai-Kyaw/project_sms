<?php
require "server/db.php";
require "component/header.php";

$errors = [];
// $selectedCourseTeacherId = ''; // Initialize the variable

// Retrieve values from the URL parameters
$course_teacher_id = $_GET['course_teacher_id'] ?? '';
$course_id = $_GET['course_id'] ?? '';
$teacher_id = $_GET['teacher_id'] ?? '';

// Initialize $result
$result = [];

// Fetch course details based on course_teacher_id
if (!empty($course_teacher_id)) {
     $sql = "SELECT ct.*, c.course_name, t.firstname, t.lastname
             FROM course_teacher ct
             JOIN courses c ON ct.course_id = c.course_id
             JOIN teacher t ON ct.teacher_id = t.teacher_id
             WHERE ct.course_teacher_id = :course_teacher_id";
 
     $statement = $pdo->prepare($sql);
     $statement->bindParam(":course_teacher_id", $course_teacher_id, PDO::PARAM_STR);
     $statement->execute();
     $result = $statement->fetch(PDO::FETCH_ASSOC);
 }

if ($_SERVER['REQUEST_METHOD'] == "POST") {
     if (isset($_POST['submit'])) {
          // $id = $_POST['schedule_id'];
          $schedule_id = $_POST['schedule_id'];
          $course_teacher_id = $_POST['course_teacher_id'];
          $room_id = $_POST['room_id'];
          $day_of_week= $_POST['day_of_weeks'] ?? [];
          $start_time = $_POST['start_time'];
          $end_time = $_POST['end_time'];
          $notes = $_POST['notes'];
          $day_of_week = implode(', ', $day_of_week);

          $addschedule = "INSERT INTO `schedule`(`schedule_id`, `course_teacher_id`, `room_id`, `day_of_week`, `start_time`, `end_time`, `notes`) VALUES (:schedule_id,:course_teacher_id,:room_id,:day_of_week,:start_time,:end_time,:notes)";

          $addschedulestatement = $pdo->prepare($addschedule);

          // $addschedulestatement->bindParam(":schedule_id", $schedule_id, PDO::PARAM_STR);
          $addschedulestatement->bindParam(":schedule_id", $schedule_id, PDO::PARAM_STR);
          $addschedulestatement->bindParam(":course_teacher_id", $course_teacher_id, PDO::PARAM_STR);
          $addschedulestatement->bindParam(":room_id", $room_id, PDO::PARAM_STR);
          $addschedulestatement->bindParam(":day_of_week", $day_of_week, PDO::PARAM_STR);
          $addschedulestatement->bindParam(":start_time", $start_time, PDO::PARAM_STR);
          $addschedulestatement->bindParam(":end_time", $end_time, PDO::PARAM_STR);
          $addschedulestatement->bindParam(":notes", $notes, PDO::PARAM_STR);
          
          $res = $addschedulestatement->execute();

          if ($res) {
               // echo "Data Stored";
               header("location:schedule_index.php");
          } else {
               $errors[] = "Error occurred while storing data";
          }
     }
  
}
?>

<form action="add_schedule.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow">
          <label for="schedule_id">Schedule Number</label>
          <input type="text" name="schedule_id" required><br>

          <label for="course_teacher_id">Course</label>
        <input type="hidden" name="course_teacher_id" value="<?= $course_teacher_id ?>">
        <input type="text" name="course_name" value="<?= isset($result['course_id']) ? $result['course_name'] : '' ?>" readonly><br>
        <label for="course_teacher_id">Lecturer</label>
        <input type="text" name="teacher_name" value="<?= isset($result['firstname']) && isset($result['lastname']) ? $result['firstname'] . ' ' . $result['lastname'] : '' ?>" readonly><br>
        
        
      <label for="room_id">Room</label>
          <select name="room_id" id="room_id">

               <?php 
               $room_sql = "SELECT * FROM room";
               $room_result = $pdo->prepare($room_sql);
               $room_result->execute();
               $rooms = $room_result->fetchAll(PDO::FETCH_ASSOC);
               foreach ($rooms as $room) {?>
                    <option value="<?= $room['room_id']?>">
                        <?= $room['room_number']?>
                    </option>
               <?php }?>
          </select><br>

          <label for="day_of_weeks">Day of Week</label><br>
          <input type="checkbox" name="day_of_weeks[]" value="Monday">Monday<br>
          <input type="checkbox" name="day_of_weeks[]" value="Tuesday">Tuesday<br>
          <input type="checkbox" name="day_of_weeks[]" value="Wednesday">Wednesday<br>
          <input type="checkbox" name="day_of_weeks[]" value="Thursday">Thursday<br>
          <input type="checkbox" name="day_of_weeks[]" value="Friday">Friday<br>
          <input type="checkbox" name="day_of_weeks[]" value="Saturday">Saturday<br>
          <input type="checkbox" name="day_of_weeks[]" value="Sunday">Sunday<br>

          <label for="start_time">Start Time</label>
          <input type="time" name="start_time" required><br>
          <label for="end_time">End Time</label>
          <input type="time" name="end_time" required><br>
          <label for="notes">Notes</label>
          <input type="text" name="notes" required><br>

          <input type="submit" value="Submit" name="submit" class="btn btn-info">
          <a href="schedule_index.php" class="btn btn-secondary">Back</a>

     </div>
</form>

<script>
    function updateCourseDetails() {
        // Get the selected option value
        var selectedCourseTeacherId = document.getElementById("course_teacher_id").value;

        // Find the corresponding course details and update the form fields
        var courseDetails = <?php echo json_encode($results); ?>;
        for (var i = 0; i < courseDetails.length; i++) {
            if (courseDetails[i].course_teacher_id == selectedCourseTeacherId) {
                document.getElementById("course_name").value = courseDetails[i].course_name;
                document.getElementById("teacher_name").value = courseDetails[i].firstname + ' ' + courseDetails[i].lastname;
                break;
            }
        }
    }
</script>


<script src="./js/ajax.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
