<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "forum";

try {
    global $pdo;
    $pdo = new PDO("mysql:host=$server;dbname=".$database, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed");
}