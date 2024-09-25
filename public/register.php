<?php

require_once("../config/init.php");
include("../tpl/header.php");

if (isset($_POST["registration"]))
{
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password_repeat = $_POST["password_repeat"];

    if(empty($username) || empty($email) || empty($password) || empty($password_repeat))
    {
        $_SESSION["error"] = "You have to enter all inputs.";
        header("Location: register.php");
        die();
    }

    // Validate password
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password))
    {
        $_SESSION["error"] = "Password is not safe enough.";
        header("Location: register.php");
        die();
    }

    if (strlen($password) > 72) // Bcrypt is limited to 72 characters
    {
        $_SESSION["error"] = "Password is too long (max. 72 characters).";
        header("Location: register.php");
        die();
    }

    if ($password != $password_repeat)
    {
        $_SESSION["error"] = "Entered passwords are not the same.";
        header("Location: register.php");
        die();
    }

    // Validate username
    if (!preg_match('/^[a-z0-9]{5,20}$/', $username))
    {
        $_SESSION["error"] = "Username has to be 5-20 characters and can only contain lowercase letters and numbers.";
        header("Location: register.php");
        die();
    }

    $stmt = $pdo->prepare('SELECT username FROM users WHERE username = :username');
    $stmt->execute(array(
        ':username' => $username
    ));

    if ($stmt->rowCount() > 0)
    {
        $_SESSION["error"] = "This username already exists.";
        header("Location: register.php");
        die();
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $_SESSION["error"] = "This email is invalid.";
        header("Location: register.php");
        die();
    }

    $stmt = $pdo->prepare('SELECT email FROM users WHERE email = :email');
    $stmt->execute(array(
        ':email' => $email
    ));

    if ($stmt->rowCount() > 0)
    {
        $_SESSION["error"] = "This email already exists.";
        header("Location: register.php");
        die();
    }

    // Add user to the database
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    try {
        $stmt->execute(array(
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_BCRYPT)
        ));
    } catch (PDOException $e) {
        $_SESSION["error"] = "An error occurred while trying to register.";
        header("Location: register.php");
        die();
    }

    $_SESSION["info"] = "You have successfully registered. Please login.";
    header("Location: login.php");
    die();
}

?>

<form method="POST">
    <label for="username">Username (* length 5-20 characters, lowercase letters and numbers only):</label>
    <input type="text" id="username" name="username" placeholder="Username" minlength="5" maxlength="20" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Email" required>

    <label for="password">Password (* length 8-72 characters, minimum one number and special character):</label>
    <input type="password" id="password" name="password" placeholder="Password" minlength="8" maxlength="72" required>

    <label for="password_repeat">Repeat password:</label>
    <input type="password" id="password_repeat" name="password_repeat" minlength="8" maxlength="72" placeholder="Repeat password" required>

    <input type="submit" name="registration" value="Register">
</form>

<?php include("../tpl/footer.php"); ?>