<?php
require "server/db.php";
require "component/header.php";
$errors = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT ct.*, c.course_id, c.course_name, t.teacher_id, t.firstname, t.lastname
            FROM course_teacher ct
            JOIN courses c ON ct.course_id = c.course_id
            JOIN teacher t ON ct.teacher_id = t.teacher_id
            WHERE ct.course_teacher_id = :course_teacher_id";

    $statement = $pdo->prepare($sql);
    $statement->bindParam(":course_teacher_id", $id, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo "Record not found.";
        exit();
    }
}

// Fetch all courses for the select options
$course_query = "SELECT course_id, course_name FROM courses";
$course_statement = $pdo->query($course_query);
$courses = $course_statement->fetchAll(PDO::FETCH_ASSOC);

$teacher_query = "SELECT teacher_id, firstname, lastname FROM teacher";
$teacher_stmt = $pdo->query($teacher_query);
$teachers = $teacher_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<form action="course_teacher_detail.php" method="POST" enctype="multipart/form-data">
    <div class="container shadow shadow">
        <label for="course_teacher_id"> ID</label>
        <input type="text" name="course_teacher_id" value="<?= $result['course_teacher_id'] ?>" readonly><br>
        <label for="course_id">Course ID</label>
        <input type="text" name="course_id" id="course_id" value="<?= $result['course_id'] ?>" readonly><br>

        <label for="course_name">Course Name</label>
        <select name="course_name" id="course_name" onchange="updateCourseId()">
            <?php foreach ($courses as $course) { ?>
                <option value="<?= $course['course_name'] ?>" <?= ($course['course_name'] == $result['course_name']) ? 'selected' : '' ?>>
                    <?= $course['course_name'] ?>
                </option>
            <?php } ?>
        </select><br>

        <label for="teacher_id">Lecturer</label>
<select name="teacher_id" id="teacher_id">
    <?php foreach ($teachers as $teacher) { ?>
        <option value="<?= $teacher['teacher_id'] ?>" <?= ($teacher['teacher_id'] == $result['teacher_id']) ? 'selected' : '' ?>>
            <?= $teacher['firstname'] . ' ' . $teacher['lastname'] ?>
        </option>
    <?php } ?>
</select><br>

<button class="btn btn-light" name="back"><a href="course_teacher_index.php">Back</a></button>
<!-- <a href="add_schedule.php?course_teacher_id=<?= $id ?>&course_id=<?= $course['course_id'] ?>&teacher_id=<?= $teacher['teacher_id'] ?>" class="btn btn-secondary">Assign Schedule</a> -->
<a href="add_schedule.php?course_teacher_id=<?= $id ?>&course_id=<?= $result['course_id'] ?>&teacher_id=<?= $result['teacher_id'] ?>" class="btn btn-secondary">Assign Schedule</a>

    </div>
</form>

<script>
    function updateCourseId() {
        // Get the selected option value
        var selectedCourse = document.getElementById("course_name").value;

        // Find the corresponding course_id and update the course_id input
        var courses = <?php echo json_encode($courses); ?>;
        for (var i = 0; i < courses.length; i++) {
            if (courses[i].course_name === selectedCourse) {
                document.getElementById("course_id").value = courses[i].course_id;
                break;
            }
        }
    }
</script>

<?php
require "component/footer.php";
?>
