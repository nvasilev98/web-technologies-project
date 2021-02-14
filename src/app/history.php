<?php
include 'header.php';
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
require_once "database/DBConnector.php";
$username = $_SESSION["username"];
$stmt = DBConnector::getInstance()::getConnection()->prepare("SELECT * FROM files where created_by = :username");
$stmt->bindParam(":username", $username, PDO::PARAM_STR);
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);
while ($r = $stmt->fetch()) {
    $filename = $r['file'];
    $user = $r['created_by'];
    $timestamp = $r['created_at'];
    $content = $r['content'];
    //to do: make in a table;
    echo "File: " . $filename . " Created by: " . $user . " Created at: " . $timestamp . "<br>";
    echo "<input type='button' value='edit' onclick='edit()'>";
    //content should not be visible to client until "EDIT" button is pressed and it's opened in EDIT page.
    echo "File content: " . $content . "<br>";
}

?>