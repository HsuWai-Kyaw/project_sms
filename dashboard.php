<?php
session_start();
require "server/db.php";
require "component/header.php";

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve the logged-in student's username
$student_id = $_SESSION['student_id'];
$sql = "SELECT username FROM student WHERE student_id = :student_id";
$statement = $pdo->prepare($sql);
$statement->bindParam(":student_id", $student_id, PDO::PARAM_STR);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Check if the user exists (this check is important for safety)
if (!$user) {
    // Redirect to the login page if the user doesn't exist
    header("Location: login.php");
    exit();
}

// Display the courses on the dashboard

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
$course_query = "SELECT * FROM courses";
if (!empty($search_query)) {
     $course_query .= " WHERE course LIKE :search_query OR head_of_course LIKE :search_query";
}

$course_query .= " LIMIT :start_page, :record_per_page";

$s = $pdo->prepare($course_query);

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

    <h1>Welcome to the Dashboard, <?php echo $user['username']; ?>!</h1>
    <?php echo $student_id; ?>
    <!-- Other content of your dashboard goes here -->

    <form action="logout.php" method="GET">
        <button type="submit">Logout</button>
    </form>


    <?php foreach ($results as $key => $value) { ?>
    <div class="card" style="width: 18rem;">
  <img class="card-img-top" src=".../100px180/" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title"><?= $value['course_id'] ?></h5>
    <hr>
    <h5 class="card-title"><?= $value['course_name'] ?></h5>

    <p class="card-text"><?= $value['description'] ?></p>
    <a href="course_detail.php?course_id=<?= $value['course_id'] ?>" class="btn btn-primary">View Detail</a>
  </div>
</div>

<?php  }?>

<?php
require "component/footer.php";
?>
