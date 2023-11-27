<?php
require "server/db.php";
$errors = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$sql = "SELECT * FROM class WHERE class_id = :class_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":class_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit'])) {
        $class_id = $_POST['class_id'];
        $class_name = $_POST['class_name'];
        $teacher_id = $_POST['teacher_id'];
       
        $editclass = "UPDATE `class` SET `class_name`=:class_name, `teacher_id`=:teacher_id WHERE `class_id`=:class_id";

        $editclassstatement = $pdo->prepare($editclass);
        $editclassstatement->bindParam(":class_id", $class_id, PDO::PARAM_STR);
        $editclassstatement->bindParam(":class_name", $class_name, PDO::PARAM_STR);
        $editclassstatement->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
        $res = $editclassstatement->execute();

        if ($res) {
            // header("Location: class_index.php?id=" . $class_id);
            header("Location: class_index.php");
            exit();
        }
    }
}
?>


<form action="edit_class.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow table-primary">
     <?php
foreach ($result as $key => $value) {
?>
    <label for="class_id">class ID</label>
    <input type="text" name="class_id" value="<?= $value['class_id'] ?? "" ?>"> <br>
    <label for="class_name">class_name</label>
    <input type="text" name="class_name" value="<?= $value['class_name'] ?? "" ?>"><br>
    <label for="teacher_id">Lecturer</label>
<select name="teacher_id">
    <?php
    $selected_teacher_id = $value['teacher_id'];

    $teacher_query = "SELECT teacher_id, firstname, lastname FROM teacher";
    $teacher_stmt = $pdo->query($teacher_query);
    $teachers = $teacher_stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($teachers as $teacher) {
        $teacher_fullname = $teacher['firstname'] . ' ' . $teacher['lastname'];
        $selected = ($teacher['teacher_id'] == $selected_teacher_id) ? 'selected' : '';
        echo '<option value="' . $teacher['teacher_id'] . '" ' . $selected . '>' . $teacher_fullname . '</option>';
    }
    ?>
</select><br>


    <input type="submit" value="Edit" name="edit" class="btn btn-info">
<?php } ?>

     </div>
</form>
