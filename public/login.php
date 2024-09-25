<?php

require_once("../config/init.php");
include("../tpl/header.php");

if (isset($_POST["login"]))
{
    $username_email = $_POST["username_email"];
    $password = $_POST["password"];

    if(empty($username_email) || empty($password))
    {
        $_SESSION["error"] = "You have to enter all inputs.";
        header("Location: login.php");
        die();
    }

    // Add user to the database
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = :email OR username = :username");
    try {
        $stmt->execute(array(
            ':email' => $username_email,
            ':username' => $username_email,
        ));
    } catch (PDOException $e) {
        $_SESSION["error"] = "An error occurred while trying to login.";
        header("Location: login.php");
        die();
    }

    if ($stmt->rowCount() == 0)
    {
        $_SESSION["error"] = "Invalid username or email.";
        header("Location: login.php");
        die();
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($password, $user["password"]))
    {
        $_SESSION["error"] = "Invalid password.";
        header("Location: login.php");
        die();
    }

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["info"] = "You have successfully logged in.";
    header("Location: index.php");
    die();
}

?>

<form method="POST">
    <label for="username_email">Username or email:</label>
    <input type="text" id="username_email" name="username_email" placeholder="Username or email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Password" required>

    <input type="submit" name="login" value="Login">
</form>

<?php include("../tpl/footer.php"); ?>