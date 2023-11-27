<?php
require "server/db.php";
$errors = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$sql = "SELECT * FROM teacher WHERE teacher_id = :teacher_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":teacher_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $teacher_id = $_POST['teacher_id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $oldphoto = $_POST['oldphoto'];
        $id_card = $_FILES['id_card'];
        $pname = $_FILES['id_card']['name'];
        $tmp_name = $_FILES['id_card']['tmp_name'];
        if ($pname != "") {
            move_uploaded_file($tmp_name, "img/teacher/$pname");
        } else {
            $pname = $oldphoto;
        }
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $qualification = $_POST['qualification'];
    $experience = $_POST['experience'];
    $joining_date = $_POST['joining_date'];
    $subject_specialization = $_POST['subject_specialization'];
    $department_id = $_POST['department_id'];
    $role_id = $_POST['role_id'];
    $salary = $_POST['salary'];
    $notes = $_POST['notes'];

    $editteacher = "UPDATE `teacher` SET `firstname`=:firstname,`lastname`=:lastname,`id_card`=:id_card,`dob`=:dob,`gender`=:gender,`email`=:email,`password`=:password,`phone`=:phone,`address`=:address,`qualification`=:qualification,`experience`=:experience,`joining_date`=:joining_date,`subject_specialization`=:subject_specialization,`department_id`=:department_id,`role_id`=:role_id,`salary`=:salary,`notes`=:notes WHERE teacher_id=:teacher_id";

    $editteacherstatement = $pdo->prepare($editteacher);
    // $editteacherstatement->bindParam(":id", $id, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":firstname", $firstname, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":lastname", $lastname, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":id_card", $pname, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":dob", $dob, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":gender", $gender, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":email", $email, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":password", $password, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":phone", $phone, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":address", $address, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":qualification", $qualification, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":experience", $experience, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":joining_date", $joining_date, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":subject_specialization", $subject_specialization, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":department_id", $department_id, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":role_id", $role_id, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":salary", $salary, PDO::PARAM_STR);
    $editteacherstatement->bindParam(":notes", $notes, PDO::PARAM_STR);

    $res = $editteacherstatement->execute();

        if ($res) {
            // header("Location: course_index.php?id=" . $teacher_id);
            header("Location: teacher_index.php");
            exit();
        }
    }
}
?>


<form action="edit_teacher.php" method="POST" enctype="multipart/form-data">
    <div class="container shadow shadow table-primary">
        <?php foreach ($result as $key => $value) { ?>
            <label for="id_card">Profile</label>

            <input hidden name="oldphoto" value="<?php echo $value['id_card'] ?>"></input>
            <img src="img/teacher/<?= $value['id_card'] ?>" name="id_card" alt="" id="img" width="100px">
            <input type="file" name="id_card" accept="image/*" id="file" class="form-control file" hidden><br>
            <label for="teacher_id">Teacher ID</label>
            <input type="text" name="teacher_id" value="<?= $value['teacher_id'] ?>" readonly><br>
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" value="<?= $value['firstname'] ?>"><br>
            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" value="<?= $value['lastname'] ?>"><br>
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
            <input type="password" name="password" value="<?= $value['password'] ?>"><br>
            <label for="phone">Phone</label>
            <input type="text" name="phone" value="<?= $value['phone'] ?>"><br>
            <label for="address">Address</label>
            <input type="text" name="address" value="<?= $value['address'] ?>"><br>
            <label for="qualification">Qualification</label>
            <input type="text" name="qualification" value="<?= $value['qualification'] ?>"><br>
            <label for="experience">Experience</label>
            <input type="text" name="experience" value="<?= $value['experience'] ?>"><br>
            <label for="joining_date">Joining Date</label>
            <input type="date" name="joining_date" value="<?= $value['joining_date'] ?>"><br>
            <label for="subject_specialization">Subject Specialization</label>
            <input type="text" name="subject_specialization" value="<?= $value['subject_specialization'] ?>"><br>

            <label for="department_id">Department</label>
            <select name="department_id" id="department_id">
                <?php
                $sql = "SELECT * FROM department";
                $statement = $pdo->prepare($sql);
                $statement->execute();
                $departments = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($departments as $department) {
                ?>
                    <option value="<?= $department['department_id'] ?>" <?= ($value['department_id'] ?? '') == $department['department_id'] ? 'selected' : '' ?>>
                        <?= $department['department'] ?>
                    </option>
                <?php } ?>
            </select><br>

            <label for="role_id">Role</label>
            <select name="role_id" id="role_id">
                <?php
                $sql = "SELECT * FROM roles";
                $statement = $pdo->prepare($sql);
                $statement->execute();
                $roles = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($roles as $role) {
                ?>
                    <option value="<?= $role['role_id'] ?>" <?= ($value['role_id'] ?? '') == $role['role_id'] ? 'selected' : '' ?>>
                        <?= $role['role'] ?>
                    </option>
                <?php } ?>
            </select><br>

            <label for="salary">Salary</label>
            <input type="text" name="salary" value="<?= $value['salary'] ?>"><br>
            <label for="notes">Notes</label>
            <input type="text" name="notes" value="<?= $value['notes'] ?>"><br>
            <input type="submit" value="Edit" name="edit" class="btn btn-info">
        <?php } ?>
    </div>
</form>

<script src="./utility/function_image.js"></script>

