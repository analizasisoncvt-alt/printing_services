<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "printing_db";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=3307", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>