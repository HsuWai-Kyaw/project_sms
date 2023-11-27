<?php
require "server/db.php";
require "component/header.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    // Get user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user data from the student table
    $sql = "SELECT * FROM student WHERE username = :username";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(":username", $username, PDO::PARAM_STR);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, start the session and redirect to a dashboard or home page
        session_start();
        $_SESSION['student_id'] = $user['student_id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $errors[] = "Invalid username or password. Please try again.";
    }
}
?>

<h1>Login</h1>
<form action="login.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br>

    <input type="submit" name="login" value="Login">
</form>
<p>Don't have an account? <a href="add_student.php">Register here</a>.</p>

<?php
// Display errors
foreach ($errors as $error) {
    echo "<p style='color: red;'>An error occurred. Please try again.</p>";
}
?>
<?php
require "component/footer.php";
?>
