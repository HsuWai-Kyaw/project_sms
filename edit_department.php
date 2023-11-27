<?php
require "server/db.php";
$errors = [];

if (isset($_GET['id'])) {

     $id = $_GET['id'];
}

$sql = "SELECT * FROM department WHERE department_id = :department_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":department_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     if (isset($_POST['edit'])) {
          // print_r($_POST);
          // die();
          $id = $_POST['department_id'];
          $department = $_POST['department'];
          $head_of_department = $_POST['head_of_department'];
          $description = $_POST['description'];
          $location = $_POST['location'];
          $contact_number = $_POST['contact_number'];
          $email = $_POST['email'];
          $start_date = $_POST['start_date'];

          $editdepartment = "UPDATE `department` SET `department`=:department,`head_of_department`=:head_of_department,`description`=:description,`location`=:location,`contact_number`=:contact_number,`email`=:email,`start_date`=:start_date WHERE `department_id`=:department_id";

          $editdepartmentstatement = $pdo->prepare($editdepartment);

          $editdepartmentstatement->bindParam(":department_id", $id, PDO::PARAM_STR);
          $editdepartmentstatement->bindParam(":department", $department, PDO::PARAM_STR);
          $editdepartmentstatement->bindParam(":head_of_department", $head_of_department, PDO::PARAM_STR);
          $editdepartmentstatement->bindParam(":description", $description, PDO::PARAM_STR);
          $editdepartmentstatement->bindParam(":location", $location, PDO::PARAM_STR);
          $editdepartmentstatement->bindParam(":contact_number", $contact_number, PDO::PARAM_STR);
          $editdepartmentstatement->bindParam(":email", $email, PDO::PARAM_STR);
          $editdepartmentstatement->bindParam(":start_date", $start_date, PDO::PARAM_STR);
          $res = $editdepartmentstatement->execute();

          if ($res) {
               header("Location: department_detail.php?id=" . $id);
               exit();
          }
     }
}
?>

<form action="edit_department.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow table-primary">
     <?php
foreach ($result as $key => $value) {
?>
    <label for="department_id">Department ID</label>
    <input type="text" name="department_id" value="<?= $value['department_id'] ?? "" ?>"> <br>
    <label for="department">Department</label>
    <input type="text" name="department" value="<?= $value['department'] ?? "" ?>"><br>
    <label for="head_of_department">Head of Department</label>
    <input type="text" name="head_of_department" value="<?= $value['head_of_department'] ?? "" ?>"><br>
    <label for="description">Description</label>
    <input type="text" name="description" value="<?= $value['description'] ?? "" ?>"><br>
    <label for="location">Location</label>
    <input type="text" name="location" value="<?= $value['location'] ?? "" ?>"><br>
    <label for="contact_number">Contact Number</label>
    <input type="phone" name="contact_number" value="<?= $value['contact_number'] ?? "" ?>"><br>
    <label for="email">Email</label>
    <input type="email" name="email" value="<?= $value['email'] ?? "" ?>"><br>
    <label for="start_date">Start Date</label>
    <input type="date" name="start_date" value="<?= $value['start_date'] ?? "" ?>"><br>
    <input type="submit" value="Edit" name="edit" class="btn btn-info">
<?php } ?>

     </div>
</form>
