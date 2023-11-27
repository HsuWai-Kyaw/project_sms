<?php
require "server/db.php";
require "component/header.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {
    $room_number = $_POST['room_number'];
    $total_seats = $_POST['total_seats'];
    $location = $_POST['location'];
    $department_id = $_POST['department_id'];

    $addroom = "INSERT INTO `room`(`room_number`, `total_seats`, `location`, `department_id`) VALUES (:room_number, :total_seats, :location, :department_id)";

    $addroomstatement = $pdo->prepare($addroom);
    $addroomstatement->bindParam(":room_number", $room_number, PDO::PARAM_STR);
    $addroomstatement->bindParam(":total_seats", $total_seats, PDO::PARAM_STR);
    $addroomstatement->bindParam(":location", $location, PDO::PARAM_STR);
    $addroomstatement->bindParam(":department_id", $department_id, PDO::PARAM_STR);

    $res = $addroomstatement->execute();

    if ($res) {
        header("location: room_index.php");
        exit();
    } else {
        $errors[] = "Error occurred while storing data";
    }
}
?>

<form action="add_room.php" method="POST" enctype="multipart/form-data">
    <div class="container shadow shadow">
        <label for="room_number">Room Number</label>
        <input type="text" name="room_number" required><br>
        <label for="total_seats">Total seats</label>
        <input type="text" name="total_seats" required><br>
        <label for="location">Location</label>
        <input type="text" name="location" required><br>
        <label for="department_id">Select Department</label>
        <select name="department_id">
            <?php
            $department_query = "SELECT department_id, department FROM department";
            $department_stmt = $pdo->query($department_query);
            $dept = $department_stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dept as $value) {
                echo '<option value="' . $value['department_id'] . '">' . $value['department'] . '</option>';
            }
            ?>
        </select><br>
        <input type="submit" value="Submit" name="submit" class="btn btn-info">
    </div>
</form>

<?php
require "component/footer.php";
?>
