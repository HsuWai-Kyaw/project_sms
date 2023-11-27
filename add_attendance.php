<?php
require "server/db.php";
require "component/header.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
     if (isset($_POST['submit'])) {
          $student_id = $_POST['student_id'];
          $course_taecher_id = $_POST['course_taecher_id'];
          $attendance_date = $_POST['attendance_date'];
          $status = $_POST['status'];
          $total_percentage = $_POST['total_percentage'];

          $add_attendance = "INSERT INTO `attendance`(`student_id`, `course_taecher_id`, `attendance_date`, `status`, `total_percentage`) VALUES (:student_id, :course_taecher_id, :attendance_date, :status, :total_percentage)";

          $add_attendancestatement = $pdo->prepare($add_attendance);

          $add_attendancestatement->bindParam(":student_id", $student_id, PDO::PARAM_STR);
          $add_attendancestatement->bindParam(":course_taecher_id", $course_taecher_id, PDO::PARAM_STR);
          $add_attendancestatement->bindParam(":attendance_date", $attendance_date, PDO::PARAM_STR);
          $add_attendancestatement->bindParam(":status", $status, PDO::PARAM_STR);
          $add_attendancestatement->bindParam(":total_percentage", $total_percentage, PDO::PARAM_STR);
          
          $res = $add_attendancestatement->execute();

          if ($res) {
               // echo "Data Stored";
               header("location:view_attendance.php");
          } else {
               $errors[] = "Error occurred while storing data";
          }
     }
}
?>

<form action="add_attendance.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow">
          <label for="student_id">First Name</label>
          <?php 
          $student = "SELECT * FROM student";
          $student_stmt = $pdo->query($student);
          $students = $student_stmt->fetchAll(PDO::FETCH_ASSOC);
          ?>
          <input type="text" name="studnet_id" value="">
          <input type="text" name="class_name" required><br>
          <label for="teacher_id">Class Teacher</label>
        <select name="teacher_id">
        <?php

// Fetch the list of teachers from the database
$teacher_query = "SELECT teacher_id, firstname, lastname FROM teacher";
$teacher_stmt = $pdo->query($teacher_query);
$teacher = $teacher_stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($teacher as $key => $value) {
    echo '<option value="' . $value['teacher_id'] . '">' . $value['firstname'] . ' ' . $value['lastname'] . '</option>';
}

?>
        </select><br>
         
          <input type="submit" value="Submit" name="submit" class="btn btn-info">

     </div>
</form>

<script src="./js/ajax.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
