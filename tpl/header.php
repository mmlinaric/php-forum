<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - PHP Forum</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php if (isset($_SESSION["error"])) { ?>
        <p class="message error"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
    <?php } ?>
    <?php if (isset($_SESSION["info"])) { ?>
        <p class="message info"><?php echo $_SESSION["info"]; unset($_SESSION["info"]); ?></p>
    <?php } ?>

    <!-- NAVIGATION -->
    <?php if (isset($_USER)) { ?>
        <p>Welcome, <?php echo $_USER["username"]; ?></p>
        <a href="logout.php">Log out</a>
    <?php } else { ?>
        <a href="login.php">Log in</a>
        <a href="register.php">Register</a>
    <?php } ?>

    <h1><a href="index.php">PHP Forum</a></h1>