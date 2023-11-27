<?php
require "server/db.php";
$errors = [];
$sql = "SELECT * FROM department WHERE department_id = :department_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":department_id", $id, PDO::PARAM_STR);
$statement->execute();
$department = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Add Department -->
<div class="modal fade" id="DepartmentAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog">
          <div class="modal-content">
               <div class="modal-header">
                    <h1 class="modal-title fs-5">New Record</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <form id="DepartmentAdd">
                    <div class="modal-body">
                         <div id="errorMessage" class="alert alert-warning d-none"></div>

                         <!-- <input type="text" name="id" value="<?= $id ?>" class="form-control" readonly> -->

                         <!-- <div class="mb-3">
                         <label for="department_id">Department ID</label>
                         <input type="text" name="department_id" class="form-control" required> 
                         </div> -->

                         <div class="mb-3">
                         <label for="department">Department</label>
                         <input type="text" name="department" class="form-control" required> 
                         </div>

                         <div class="mb-3">
                         <label for="head_of_department">Head of Department</label>
                         <input type="text" name="head_of_department" class="form-control" required>
                         </div>

                         <div class="mb-3">
                         <label for="description">Description</label>
                         <input type="text" name="description" class="form-control" required>
                         </div>

                         <div class="mb-3">
                         <label for="location">Location</label>
                         <input type="text" name="location" class="form-control" required>
                         </div>

                         <div class="mb-3">
                         <label for="contact_number">Contact Number</label>
                         <input type="phone" name="contact_number" class="form-control" required>
                         </div>

                         <div class="mb-3">
                         <label for="email">email</label>
                         <input type="email" name="email" class="form-control" required>
                         </div>

                         <div class="mb-3">
                         <label for="start_date">Start Date</label>
                         <input type="date" name="start_date" class="form-control" required>
                         </div>
                    </div>
                    <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                         <button type="submit" name="submit" class="btn btn-primary">Add</button>
                    </div>
               </form>
          </div>
     </div>
</div>


<!-- Edit -->
<div class="modal fade" id="DepartmentEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog">
          <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <form id="DepartmentUpdate">
                    <div class="modal-body">
                         <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                         <input type="text" name="department_id" id="department_id" readonly>

                         <div class="mb-3">
                         <label for="department">Department</label>
                         <input type="text" name="department" id="department" class="form-control" required> 
                         </div>

                         <div class="mb-3">
                         <label for="head_of_department">Head of Department</label>
                         <input type="text" name="head_of_department" id="head_of_department" class="form-control" required>
                         </div>

                         <div class="mb-3">
                         <label for="description">Description</label>
                         <input type="text" name="description" id="description" class="form-control" required>
                         </div>

                         <div class="mb-3">
                         <label for="location">Location</label>
                         <input type="text" name="location" id="location" class="form-control" required>
                         </div>

                         <div class="mb-3">
                         <label for="contact_number">Contact Number</label>
                         <input type="phone" name="contact_number" id="contact_number" class="form-control" required>
                         </div>

                         <div class="mb-3">
                         <label for="email">email</label>
                         <input type="email" name="email" id="email" class="form-control" required>
                         </div>

                         <div class="mb-3">
                         <label for="start_date">Start Date</label>
                         <input type="date" name="start_date" id="start_date" class="form-control" required>
                         </div>
                    </div>
                    <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                         <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </div>
               </form>
          </div>
     </div>
</div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

<script>
$(document).on('submit', '#DepartmentAdd', function(e) {
     e.preventDefault();
     // alert('G');
     var formData = new FormData(this)
     // alert(formData);
     formData.append('add_department', true);
     location.reload();
     // console.log('formData :>> ', formData);

     $.ajax({
          type: "POST",
          url: "department_operation.php",
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
               var res = jQuery.parseJSON(response);
               if (res.status == 422) {
                    $('#errorMessage').removeClass('d-none');
                    $('#errorMessage').text(res.message);
               } else if (res.status == 200) {

                    $('#errorMessage').addClass('d-none');
                    $('#DepartmentAddModal').modal('hide');
                    $('#DepartmentAdd')[0].reset();

                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(res.message);

                    $('#DepartmentTbl').load(location.href + " #DepartmentTbl");

               } else if (res.status == 500) {
                    alert(res.message);
               }
          }
     });
});
$(document).on('click', '.DepartmentEdit', function() {

     var department_id = $(this).val();
     // alert(department_id);
     $.ajax({
          type: "GET",
          url: "department_operation.php?id=" + department_id,
          success: function(response) {
               // console.log('====================================');
               // console.log(response);
               // console.log('====================================');
               // var res = jQuery.parseJSON('{ "name": "John" }');
               var res = jQuery.parseJSON(response);
               // console.log(res);

               if (res.status == 404) {
                    alert(res.message);
               } else if (res.status === 200) {
                    // $('#department_id').val(res.data.id);
                    //$('#department_id').val(res.data.department_id);
                    $('#department_id').val(res.data.department_id);
                    $('#department').val(res.data.department);
                    $('#head_of_department').val(res.data.head_of_department);
                    $('#description').val(res.data.description);
                    $('#location').val(res.data.location);
                    $('#contact_number').val(res.data.contact_number);
                    $('#email').val(res.data.email);

                    $('#DepartmentEditModal').modal('show');
               }
          }
     });
});

$(document).on('submit', '#DepartmentUpdate', function(e) {
     e.preventDefault();

     var formData = new FormData(this);
     // const values = [...formData.entries()];
     // console.log(values);

     formData.append("update_department", true);

     $.ajax({
          type: "POST",
          url: "department_operation.php",
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
               console.log('====================================');
               console.log(response);
               console.log('====================================');
               var res = jQuery.parseJSON(response);

               if (res.status == 422) {
                    $('#errorMessageUpdate').removeClass('d-none');
                    $('#errorMessageUpdate').text(res.message);

               } else if (res.status == 200) {

                    $('#errorMessageUpdate').addClass('d-none');

                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(res.message);

                    $('#DepartmentEditModal').modal('hide');
                    $('#DepartmentUpdate')[0].reset();


                    $('#DepartmentTbl').load(location.href + " #DepartmentTbl");
                    window.location.reload();

               } else if (res.status == 500) {
                    alert(res.message);
               }
          }
     });

});

$(document).on('click', '.DepartmentDelete', function(e) {
     e.preventDefault();
     if (confirm('Sure?')) {
          var department_id = $(this).val();

          $.ajax({
               type: "POST", // Change to POST to send data to the server
               url: "department_operation.php", // Change to the URL where your PHP code resides
               data: {
                    DepartmentDelete: true,
                    department_id: department_id
               }, // Send the data as an object
               success: function(response) {
                    try {
                         var result = JSON.parse(response);
                         if (result.status === 200) {
                              // Data was successfully deleted
                              // You can refresh the page or update the UI as needed
                              location.reload(); // For example, refresh the page
                         } else {
                              // Handle the case where deletion was not successful
                              console.error("Deletion failed: " + result.message);
                         }
                    } catch (error) {
                         console.error("Error parsing JSON response: " + error);
                    }
               },
               error: function(xhr, status, error) {
                    console.error("Error: " + xhr.responseText);
               }
          });
     }
});
</script>