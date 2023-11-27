<?php
require "server/db.php";
$errors = [];

if (isset($_GET['id'])) {

     $id = $_GET['id'];
}

$sql = "SELECT * FROM enrollment WHERE enrollment_id = :enrollment_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":enrollment_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     if (isset($_POST['edit'])) {
          // print_r($_POST);
          // die();
          $id = $_POST['enrollment_id'];
          // $student_id = $_POST['student_id'];
          // $course_id = $_POST['course_id'];
          // $enrollment_date = $_POST['enrollment_date'];
          $status = $_POST['status'];

          $editenrollment = "UPDATE `enrollment` SET `status`=:status WHERE `enrollment_id`=:enrollment_id";

          $editenrollmentstatement = $pdo->prepare($editenrollment);
          $editenrollmentstatement->bindParam(":enrollment_id", $id, PDO::PARAM_STR);
          $editenrollmentstatement->bindParam(":status", $status, PDO::PARAM_STR);
          $res = $editenrollmentstatement->execute();

          if ($res) {
               header("Location: enrollment_detail.php?id=" . $id);
               exit();
          }
     }
}
?>

<form action="edit_enrollment.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow table-primary">
     <?php
foreach ($result as $key => $value) {
?>
    <label for="enrollment_id">enrollment ID</label>
    <input type="text" name="enrollment_id" value="<?= $value['enrollment_id'] ?? "" ?>"> <br>
    <label for="student_id">student_id</label>
    <?php
                         $student_sql = "SELECT * FROM student WHERE student_id = '$value[student_id]'";
                         $student_res = $pdo->prepare($student_sql);
                         $student_res->execute();
                         $student = $student_res->fetch(PDO::FETCH_ASSOC);

                    ?>
    <input type="text" name="student_id" value="<?= $student['firstname'] . " " . $student['lastname'] ?? "" ?>"><br>
    <label for="course_id">course_id</label>
    <?php
                    $course_sql = "SELECT * FROM courses WHERE course_id = '$value[course_id]'";
                    $course_res = $pdo->prepare($course_sql);
                    $course_res->execute();
                    $course = $course_res->fetch(PDO::FETCH_ASSOC);
                    ?>
    <input type="text" name="course_id" value="<?= $course['course_name'] ?? "" ?>"><br>
    <label for="enrollment_date">enrollment_date</label>
    <input type="enrollment_date" name="enrollment_date" value="<?= $value['enrollment_date'] ?? "" ?>"><br>
    <label for="status">Status</label>
    <select name="status">
     <?php
     if ($value['status'] == "Pending") {
          echo '<option value="Pending" selected>Pending</option>';
     } else {
          echo '<option value="Pending">Pending</option>';
     }
     if ($value['status'] == "Approved") {
          echo '<option value="Approved" selected>Approved</option>';
     } else {
          echo '<option value="Approved">Approved</option>';
     }
     if ($value['status'] == "Rejected") {
          echo '<option value="Rejected" selected>Rejected</option>';
     } else {
          echo '<option value="Rejected">Rejected</option>';
     }
   ?>
   
    </select><br>

<?php } ?>
    
    <br>
    <input type="submit" value="Edit" name="edit" class="btn btn-info">

     </div>
</form>
