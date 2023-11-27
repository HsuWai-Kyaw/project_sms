<?php
require "server/db.php";
require "component/header.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
     if (isset($_POST['submit'])) {
          $subject_id = $_POST['subject_id'];
          $subject_name = $_POST['subject_name'];
          $description = $_POST['description'];
          $start_time = $_POST['start_time'];
          $end_time = $_POST['end_time'];
          $course_id = $_POST['course_id'];

          $addsubject = "INSERT INTO `subject`(`subject_id`, `subject_name`, `description`, `start_time`, `end_time`, `course_id`) VALUES (:subject_id,:subject_name,:description,:start_time,:end_time,:course_id)";

          $addsubjectstatement = $pdo->prepare($addsubject);
          $addsubjectstatement->bindParam(":subject_id", $subject_id, PDO::PARAM_STR);
          $addsubjectstatement->bindParam(":subject_name", $subject_name, PDO::PARAM_STR);
          $addsubjectstatement->bindParam(":description", $description, PDO::PARAM_STR);
          $addsubjectstatement->bindParam(":start_time", $start_time, PDO::PARAM_STR);
          $addsubjectstatement->bindParam(":end_time", $end_time, PDO::PARAM_STR);
          $addsubjectstatement->bindParam(":course_id", $course_id, PDO::PARAM_STR);
          
          $res = $addsubjectstatement->execute();

          if ($res) {
               // echo "Data Stored";
               header("location:subject_index.php");
          } else {
               $errors[] = "Error occurred while storing data";
          }
     }
}
?>

<form action="add_subject.php" method="POST" enctype="multipart/form-data">
     <div subject="container shadow shadow">
        <label for="subject_id">Subject ID</label>
        <input type="text" name="subject_id" required><br>
          <label for="subject_name">Title</label>
          <input type="text" name="subject_name" required><br>
          <label for="description">Description</label>
          <input type="text" name="description" required><br>
          <label for="start_time">Start Time</label>
          <input type="time" name="start_time" required><br>
          <label for="end_time">End Time</label>
          <input type="time" name="end_time" required><br>
          <label for="course_id">Course </label>
        <select name="course_id">
        <?php
$course_query = "SELECT course_id, course_name FROM courses";
$course_stmt = $pdo->query($course_query);
$course = $course_stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($course as $key => $value) {
    echo '<option value="' . $value['course_id'] . '">' . $value['course_name'] . '</option>';
}

?>
        </select><br>
         
          <input type="submit" value="Submit" name="submit" class="btn btn-info">

     </div>
</form>

<script src="./js/ajax.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
