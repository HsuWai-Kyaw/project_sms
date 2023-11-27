<?php
require "server/db.php";
require "component/header.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {
    $teacher_id = $_POST['teacher_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $id_card = $_FILES['id_card'];
    $pname = $_FILES['id_card']['name'];
    $tmp_name = $_FILES['id_card']['tmp_name'];
    move_uploaded_file($tmp_name, "img/teacher/$pname");

    // Validate image file
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
$fileExtension = pathinfo($pname, PATHINFO_EXTENSION);

if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
    $errors[] = "Invalid file format. Please upload a valid image.";
}

// Check file size (for example, limit to 2MB)
$maxFileSize = 2 * 1024 * 1024; // 2MB
if ($_FILES['id_card']['size'] > $maxFileSize) {
    $errors[] = "File size exceeds the limit. Please upload a smaller image.";
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

    $addteacher = "INSERT INTO `teacher`(`teacher_id`, `firstname`, `lastname`, `id_card`, `dob`, `gender`, `email`,`password`, `phone`, `address`, `qualification`, `experience`, `joining_date`, `subject_specialization`, `department_id`, `role_id`, `salary`, `notes`) VALUES (:teacher_id,:firstname,:lastname,:id_card,:dob,:gender,:email,:password,:phone,:address,:qualification,:experience,:joining_date,:subject_specialization,:department_id,:role_id,:salary,:notes)";

    $addteacherstatement = $pdo->prepare($addteacher);
    $addteacherstatement->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":firstname", $firstname, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":lastname", $lastname, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":id_card", $pname, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":dob", $dob, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":gender", $gender, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":email", $email, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":password", $password, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":phone", $phone, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":address", $address, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":qualification", $qualification, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":experience", $experience, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":joining_date", $joining_date, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":subject_specialization", $subject_specialization, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":department_id", $department_id, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":role_id", $role_id, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":salary", $salary, PDO::PARAM_STR);
    $addteacherstatement->bindParam(":notes", $notes, PDO::PARAM_STR);

    $res = $addteacherstatement->execute();

    if ($res) {
        header("location: teacher_index.php");
        exit();
    } else {
        $errors[] = "Error occurred while storing data";
    }
}
?>

<form action="add_teacher.php" method="POST" enctype="multipart/form-data">
    <div class="container shadow shadow">
        <label for="id_card">ID Card</label>
        <img src="img/empty.jpg" alt="" id="img" width="100px">
        <input type="file" name="id_card" id="file" required><br>
        <label for="teacher_id">ID Number</label>
        <input type="text" name="teacher_id" required><br>
        <label for="firstname">Firstname</label>
        <input type="text" name="firstname" required><br>
        <label for="lastname">Lastname</label>
        <input type="text" name="lastname" required><br>
        <label for="dob">DOB</label>
        <input type="date" name="dob" required><br>
        <label for="gender">Gender</label>
    <div>
        <input type="radio" id="male" name="gender" value="male" required>
        <label for="male">Male</label>

        <input type="radio" id="female" name="gender" value="female" required>
        <label for="female">Female</label>
    </div><br>
        <label for="email">Email</label>
        <input type="email" name="email" required><br>
        <label for="password">Password</label>
        <input type="password" name="password" required><br>
        <label for="phone">Phone</label>
        <input type="number" name="phone" required><br>
        <label for="address">Address</label>
        <textarea name="address" cols="50" rows="4"></textarea><br>
        <label for="qualification">Qualification</label>
        <textarea name="qualification" cols="50" rows="4"></textarea><br>
        <label for="experience">Experience</label>
        <textarea name="experience" cols="50" rows="4"></textarea><br>
        <label for="joining_date">Joining Date</label>
        <input type="date" name="joining_date" required><br>
        <label for="subject_specialization">Subject Specialization</label>
        <textarea name="subject_specialization" cols="50" rows="4"></textarea><br>
        <label for="department_id">Department</label>
        <select name="department_id">
            <?php
            $query = "SELECT * FROM `department`";
            $statement = $pdo->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {?>
                <option value="<?php echo $row['department_id'];?>"><?php echo $row['department'];?></option>
            <?php }?>
        </select><br>
        <label for="role_id">Role</label>
        <select name="role_id">
        <?php
            $query = "SELECT * FROM `roles`";
            $statement = $pdo->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {?>
                <option value="<?php echo $row['role_id'];?>"><?php echo $row['role'];?></option>
            <?php }?>
        </select><br>
        <label for="salary">Salary</label>
        <input type="number" name="salary" required><br>
        <label for="notes">Notes</label>
        <textarea name="notes" cols="30" rows="10"></textarea><br>
        
        <input type="submit" value="Submit" name="submit" class="btn btn-info">
    </div>
</form>

<script src="./utility/function_image.js"></script>
<?php
require "component/footer.php";
?>
