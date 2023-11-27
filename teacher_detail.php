<?php
require "server/db.php";
require "component/header.php";
$errors = [];

if (isset($_GET['id'])) {

     $id = $_GET['id'];
}


$sql = "SELECT * FROM teacher WHERE teacher_id = :teacher_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":teacher_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
// print_r($result);
// die();

?>

<form action="teacher_detail.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow">
            <?php
               foreach ($result as $key => $value) { 
            ?>
          <label for="id_card">Profile</label>
          <img src="img/teacher/<?= $value['id_card'] ?>" alt="" id="img" width="100px"><br>
          <label for="teacher_id">teacher ID</label>
          <input type="text" name="teacher_id" value="<?= $value['teacher_id'] ?>"> <br>
          <label for="firstname">First Name</label>
          <input type="text" name="firstname" value="<?= $value['firstname'] ?>"><br>
          <label for="lastname">Last Name</label>
          <input type="text" name="lastname" value="<?= $value['lastname']?>"><br>  
          <label for="dob">DOB</label>
          <input type="date" name="dob" value="<?= $value['dob']?>"><br>
          <label for="gender">Gender</label>
<div>
    <input type="radio" id="male" name="gender" value="male" <?= ($value['gender'] ?? '') === 'male' ? 'checked' : '' ?>>
    <label for="male">Male</label>

    <input type="radio" id="female" name="gender" value="female" <?= ($value['gender'] ?? '') === 'female' ? 'checked' : '' ?>>
    <label for="female">Female</label>
</div>

          <label for="email">Email</label>
          <input type="text" name="email" value="<?= $value['email']?>"><br>
          <label for="password">Password</label>
          <input type="text" name="password" value="<?= $value['password']?>"><br>
          <label for="phone">Phone</label>
          <input type="text" name="phone" value="<?= $value['phone']?>"><br>
          <label for="address">Address</label>
          <input type="text" name="address" value="<?= $value['address']?>"><br>
          <label for="qualification">Qualification</label>
          <input type="text" name="qualification" value="<?= $value['qualification']?>"><br>
          <label for="experience">Experience</label>
          <input type="text" name="experience" value="<?= $value['experience']?>"><br>
          <label for="joining_date">Joining Date</label>
          <input type="text" name="joining_date" value="<?= $value['joining_date']?>"><br>
          <label for="subject_specialization">Subject Specialization</label>
          <input type="text" name="subject_specialization" value="<?= $value['subject_specialization']?>"><br>

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

<?php
foreach ($result as $key => $value) {
    $role_id = $value['role_id'];
    $roleQuery = "SELECT role FROM roles WHERE role_id = :role_id";
    $roleStatement = $pdo->prepare($roleQuery);
    $roleStatement->bindParam(":role_id", $role_id, PDO::PARAM_STR);
    $roleStatement->execute();
    $role_res = $roleStatement->fetch(PDO::FETCH_ASSOC);
?>
          <label for="role_id">Role</label>
          <input type="text" name="role_id" value="<?= $role_res['role']?? ''?>" readonly><br>
          <?php } ?>

          <label for="salary">Salary</label>
          <input type="text" name="salary" value="<?= $value['salary']?>"><br>
          <label for="notes">Notes</label>
          <input type="text" name="notes" value="<?= $value['notes']?>"><br>
          
          <?php } ?>
     </div>
</form>

<button class="btn btn-light" name="back"><a href="teacher_index.php">Back</a></button>

<?php
require "component/footer.php";

?>