<?php
require "server/db.php";
$errors = [];

if (isset($_GET['id'])) {

     $id = $_GET['id'];
}

$sql = "SELECT * FROM room WHERE room_id = :room_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":room_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     if (isset($_POST['edit'])) {
          // print_r($_POST);
          // die();
          $id = $_POST['room_id'];
          $room_number = $_POST['room_number'];
          $total_seats = $_POST['total_seats'];
          $location = $_POST['location'];
          $department_id = $_POST['department_id'];

          $editroom = "UPDATE `room` SET `room_number`=:room_number,`total_seats`=:total_seats,`location`=:location,`department_id`=:department_id WHERE `room_id`=:room_id";

          $editroomstatement = $pdo->prepare($editroom);

          $editroomstatement->bindParam(":room_id", $id, PDO::PARAM_STR);
          $editroomstatement->bindParam(":room_number", $room_number, PDO::PARAM_STR);
          $editroomstatement->bindParam(":total_seats", $total_seats, PDO::PARAM_STR);
          $editroomstatement->bindParam(":location", $location, PDO::PARAM_STR);
          $editroomstatement->bindParam(":department_id", $department_id, PDO::PARAM_STR);
          $res = $editroomstatement->execute();

          if ($res) {
               header("Location: room_detail.php?id=" . $id);
               exit();
          }
     }
}
?>

<form action="edit_room.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow table-primary">
     <?php
foreach ($result as $key => $value) {
?>
    <label for="room_id">room ID</label>
    <input type="text" name="room_id" value="<?= $value['room_id'] ?? "" ?>"> <br>
    <label for="room_number">room</label>
    <input type="text" name="room_number" value="<?= $value['room_number'] ?? "" ?>"><br>
    <label for="total_seats">total_seats</label>
    <input type="text" name="total_seats" value="<?= $value['total_seats'] ?? "" ?>"><br>
    <label for="location">Location</label>
    <input type="text" name="location" value="<?= $value['location'] ?? "" ?>"><br>
    <label for="department_id">Department</label>
<select name="department_id">
    <?php
    $selected_department_id = $value['department_id'];

    $department_query = "SELECT department_id, department FROM department";
    $department_stmt = $pdo->query($department_query);
    $departments = $department_stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($departments as $dept) {
        $selected = ($dept['department_id'] == $selected_department_id) ? 'selected' : '';
        echo '<option value="' . $dept['department_id'] . '" ' . $selected . '>' . $dept['department'] . '</option>';
    }
    ?>
</select><br>
    <input type="submit" value="Edit" name="edit" class="btn btn-info">
<?php } ?>

     </div>
</form>
