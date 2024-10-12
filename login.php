<?php
session_start();

// Establishing connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['login'])) {
        // Retrieving username and password from the form
        $username = $_POST['username'];
        $password = $_POST['password'];

        // SQL query to check if the username and password match
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // User is authenticated
            $_SESSION['username'] = $username;
            header("Location: welcome.php"); // Redirect to welcome page after successful login
        } else {
            $login_error = "Invalid username or password";
        }
    } elseif (isset($_POST['forget_password'])) {
        // Forget Password functionality
        $email = $_POST['email'];

        // Check if email exists in the database
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Email exists, send reset password instructions
            // Code for sending email or reset password instructions here
            $reset_password_message = "Reset password instructions sent to your email.";
        } else {
            // Email does not exist
            $reset_password_error = "Email not found.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" name="login" value="Login">
    </form>

    <h2>Forget Password</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <input type="submit" name="forget_password" value="Forget Password">
    </form>

    <?php
    if(isset($login_error)) {
        echo "<p>$login_error</p>";
    }
    if(isset($reset_password_error)) {
        echo "<p>$reset_password_error</p>";
    }
    if(isset($reset_password_message)) {
        echo "<p>$reset_password_message</p>";
    }
    ?>
</body>
</html>
