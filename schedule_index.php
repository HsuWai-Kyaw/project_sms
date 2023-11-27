<?php
require "server/db.php";
require "component/header.php";

$record_per_page = 20; // Number of items to display per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start_page = ($page - 1) * $record_per_page;

// Initialize the search query variable
$search_query = '';

// Check if search query is provided
if (isset($_GET['q'])) {
     $search_query = trim($_GET['q']);
}

// Modify the query to include the search condition if search query is provided
$schedule_query = "SELECT s.*, ct.*, c.course_name, t.firstname, t.lastname, r.room_number
                   FROM schedule s
                   JOIN course_teacher ct ON s.course_teacher_id = ct.course_teacher_id
                   JOIN courses c ON ct.course_id = c.course_id
                   JOIN teacher t ON ct.teacher_id = t.teacher_id
                   JOIN room r ON s.room_id = r.room_id";

if (!empty($search_query)) {
    $schedule_query .= " WHERE s.schedule_id LIKE :search_query OR s.head_of_schedule LIKE :search_query
                        OR ct.course_name LIKE :search_query OR t.firstname LIKE :search_query OR t.lastname LIKE :search_query
                        OR r.room_number LIKE :search_query";
}

$schedule_query .= " LIMIT :start_page, :record_per_page";

$s = $pdo->prepare($schedule_query);

// Bind search query parameter if it's provided
if (!empty($search_query)) {
    $search_param = "%$search_query%";
    $s->bindParam(':search_query', $search_param, PDO::PARAM_STR);
}

$s->bindParam(":start_page", $start_page, PDO::PARAM_INT);
$s->bindParam(":record_per_page", $record_per_page, PDO::PARAM_INT);
$s->execute();
$results = $s->fetchAll(PDO::FETCH_ASSOC);
               
?>
<div class="header w-auto shadow m-auto p-3">
     <nav class="navbar navbar-expand-lg bg-body-tertiary">
          <div class="container-fluid">
               <a class="navbar-brand" href="schedule_index.php">schedule List</a>
               <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                         <li class="nav-item">
                              <a class="nav-link active" aria-current="page" href="schedule_index.php">Home</a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="course_teacher_index.php">Add New +</a>
                         </li>

                    </ul>
                    <form action="" method="GET">
                         <input type="text" name="q" value="<?= htmlentities($search_query) ?>" placeholder="Search by name, kana name, or passport">
                         <input type="submit" value="Search" class="btn btn-success">
                    </form>
               </div>
          </div>
     </nav>
</div>

<div class="col-10">
     <table class="table m-3 align-middle mb-0 bg-white">
          <thead>
               <tr>

                    <th scope="col">No</th>
                    <th scope="col">schedule ID</th>
                    <th scope="col">Course</th>
                    <th scope="col">Lecturer</th>
                    <th scope="col">Room Number</th>
                    <th scope="col">Day of Week</th>
                    <th scope="col">Start Time</th>
                    <th scope="col">End Time</th>
                    <th scope="col">Notes</th>
                    <th scope="col" colspan="2">Action</th>
               </tr>
          </thead>
          
          <tbody>

               <?php foreach ($results as $key => $value) { ?>

                    <!-- print_r($value['name']); -->

                    <th><?= ++$key ?></th>
                    <td scope="row"><?= $value['schedule_id'] ?></td>
                    <td scope="row"><?= $value['course_name'] ?></td>
<td scope="row"><?= $value['firstname'] . " " . $value['lastname'] ?></td>
<td scope="row"><?= $value['room_number'] ?></td>

                    <td scope="row"><?= $value['day_of_week'] ?></td>
                    <td scope="row"><?= $value['start_time'] ?></td>
                    <td scope="row"><?= $value['end_time'] ?></td>
                    <td scope="row"><?= $value['notes'] ?></td>
                    <td scope="row"><a href="schedule_detail.php?id=<?= $value['schedule_id'] ?>" class="btn btn-sm btn-info">Detail</a>
                    </td>
                    <td scope="row"> <a href="edit_schedule.php?id=<?= $value['schedule_id'] ?>" class="btn btn-sm btn-warning">Edit</a>

                    </td>
                    <td scope="row"><a href="delete.php?id=<?= $value['schedule_id']; ?>" class="btn btn-sm btn-danger" onclick="alert('are you sure?')">Delete</a>

                    </td>

                    </tr>
               <?php  } ?>

          </tbody>
     </table>

</div>
<div class="pagination m-auto" style="width: fit-content;">
     <?php
     // Count total records for pagination
     $page_qry = "SELECT * FROM schedule";
     if (!empty($search_query)) {
          $page_qry .= " WHERE schedule LIKE :search_query OR location LIKE :search_query";
     }

     $page_res = $pdo->prepare($page_qry);

     // Bind search query parameter if it's provided
     if (!empty($search_query)) {
          $page_res->bindParam(':search_query', $search_param, PDO::PARAM_STR);
     }

     $page_res->execute();
     $total_records = $page_res->rowCount();

     $total_pages = ceil($total_records / $record_per_page);
     echo '<div>';
     if ($page > 1) {
          echo '<a href="?page=' . ($page - 1) . '&q=' . urlencode($search_query) . '">Previous</a> ';
     }

     for ($i = 1; $i <= $total_pages; $i++) {
          if ($i === $page) {
               echo '<span>' . $i . '</span> ';
          } else {
               echo '<a href="?page=' . $i . '&q=' . urlencode($search_query) . '">' . $i . '</a> ';
          }
     }

     if ($page < $total_pages) {
          echo '<a href="?page=' . ($page + 1) . '&q=' . urlencode($search_query) . '">Next</a>';
     }
     echo '</div>';
     ?>
</div>

<?php
require "component/footer.php";
?>