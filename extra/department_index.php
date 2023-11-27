<?php
require "server/db.php";
$errors = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM department WHERE department_id = :department_id";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(":department_id", $id, PDO::PARAM_STR);
    $statement->execute();
    $department = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    // If $_GET['id'] is not set, fetch all department records
    $sql = "SELECT * FROM department";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $department = $statement->fetchAll(PDO::FETCH_ASSOC);
}

// Debugging: Print the data fetched from the database
// var_dump($department);
?>

<div class="container">
     <div class="row">
          <div class="col-md-12">
               <div class="card">
                    <div class="card-header">
                         <h4>Department

                              <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                                   data-bs-target="#DepartmentAddModal">
                                   Add +
                              </button>
                         </h4>
                    </div>

                    <table id="DepartmentTbl" class="table table-bordered table-striped">
                         <thead>
                              <tr>
                                   <td>Department ID</td>
                                   <td>Department</td>
                                   <td>Head of Department</td>
                                   <td>Description</td>
                                   <td>Location</td>
                                   <td>Contact</td>
                                   <td>Email</td>
                                   <td>Start Date</td>
                                   <td>Action</td>
                              </tr>

                         </thead>
                         <tbody>
                              <?php foreach ($department as $result) {
                                   // print_r($result);
                                //    die;

                              ?>

                              <tr>
                                   <td>
                                        <input type="text" name="department_id"
                                             value=" <?= $result['department_id'] ?? "" ?>" style="width: 50px;"
                                             readonly>
                                   </td>
                                   <td>
                                        <input type="text" name="department"
                                             value="<?= $result['department'] ?>" style="width: 50px;" readonly>
                                   </td>
                                   <td>
                                        <input type="text" name="head_of_department"
                                             value="<?= $result['head_of_department'] ?? "" ?>" style="width: 50px;"
                                             readonly>
                                   </td>
                                   <td>
                                        <input type="text" name="description"
                                             value="<?= $result['description'] ?? "" ?>" style="width: 50px;"
                                             readonly>    
                                   </td>
                                   <td>
                                        <input type="text" name="location" value="<?= $result['location'] ?? "" ?>"
                                             readonly>
                                   </td>
                                   <td>
                                        <input type="text" name="contact_number"
                                             value="<?= $result['contact_number'] ?? "" ?>" readonly>
                                   </td>
                                   <td>
                                        <input type="text" name="email" value="<?= $result['email'] ?? "" ?>" readonly>
                                   </td>
                                   <td>
                                        <input type="text" name="start_date" value="<?= $result['start_date'] ?? "" ?>" readonly></td>
                                    <td>
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#DepartmentEditModal"
                                             value="<?= $result['department_id']; ?>"
                                             class="DepartmentEdit btn btn-warning">Edit</button>
                                        <button type="button" value="<?= $result['department_id']; ?>"
                                             class="DepartmentDelete btn btn-danger">Delete</button>
                                   </td>
                              </tr>

                              <?php } ?>

                         </tbody>
                    </table>
               </div>
          </div>
     </div>
</div>
    <?php
require "./component/footer.php";
?>