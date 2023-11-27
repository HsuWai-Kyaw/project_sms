<?php
require "server/db.php";
require "component/header.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
     if (isset($_POST['submit'])) {
          $course_id = $_POST['course_id'];
          $course_name = $_POST['course_name'];
          $description = $_POST['description'];
          $department_id = $_POST['department_id'];
          $start_date = $_POST['start_date'];

          $addcourse = "INSERT INTO `courses`(`course_id`, `course_name`, `description`, `department_id`, `start_date`) VALUES (:course_id,:course_name,:description,:department_id,:start_date)";

          $addcoursestatement = $pdo->prepare($addcourse);

          $addcoursestatement->bindParam(":course_id", $course_id, PDO::PARAM_STR);
          $addcoursestatement->bindParam(":course_name", $course_name, PDO::PARAM_STR);
          $addcoursestatement->bindParam(":description", $description, PDO::PARAM_STR);
          $addcoursestatement->bindParam(":department_id", $department_id, PDO::PARAM_STR);
          $addcoursestatement->bindParam(":start_date", $start_date, PDO::PARAM_STR);
          
          $res = $addcoursestatement->execute();

          if ($res) {
               // echo "Data Stored";
               header("location:course_index.php");
          } else {
               $errors[] = "Error occurred while storing data";
          }
     }
}
?>

<form action="add_course.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow">
          <label for="course_id">Course ID</label>
          <input type="text" name="course_id" required><br>
          <label for="course_name">Course</label>
          <input type="text" name="course_name" required><br>
          <label for="description">Description</label>
          <input type="text" name="description" required><br>
          <label for="department_id">Select Department</label>
        <select name="department_id">
        <?php

// Fetch the list of departments from the database
$department_query = "SELECT department_id, department FROM department";
$department_stmt = $pdo->query($department_query);
$dept = $department_stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($dept as $key => $value) {
    echo '<option value="' . $value['department_id'] . '">' . $value['department'] . '</option>';
}
?>
        </select><br>
          <label for="start_date">Start Date</label>
          <input type="date" name="start_date" required><br>
         
          <input type="submit" value="Submit" name="submit" class="btn btn-info">

     </div>
</form>

<script src="./js/ajax.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
