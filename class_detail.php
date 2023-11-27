<?php
require "server/db.php";
require "component/header.php";
$errors = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$sql = "SELECT c.*, t.firstname, t.lastname FROM class c
        LEFT JOIN teacher t ON c.teacher_id = t.teacher_id
        WHERE c.class_id = :class_id";

$statement = $pdo->prepare($sql);
$statement->bindParam(":class_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<form action="class_detail.php" method="POST" enctype="multipart/form-data">
    <div class="container shadow shadow">
        <?php
        foreach ($result as $key => $value) { 
        ?>
            <label for="class_id">Class ID</label>
            <input type="text" name="class_id" value="<?= $value['class_id'] ?>" readonly><br>
            <label for="class_name">Class Name</label>
            <input type="text" name="class_name" value="<?= $value['class_name'] ?>" readonly><br>
            <label for="lecturer">Lecturer</label>
            <input type="text" name="lecturer" value="<?= $value['firstname'] . ' ' . $value['lastname'] ?>" readonly><br>
        <?php } ?>
    </div>
</form>

<button class="btn btn-light" name="back"><a href="class_index.php">Back</a></button>

<?php
require "component/footer.php";
?>
