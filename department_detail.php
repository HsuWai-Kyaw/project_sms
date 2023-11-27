<?php
require "server/db.php";
require "component/header.php";
$errors = [];

if (isset($_GET['id'])) {

     $id = $_GET['id'];
}


$sql = "SELECT * FROM department WHERE department_id = :department_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":department_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
// print_r($result);
// die();

?>

<form action="department_detail.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow">
            <?php
               foreach ($result as $key => $value) { 
            ?>
          <label for="department_id">Department ID</label>
          <input type="text" name="department_id" value="<?= $value['department_id'] ?>"> <br>
          <label for="department">Department</label>
          <input type="text" name="department" value="<?= $value['department'] ?>"><br>
          <label for="head_of_department">Head of Department</label>
          <input type="text" name="head_of_department" value="<?= $value['head_of_department'] ?>"><br>
          <label for="description">Description</label>
          <input type="text" name="description" value="<?= $value['description'] ?>"><br>
          <label for="location">Location</label>
          <input type="text" name="location" value="<?= $value['location'] ?>"ired><br>
          <label for="contact_number">Contact Number</label>
          <input type="phone" name="contact_number" value="<?= $value['contact_number'] ?>"><br>
          <label for="email">email</label>
          <input type="email" name="email" value="<?= $value['email'] ?>"><br>
          <label for="start_date">Start Date</label>
          <input type="date" name="start_date" value="<?= $value['start_date'] ?>"><br>
          
          <?php } ?>
     </div>
</form>

<button class="btn btn-light" name="back"><a href="department_index.php">Back</a></button>

<?php
require "component/footer.php";

?>