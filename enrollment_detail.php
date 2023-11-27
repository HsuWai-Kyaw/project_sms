<?php
require "server/db.php";
require "component/header.php";
$errors = [];

if (isset($_GET['id'])) {

     $id = $_GET['id'];
}


$sql = "SELECT * FROM enrollment WHERE enrollment_id = :enrollment_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":enrollment_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
// print_r($result);
// die();

?>

<form action="enrollment_detail.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow">
            <?php
               foreach ($result as $key => $value) { 
            ?>
          <label for="enrollment_id">enrollment ID</label>
          <input type="text" name="enrollment_id" value="<?= $value['enrollment_id'] ?>"> <br>
          <label for="student_id">Student Name</label>
          <?php
                         $student_sql = "SELECT * FROM student WHERE student_id = '$value[student_id]'";
                         $student_res = $pdo->prepare($student_sql);
                         $student_res->execute();
                         $student = $student_res->fetch(PDO::FETCH_ASSOC);

                    ?>
          <input type="text" name="student_id" value="<?= $student['firstname'] . " " . $student['lastname'] ?>"><br>
          <label for="course_id">Course Name</label>
          <?php
                    $course_sql = "SELECT * FROM courses WHERE course_id = '$value[course_id]'";
                    $course_res = $pdo->prepare($course_sql);
                    $course_res->execute();
                    $course = $course_res->fetch(PDO::FETCH_ASSOC);
                    ?>
          <input type="text" name="course_id" value="<?= $course['course_name'] ?>"><br>
          <label for="enrollment_date">enrollment_date</label>
          <input type="text" name="enrollment_date" value="<?= $value['enrollment_date'] ?>"><br>
          <label for="status">status</label>
          <input type="text" name="status" value="<?= $value['status'] ?>"><br>
          
          <?php } ?>
     </div>
</form>

<button class="btn btn-light" name="back"><a href="enrollment_index.php">Back</a></button>

<?php
require "component/footer.php";

?>