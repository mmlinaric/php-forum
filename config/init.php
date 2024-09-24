<?php

require_once "db.php";

session_start();

// Logout user if their account is not found in the database
// This could occur if their account is deleted while they are logged in
if (isset($_SESSION["user_id"]))
{
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = :id");
    $stmt->execute(array(
        ':id' => $_SESSION["user_id"]
    ));

    if ($stmt->rowCount() == 0)
    {
        // Empty $_SESSION to clear user_id from it
        $_SESSION = array();
    }
    else
    {
        // Create a global user value for displaying their name/email later
        global $_USER;
        $_USER = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}