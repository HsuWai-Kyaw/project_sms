<?php
require "server/db.php";
require "component/header.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['submit'])) {
    $role = $_POST['role'];
    $description = $_POST['description'];
    $permission = $_POST['permissions'] ?? [];
    $status = $_POST['status'];
    $skills = $_POST['skills'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $permission = implode(', ', $permission);

    $addroom = "INSERT INTO `roles`(`role`, `description`, `permission`, `status`, `skills`, `start_date`, `end_date`) VALUES (:role,:description,:permission,:status,:skills,:start_date,:end_date)";

    $addroomstatement = $pdo->prepare($addroom);
    $addroomstatement->bindParam(":role", $role, PDO::PARAM_STR);
    $addroomstatement->bindParam(":description", $description, PDO::PARAM_STR);
    $addroomstatement->bindParam(":permission", $permission, PDO::PARAM_STR);
    $addroomstatement->bindParam(":status", $status, PDO::PARAM_STR);
    $addroomstatement->bindParam(":skills", $skills, PDO::PARAM_STR);
    $addroomstatement->bindParam(":start_date", $start_date, PDO::PARAM_STR);
    $addroomstatement->bindParam(":end_date", $end_date, PDO::PARAM_STR);

    $res = $addroomstatement->execute();

    if ($res) {
        header("location: role_index.php");
        exit();
    } else {
        $errors[] = "Error occurred while storing data";
    }
}
?>

<form action="add_role.php" method="POST" enctype="multipart/form-data">
    <div class="container shadow shadow">
        <label for="role">Role</label>
        <input type="text" name="role" required><br>
        <label for="description">Description</label>
        <input type="text" name="description" required><br>
        
        <label for="permissions">Permission:</label>

       
        <input type="checkbox" name="permissions[]" value="read"> Read
        <input type="checkbox" name="permissions[]" value="write"> Write
        <input type="checkbox" name="permissions[]" value="update"> Update
        <input type="checkbox" name="permissions[]" value="delete"> Delete
        <input type="checkbox" name="permissions[]" value="manage_users"> Manage User
        <input type="checkbox" name="permissions[]" value="manage_courses"> Manage Courses
        
        <br>
        <label for="status">Status</label>
        <select name="status" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
            <option value="Pending">Pending</option>
        </select><br>
        <label for="skills">skills</label>
        <input type="text" name="skills" required><br>
        <label for="start_date">start_date</label>
        <input type="date" name="start_date" required><br>
        <label for="end_date">end_date</label>
        <input type="date" name="end_date"><br>
        
        <input type="submit" value="Submit" name="submit" class="btn btn-info">
    </div>
</form>

<?php
require "component/footer.php";
?>
