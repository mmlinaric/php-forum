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
    <ul class="nav">
        <li class="left"><a href="index.php">PHP Forum</a></li>
        <?php if (isset($_USER)) { ?>
            <li><a href="#"><?php echo $_USER["username"]; ?></a></li>
            <li><a href="add-post.php">New post</a></li>
            <li><a href="logout.php">Log out</a></li>
        <?php } else { ?>
            <li><a href="login.php">Log in</a></li>
            <li><a href="register.php">Register</a></li>
        <?php } ?>
    </ul>

    <div class="container">