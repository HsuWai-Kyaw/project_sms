<?php
require "server/db.php";
require "component/header.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
     if (isset($_POST['submit'])) {
          $class_name = $_POST['class_name'];
          $teacher_id = $_POST['teacher_id'];

          $addclass = "INSERT INTO `class`(`class_name`, `teacher_id`) VALUES (:class_name,:teacher_id)";

          $addclassstatement = $pdo->prepare($addclass);

          $addclassstatement->bindParam(":class_name", $class_name, PDO::PARAM_STR);
          $addclassstatement->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
          
          $res = $addclassstatement->execute();

          if ($res) {
               // echo "Data Stored";
               header("location:class_index.php");
          } else {
               $errors[] = "Error occurred while storing data";
          }
     }
}
?>

<form action="add_class.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow">
          <label for="class_name">Class Title</label>
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
