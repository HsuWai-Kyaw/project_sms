<?php
require "server/db.php";
require "component/header.php";
$errors = [];

if (isset($_GET['id'])) {

     $id = $_GET['id'];
}

$sql = "SELECT * FROM student WHERE student_id = :student_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":student_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
// print_r($result);
// die();
$class_query = "SELECT class_id, class_name FROM class";
$class_stmt = $pdo->query($class_query);
$class = $class_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<form action="student_detail.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow">
            <?php
               foreach ($result as $key => $value) { 
            ?>
          <label for="photo">Profile</label>
          <img src="img/student/<?= $value['photo'] ?>" alt="" id="img" width="100px"><br>
          <label for="student_id">student ID</label>
          <input type="text" name="student_id" value="<?= $value['student_id'] ?>"> <br>
          <label for="firstname">First Name</label>
          <input type="text" name="firstname" value="<?= $value['firstname'] ?>"><br>
          <label for="lastname">Last Name</label>
          <input type="text" name="lastname" value="<?= $value['lastname']?>"><br>  
          <label for="username">UserName</label>
          <input type="text" name="username" value="<?= $value['username']?>"><br>  
          <label for="email">Email</label>
          <input type="text" name="email" value="<?= $value['email']?>"><br>
          <label for="password">Password</label>
          <input type="password" name="password" value="<?= $value['password']?>" readonly><br>
          <label for="dob">DOB</label>
          <input type="date" name="dob" value="<?= $value['dob']?>"><br>
          <label for="gender">Gender</label>
            <div>
                <input type="radio" id="male" name="gender" value="male" <?= ($value['gender'] ?? '') === 'male' ? 'checked' : '' ?>>
                <label for="male">Male</label>

                <input type="radio" id="female" name="gender" value="female" <?= ($value['gender'] ?? '') === 'female' ? 'checked' : '' ?>>
                <label for="female">Female</label>
            </div>

          <label for="address">Address</label>
          <input type="text" name="address" value="<?= $value['address']?>"><br>
          <label for="phone">Phone</label>
          <input type="text" name="phone" value="<?= $value['phone']?>"><br>
          <label for="joining_date">Joining Date</label>
          <input type="text" name="joining_date" value="<?= $value['joining_date']?>"><br>
         
          <label for="class_id">Class</label>
            <select name="class_id" id="class_id">
                <?php foreach ($class as $classItem) { ?>
                    <option value="<?= $classItem['class_id'] ?>" <?= ($classItem['class_id'] == $value['class_id']) ? 'selected' : '' ?>>
                        <?= $classItem['class_name'] ?>
                    </option>
                <?php } ?>
            </select><br>
        <?php } ?>
     </div>
</form>

<button class="btn btn-light" name="back"><a href="student_index.php">Back</a></button>

<?php
require "component/footer.php";

?>