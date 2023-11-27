<?php
require "server/db.php";
$errors = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$sql = "SELECT * FROM student WHERE student_id = :student_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":student_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

// Fetch all classes
$sqlClasses = "SELECT * FROM class";
$statementClasses = $pdo->prepare($sqlClasses);
$statementClasses->execute();
$classes = $statementClasses->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $id = $_POST['id'];
    $student_id = $_POST['student_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $oldphoto = $_POST['oldphoto'];
    $photo = $_FILES['photo'];
    $pname = $_FILES['photo']['name'];
    $tmp_name = $_FILES['photo']['tmp_name'];
    if ($pname != "") {
        move_uploaded_file($tmp_name, "img/student/$pname");
    } else {
        $pname = $oldphoto;
    }
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $joining_date = $_POST['joining_date'];
    $class_id = $_POST['class_id'];
    // Check if a new password is provided
    $newPassword = $_POST['new_password'];
    if (!empty($newPassword)) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Use a separate prepared statement for the case where a new password is provided
        $editstudent = "UPDATE `student` SET `student_id`=:student_id,`firstname`=:firstname,`lastname`=:lastname, `username`=:username,`email`=:email,`password`=:hashedPassword,`dob`=:dob,`gender`=:gender,`photo`=:photo,`address`=:address,`phone`=:phone,`joining_date`=:joining_date,`class_id`=:class_id WHERE `id`=:id";

        $editstudentstatement = $pdo->prepare($editstudent);
        $editstudentstatement->bindParam(":hashedPassword", $hashedPassword, PDO::PARAM_STR);
    } else {
        // Use a separate prepared statement for the case where no new password is provided
        $editstudent = "UPDATE `student` SET `student_id`=:student_id,`firstname`=:firstname,`lastname`=:lastname, `username`=:username,`email`=:email,`dob`=:dob,`gender`=:gender,`photo`=:photo,`address`=:address,`phone`=:phone,`joining_date`=:joining_date,`class_id`=:class_id WHERE `id`=:id";

        $editstudentstatement = $pdo->prepare($editstudent);
    }

    // Bind common parameters
    $editstudentstatement->bindParam(":id", $id, PDO::PARAM_STR);
    $editstudentstatement->bindParam(":student_id", $student_id, PDO::PARAM_STR);
    $editstudentstatement->bindParam(":firstname", $firstname, PDO::PARAM_STR);
    $editstudentstatement->bindParam(":lastname", $lastname, PDO::PARAM_STR);
    $editstudentstatement->bindParam(":username", $username, PDO::PARAM_STR);
    $editstudentstatement->bindParam(":photo", $pname, PDO::PARAM_STR);
    $editstudentstatement->bindParam(":dob", $dob, PDO::PARAM_STR);
    $editstudentstatement->bindParam(":gender", $gender, PDO::PARAM_STR);
    $editstudentstatement->bindParam(":email", $email, PDO::PARAM_STR);
    $editstudentstatement->bindParam(":phone", $phone, PDO::PARAM_STR);
    $editstudentstatement->bindParam(":address", $address, PDO::PARAM_STR);
    $editstudentstatement->bindParam(":joining_date", $joining_date, PDO::PARAM_STR);
    $editstudentstatement->bindParam(":class_id", $class_id, PDO::PARAM_STR);

    $res = $editstudentstatement->execute();

    if ($res) {
        // header("Location: course_index.php?id=" . $student_id);
        header("Location: student_index.php");
        exit();
    }
}
?>

<form action="edit_student.php" method="POST" enctype="multipart/form-data">
    <div class="container shadow shadow table-primary">
        <?php foreach ($result as $key => $value) { ?>
            <input type="hidden" name="id" value="<?= $value['id'] ?>">
            <label for="photo">Profile</label>
            <input type="hidden" name="oldphoto" value="<?php echo $value['photo'] ?>">
            <img src="img/student/<?= $value['photo'] ?>" name="photo" alt="" id="img" width="100px">
            <input type="file" name="photo" accept="image/*" id="file" class="form-control file" hidden><br>
            <label for="student_id">student ID</label>
            <input type="text" name="student_id" value="<?= $value['student_id'] ?>" readonly><br>
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" value="<?= $value['firstname'] ?>"><br>
            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" value="<?= $value['lastname'] ?>"><br>
            <label for="username">UserName</label>
            <input type="text" name="username" value="<?= $value['username']?>"><br>
            <label for="dob">DOB</label>
            <input type="date" name="dob" value="<?= $value['dob'] ?>"><br>
            <label for="gender">Gender</label>
            <div>
                <input type="radio" id="male" name="gender" value="male" <?= ($value['gender'] ?? '') === 'male' ? 'checked' : '' ?>>
                <label for="male">Male</label>

                <input type="radio" id="female" name="gender" value="female" <?= ($value['gender'] ?? '') === 'female' ? 'checked' : '' ?>>
                <label for="female">Female</label>
            </div>
            <label for="email">Email</label>
            <input type="text" name="email" value="<?= $value['email'] ?>"><br>
            <label for="password">Password</label>
            <input type="password" name="password" value="<?= $value['password']?>" hidden>
            <input type="password" name="new_password"> <br>
            <label for="phone">Phone</label>
            <input type="tel" name="phone" value="<?= $value['phone'] ?>"><br>
            <label for="address">Address</label>
            <input type="text" name="address" value="<?= $value['address'] ?>"><br>
            <label for="joining_date">Joining Date</label>
            <input type="date" name="joining_date" value="<?= $value['joining_date'] ?>"><br>
            
            <label for="class_id">Class</label>
            <select name="class_id" id="class_id">
                <?php foreach ($classes as $classItem) { ?>
                    <option value="<?= $classItem['class_id'] ?>" <?= ($value['class_id'] ?? '') == $classItem['class_id'] ? 'selected' : '' ?>>
                        <?= $classItem['class_name'] ?>
                    </option>
                <?php } ?>
            </select><br>

            <input type="submit" value="Edit" name="edit" class="btn btn-info">
        <?php } ?>
    </div>
</form>

<script src="./utility/function_image.js"></script>
