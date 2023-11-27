<?php
require "server/db.php";
require "component/header.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['register'])) {
    $student_id = $_POST['student_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $photo = $_FILES['photo'];
    $pname = $_FILES['photo']['name'];
    $tmp_name = $_FILES['photo']['tmp_name'];
    move_uploaded_file($tmp_name, "img/student/$pname");

    // Validate image file
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
$fileExtension = pathinfo($pname, PATHINFO_EXTENSION);

if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
    $errors[] = "Invalid file format. Please upload a valid image.";
}

// Check file size (for example, limit to 2MB)
$maxFileSize = 2 * 1024 * 1024; // 2MB
if ($_FILES['photo']['size'] > $maxFileSize) {
    $errors[] = "File size exceeds the limit. Please upload a smaller image.";
}

    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $joining_date = $_POST['joining_date'];
    $class_id = $_POST['class_id'];

    $add_student= "INSERT INTO `student`(`student_id`, `firstname`, `lastname`, `username`, `email`, `password`, `dob`, `gender`, `photo`, `address`, `phone`, `joining_date`, `class_id`) VALUES (:student_id, :firstname, :lastname, :username, :email, :hashedPassword, :dob,:gender, :photo, :address, :phone, :joining_date, :class_id)";

    $add_student_statement = $pdo->prepare($add_student);
    $add_student_statement->bindParam(":student_id", $student_id, PDO::PARAM_STR);
    $add_student_statement->bindParam(":firstname", $firstname, PDO::PARAM_STR);
    $add_student_statement->bindParam(":lastname", $lastname, PDO::PARAM_STR);
    $add_student_statement->bindParam(":username", $username, PDO::PARAM_STR);
    $add_student_statement->bindParam(":email", $email, PDO::PARAM_STR);
    $add_student_statement->bindParam(":hashedPassword", $hashedPassword, PDO::PARAM_STR);
    $add_student_statement->bindParam(":dob", $dob, PDO::PARAM_STR);
    $add_student_statement->bindParam(":gender", $gender, PDO::PARAM_STR);
    $add_student_statement->bindParam(":photo", $pname, PDO::PARAM_STR);
    $add_student_statement->bindParam(":address", $address, PDO::PARAM_STR);
    $add_student_statement->bindParam(":phone", $phone, PDO::PARAM_STR);
    $add_student_statement->bindParam(":joining_date", $joining_date, PDO::PARAM_STR);
    $add_student_statement->bindParam(":class_id", $class_id, PDO::PARAM_STR);

    $res = $add_student_statement->execute();

    if ($res) {
        header("location: student_index.php");
        exit();
    } else {
        $errors[] = "Error occurred while storing data";
    }
}
?>

<form action="add_student.php" method="POST" enctype="multipart/form-data">
    <div class="container shadow shadow">
        <label for="photo">Profile</label>
        <img src="img/empty.jpg" alt="" id="img" width="100px">
        <input type="file" name="photo" id="file" required><br>
        <label for="student_id">Student ID</label>
        <input type="text" name="student_id" required><br>
        <label for="firstname">First Name</label>
        <input type="text" name="firstname" required><br>
        <label for="lastname">Last Name</label>
        <input type="text" name="lastname" required><br>
        <label for="username">Username</label>
        <input type="text" name="username" required><br>
        <label for="email">Email</label>
        <input type="email" name="email" required><br>
        <label for="password">Password</label>
        <input type="password" name="password" required><br>
        <label for="dob">Date of Birth</label>
        <input type="date" name="dob" required><br>
        <label for="gender">Gender</label>
        <div>
        <input type="radio" id="male" name="gender" value="male" required>
        <label for="male">Male</label>

        <input type="radio" id="female" name="gender" value="female" required>
        <label for="female">Female</label>
        </div><br>
        <label for="address">Address</label>
        <input type="text" name="address" required><br>
        <label for="phone">Phone Number</label>
        <input type="phone" name="phone" required><br>
        <label for="joining_date">Joining Date</label>
        <input type="date" name="joining_date" required><br>
        <label for="class_id">Select Class</label>
        <select name="class_id">
            <?php
            $class_query = "SELECT class_id, class_name FROM class";
            $class_stmt = $pdo->query($class_query);
            $class = $class_stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($class as $value) {
                echo '<option value="'. $value['class_id']. '">'. $value['class_name']. '</option>';
            }
            ?>
        </select><br>
        
        <input type="submit" value="Submit" name="register" class="btn btn-info">
    </div>
</form>

<script src="./utility/function_image.js"></script>
<?php
require "component/footer.php";
?>
