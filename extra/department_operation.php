<?php
require_once('server/db.php');
$errors = [];


if (isset($_GET['id'])) {
     $id = $_GET['id'];
     // $id = $_POST['student_id'];
     $select = "SELECT * FROM department WHERE department_id=:department_id";
     $selectstatement = $pdo->prepare($select);
     $selectstatement->bindParam(':department_id', $id, PDO::PARAM_STR);
     $select_res = $selectstatement->execute();
     $select_res = $selectstatement->fetch();
     // print_r($select_res);
     // die();

     if ($select_res) {
          $res = [
               'status' => 200,
               'message' => 'Data Fetch Successfully',
               'data' => $select_res
          ];
          echo json_encode($res);
          return;
     } else {
          $res = [
               'status' => 404,
               'message' => 'ID not found!'
          ];
          echo json_encode($res);
          return;
     }
}
if (isset($_POST['add_department'])) {
    $department = $_POST['department'];
    $head_of_department = $_POST['head_of_department'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $start_date = $_POST['start_date'];

     if ($department == NULL || $head_of_department == NULL || $description == NULL || $location == NULL || $contact_number == NULL || $email == NULL || $start_date == NULL) {
          $res = [
               'status' => 422,
               'message' => 'All fields are necessary'
          ];
          echo json_encode($res);
          return;
     }

     $insertqry = "INSERT INTO `department`(`department`, `head_of_department`, `description`, `location`, `contact_number`, `email`, `start_date`) VALUES (:department_id,:department,:head_of_department,:description,:location,:contact_number,:email,:start_date)";
     $adddepartmentstatement = $pdo->prepare($insertqry);
          $adddepartmentstatement->bindParam(":department", $department, PDO::PARAM_STR);
          $adddepartmentstatement->bindParam(":head_of_department", $head_of_department, PDO::PARAM_STR);
          $adddepartmentstatement->bindParam(":description", $description, PDO::PARAM_STR);
          $adddepartmentstatement->bindParam(":location", $location, PDO::PARAM_STR);
          $adddepartmentstatement->bindParam(":contact_number", $contact_number, PDO::PARAM_STR);
          $adddepartmentstatement->bindParam(":email", $email, PDO::PARAM_STR);
          $adddepartmentstatement->bindParam(":start_date", $start_date, PDO::PARAM_STR);
          
          $add = $adddepartmentstatement->execute();


     if ($add) {
          $res = [
               'status' => 200,
               'message' => 'Record added Successfully'
          ];
          echo json_encode($res);
          return;
     } else {
          $res = [
               'status' => 500,
               'message' => 'New Record Not Created'
          ];
          echo json_encode($res);
          return;
     }
}


if (isset($_POST['update_department'])) {

     $id = $_POST['department_id'];
     // $education_s_year = 2020;
     // $education_s_month = 4;
     // $education_e_year = 2023;
     // $education_e_month = 8;
     // $school_name = "ABC";
     // $specialized_subject = "Sleep224";
     // $skills = " TEa";
     $department = $_POST['department'] ?? null;
     $head_of_department = $_POST['head_of_department'] ?? null;
     $description = $_POST['description'] ?? null;
     $location = $_POST['location'] ?? null;
     $contact_number = $_POST['contact_number'] ?? null;
     $email = $_POST['email'] ?? null;
     $start_date = $_POST['start_date'] ?? null;
    
     if ($department == NULL || $head_of_department == NULL || $description == NULL || $location == NULL || $contact_number == NULL || $email == NULL || $start_date == NULL) {
          $res = [
               'status' => 422,
               'message' => 'All fields are necessary'
          ];
          echo json_encode($res);
          return;
     }
     $updateqry = "UPDATE `department` SET `department`=:department,`head_of_department`=:head_of_department,`description`=:description,`location`=:location,`contact_number`=:contact_number,`email`=:email`,`start_date`=:start_date WHERE `department_id`=:department_id";
     $updatedepartmentstatement = $pdo->prepare($updateqry);
     $updatedepartmentstatement->bindParam(':department_id', $select_resid, PDO::PARAM_STR);
     $updatedepartmentstatement->bindParam(":department", $department, PDO::PARAM_STR);
     $updatedepartmentstatement->bindParam(":head_of_department", $head_of_department, PDO::PARAM_STR);
     $updatedepartmentstatement->bindParam(":description", $description, PDO::PARAM_STR);
     $updatedepartmentstatement->bindParam(":location", $location, PDO::PARAM_STR);
     $updatedepartmentstatement->bindParam(":contact_number", $contact_number, PDO::PARAM_STR);
     $updatedepartmentstatement->bindParam(":email", $email, PDO::PARAM_STR);
     $updatedepartmentstatement->bindParam(":start_date", $start_date, PDO::PARAM_STR);

     $updates = $updatedepartmentstatement->execute();


     if ($updates) {
          $res = [
               'status' => 200,
               'message' => 'Updated Successfully 58477777777777777'
          ];
          echo json_encode($res);
          return;
     } else {
          $res = [
               'status' => 500,
               'message' => 'Not Updated'
          ];
          echo json_encode($res);
          return;
     }
}

if (isset($_POST['DepartmentDelete'])) {
     $id = $_POST['department_id'];

     $deleteqry = "DELETE FROM department WHERE department_id = :department_id";

     $deletestmt = $pdo->prepare($deleteqry);
     $deletestmt->bindParam(':department_id', $id, PDO::PARAM_STR);
     $delete = $deletestmt->execute();

     if ($delete) {
          $res = [
               'status' => 200,
               'message' => 'Department Data Deleted.'
          ];
          echo json_encode($res);
          return;
     } else {
          $res = [
               'status' => 500,
               'message' => 'Deletion Failed.'
          ];
          echo json_encode($res);
          return;
     }
}
