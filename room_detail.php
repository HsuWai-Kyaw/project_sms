<?php
require "server/db.php";
require "component/header.php";
$errors = [];

if (isset($_GET['id'])) {

     $id = $_GET['id'];
}


$sql = "SELECT * FROM room WHERE room_id = :room_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":room_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
// print_r($result);
// die();

?>

<form action="room_detail.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow">
            <?php
               foreach ($result as $key => $value) { 
            ?>
          <label for="room_id">room ID</label>
          <input type="text" name="room_id" value="<?= $value['room_id'] ?>"> <br>
          <label for="room_number">room number</label>
          <input type="text" name="room_number" value="<?= $value['room_number'] ?>"><br>
          <label for="total_seats">total_seats</label>
          <input type="text" name="total_seats" value="<?= $value['total_seats'] ?>"><br>
          <label for="location">Location</label>
          <input type="text" name="location" value="<?= $value['location'] ?>"ired><br>
          <?php
foreach ($result as $key => $value) {
    $department_id = $value['department_id'];
    $departmentQuery = "SELECT department FROM department WHERE department_id = :department_id";
    $departmentStatement = $pdo->prepare($departmentQuery);
    $departmentStatement->bindParam(":department_id", $department_id, PDO::PARAM_STR);
    $departmentStatement->execute();
    $dep_res = $departmentStatement->fetch(PDO::FETCH_ASSOC);
?>
    <label for="department_id">Department</label>
    <input type="text" name="department_id" value="<?= $dep_res['department'] ?? '' ?>" readonly><br>
    <?php } ?>
          <?php } ?>
     </div>
</form>

<button class="btn btn-light" name="back"><a href="room_index.php">Back</a></button>

<?php
require "component/footer.php";

?>