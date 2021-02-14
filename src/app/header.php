<?php
session_start();
if (!($_SERVER['REQUEST_URI'] === '/app/login.php' || $_SERVER['REQUEST_URI'] === '/app/register.php')
    && (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)) {
    header("location: login.php");
    exit;
}
?>
<title>ЕNA Project</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="../css/main.css">
<div class="w3-top">
    <div class="w3-bar w3-white w3-wide w3-padding w3-card">
        <a href="index.php" class="w3-bar-item w3-button"><b>ENA</b> Project</a>
        <div class="w3-right w3-hide-small">
            <a href="create.php" class="w3-bar-item w3-button">Create</a>
            <a href="update.php" class="w3-bar-item w3-button">Edit</a>
            <a href="history.php" class="w3-bar-item w3-button">History</a>
            <a href="logout.php" class="w3-bar-item w3-button">Sign Out</a>

        </div>
    </div>
</div>