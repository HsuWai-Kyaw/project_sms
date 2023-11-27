<?php
require "server/db.php";
require "component/header.php";
$errors = [];

if (isset($_GET['id'])) {

     $id = $_GET['id'];
}


$sql = "SELECT * FROM roles WHERE role_id = :role_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":role_id", $id, PDO::PARAM_STR);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
// print_r($result);
// die();

?>

<form action="role_detail.php" method="POST" enctype="multipart/form-data">
     <div class="container shadow shadow">
            <?php
               foreach ($result as $key => $value) { 
            ?>
          <label for="role_id">Role ID</label>
          <input type="text" name="role_id" value="<?= $value['role_id'] ?>"> <br>
          <label for="role">Role</label>
          <input type="text" name="role" value="<?= $value['role'] ?>"><br>
          <label for="description">Description</label>
          <input type="text" name="description" value="<?= $value['description'] ?>"><br>

          <?php
$selectedRoleId = $_GET['id']; // Adjust this based on how you get the selected role ID

// Fetch the permissions for the selected role
$permissionsQuery = "SELECT permission FROM roles WHERE role_id = :role_id";
$permissionsStmt = $pdo->prepare($permissionsQuery);
$permissionsStmt->bindParam(":role_id", $selectedRoleId, PDO::PARAM_INT);
$permissionsStmt->execute();
$permissionsResult = $permissionsStmt->fetch(PDO::FETCH_ASSOC);

// Extract the permissions into an array
$permissions = explode(', ', $permissionsResult['permission']);

?>
          <label for="permissions">Permission</label>
<input type="checkbox" name="permissions[]" value="read" <?= isset($permissions) && in_array('read', $permissions) ? 'checked' : '' ?>> Read
<input type="checkbox" name="permissions[]" value="write" <?= isset($permissions) && in_array('write', $permissions) ? 'checked' : '' ?>> Write
<input type="checkbox" name="permissions[]" value="update" <?= isset($permissions) && in_array('update', $permissions) ? 'checked' : '' ?>> Update
<input type="checkbox" name="permissions[]" value="delete" <?= isset($permissions) && in_array('delete', $permissions) ? 'checked' : '' ?>> Delete
<input type="checkbox" name="permissions[]" value="manage_users" <?= isset($permissions) && in_array('manage_users', $permissions) ? 'checked' : '' ?>> Manage User
<input type="checkbox" name="permissions[]" value="manage_courses" <?= isset($permissions) && in_array('manage_courses', $permissions) ? 'checked' : '' ?>> Manage Course


<br>
<label for="status">Status</label>
<select name="status" required>
    <option value="Active" <?= ($value['status'] ?? '') === 'Active' ? 'selected' : '' ?>>Active</option>
    <option value="Inactive" <?= ($value['status'] ?? '') === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
    <option value="Pending" <?= ($value['status'] ?? '') === 'Pending' ? 'selected' : '' ?>>Pending</option>
</select><br>
          <label for="skills">Skills</label>
          <input type="text" name="skills" value="<?= $value['skills'] ?? '' ?>"><br>
          <label for="start_date">Start Date</label>
          <input type="date" name="start_date" value="<?= $value['start_date'] ?? '' ?>"><br>
          <label for="end_date">End Date</label>
          <input type="date" name="end_date" value="<?= $value['end_date'] ?? '' ?>"><br>

          
          <?php } ?>
     </div>
</form>

<button class="btn btn-light" name="back"><a href="role_index.php">Back</a></button>

<?php
require "component/footer.php";

?>