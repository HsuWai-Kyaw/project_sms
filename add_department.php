<?php
require "server/db.php";
require "component/header.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
     if (isset($_POST['submit'])) {
          // $id = $_POST['department_id'];
          $department = $_POST['department'];
          $head_of_department = $_POST['head_of_department'];
          $description = $_POST['description'];
          $location = $_POST['location'];
          $contact_number = $_POST['contact_number'];
          $email = $_POST['email'];
          $start_date = $_POST['start_date'];

          $adddepartment = "INSERT INTO `department`(`department`, `head_of_department`, `description`, `location`, `contact_number`, `email`, `start_date`) VALUES (:department,:head_of_department,:description,:location,:contact_number,:email,:start_date)";

          $adddepartmentstatement = $pdo->prepare($adddepartment);

          // $adddepartmentstatement->bindParam(":department_id", $department_id, PDO::PARAM_STR);
          $adddepartmentstatement->bindParam(":department", $department, PDO::PARAM_STR);
          $adddepartmentstatement->bindParam(":head_of_department", $head_of_department, PDO::PARAM_STR);
          $adddepartmentstatement->bindParam(":description", $description, PDO::PARAM_STR);
          $adddepartmentstatement->bindParam(":location", $location, PDO::PARAM_STR);
          $adddepartmentstatement->bindParam(":contact_number", $contact_number, PDO::PARAM_STR);
          $adddepartmentstatement->bindParam(":email", $email, PDO::PARAM_STR);
          $adddepartmentstatement->bindParam(":start_date", $start_date, PDO::PARAM_STR);
          
          $res = $adddepartmentstatement->execute();

          if ($res) {
               // echo "Data Stored";
               header("location:department_index.php");
          } else {
               $errors[] = "Error occurred while storing data";
          }
     }
}
?>

<form action="add_department.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow">
          <!-- <label for="department_id">Department ID</label>
          <input type="text" name="department_id" required> <br> -->
          <label for="department">Department</label>
          <input type="text" name="department" required><br>
          <label for="head_of_department">Head of Department</label>
          <input type="text" name="head_of_department" required><br>
          <label for="description">Description</label>
          <input type="text" name="description" required><br>
          <label for="location">Location</label>
          <input type="text" name="location" required><br>
          <label for="contact_number">Contact Number</label>
          <input type="phone" name="contact_number" required><br>
          <label for="email">email</label>
          <input type="email" name="email" required><br>
          <label for="start_date">Start Date</label>
          <input type="date" name="start_date" required><br>
          <input type="submit" value="Submit" name="submit" class="btn btn-info">

     </div>
</form>

<script src="./js/ajax.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
