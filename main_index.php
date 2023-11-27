<?php
// Start or resume a session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['student_id'])) {
    $username = $_SESSION['username'];
    // Redirect to the dashboard or home page if the user is already logged in
    header("Location: dashboard.php");
    exit();
}

require "server/db.php";
require "component/header.php";

$errors = [];
?>

<h1>Welcome to Our Website</h1>
<p>This is the index page. Choose an option below:</p>

<div>
    <h2>Register</h2>
    <p>If you don't have an account, you can register here:</p>
    <form action="add_student.php" method="GET">
        <button type="submit">Register</button>
    </form>
</div>

<div>
    <h2>Login</h2>
    <p>If you already have an account, you can log in here:</p>
    <form action="login.php" method="GET">
        <button type="submit">Login</button>
    </form>
</div>

<?php 
require "component/footer.php";
?>
