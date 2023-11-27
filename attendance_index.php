<?php
// get_courses.php
require "server/db.php";
require "component/header.php";

$sql = "SELECT course_id, course_name FROM courses";
$statement = $pdo->prepare($sql);
$statement->execute();
$courses = $statement->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($courses);


$courseId = $_GET['courseId'] ?? '';

if ($courseId) {
    $sql = "SELECT s.student_name
            FROM student s
            JOIN attendance a ON s.student_id = a.student_id
            WHERE a.course_id = :courseId";

    $statement = $pdo->prepare($sql);
    $statement->bindParam(":courseId", $courseId, PDO::PARAM_STR);
    $statement->execute();
    $attendance = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($attendance);
} else {
    echo json_encode([]);
}
?>
<!-- index.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
</head>
<body>
    <h1>View Attendance</h1>
    <form id="attendanceForm">
        <label for="courseId">Select Course:</label>
        <select id="courseId" name="courseId">
        <?php foreach ($courses as $course) { ?>
                <option value="<?= $course['course_name'] ?>">
                    <?= $course['course_name'] ?>
                </option>
            <?php } ?>
        </select>
        <button type="button" onclick="getAttendance()">View Attendance</button>
    </form>
    <div id="attendanceResult"></div>

    <script src="script.js"></script>
    <script>
     // script.js
document.addEventListener('DOMContentLoaded', function () {
    // Fetch courses and populate the dropdown
    fetch('course_index.php')
        .then(response => response.json())
        .then(data => {
            const courseIdSelect = document.getElementById('courseId');
            data.forEach(course => {
                const option = document.createElement('option');
                option.value = course.course_id;
                option.textContent = course.course_name;
                courseIdSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));
});

function getAttendance() {
    const courseId = document.getElementById('courseId').value;

    // Fetch attendance for the selected course
    fetch(`attendance_index.php?courseId=${courseId}`)
        .then(response => response.json())
        .then(data => {
            const attendanceResultDiv = document.getElementById('attendanceResult');
            attendanceResultDiv.innerHTML = '<h2>Attendance List</h2>';

            if (data.length === 0) {
                attendanceResultDiv.innerHTML += '<p>No attendance records found for the selected course.</p>';
            } else {
                const ul = document.createElement('ul');
                data.forEach(student => {
                    const li = document.createElement('li');
                    li.textContent = student.student_name;
                    ul.appendChild(li);
                });
                attendanceResultDiv.appendChild(ul);
            }
        })
        .catch(error => console.error('Error:', error));
}

    </script>
</body>
</html>
