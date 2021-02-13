<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";
$username = $_SESSION["username"];
$stmt = $pdo->prepare("SELECT * FROM files where created_by = :username");
$stmt->bindParam(":username", $username, PDO::PARAM_STR);
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);

while ($r = $stmt->fetch()) {
    $filename = $r['file'];
    $user = $r['created_by'];
    $timestamp = $r['created_at'];
    echo "File: " . $filename . " Created by: " . $user . " Created at: " . $timestamp . "<br>";
}

unset($pdo)
?>
<html>
    <title>AEN Project</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        * {
        box-sizing: border-box;
        }
        body {
        background-color: #f1f1f1;
        }
        #createForm {
        background-color: #ffffff;
        margin: 100px auto;
        font-family: Raleway;
        padding: 40px;
        width: 70%;
        min-width: 300px;
        }
        h1 {
        text-align: center;  
        }
        input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
        }
        input.invalid {
        background-color: #ffdddd;
        }
        .tab {
        display: none;
        }
        #prevBtn {
        background-color: #bbbbbb;
        }
        .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;  
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
        }
        .step.finish {
        background-color: #000000;
        }
    </style>
    <body>
        <div class="w3-top">
            <div class="w3-bar w3-white w3-wide w3-padding w3-card">
                <a href="#home" class="w3-bar-item w3-button"><b>AEN</b> Project</a>
                <div class="w3-right w3-hide-small">
                    <a href="create.php" class="w3-bar-item w3-button">Create</a>
                    <a href="update.php" class="w3-bar-item w3-button">Edit</a>
                    <a href="history.php" class="w3-bar-item w3-button">History</a>
                    <a href="logout.php" class="w3-bar-item w3-button">Sign Out</a>
                </div>
            </div>
            <div class ="main-table">

            </div>
        </div>
    </body>
</html>

