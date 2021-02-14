<?php
include 'header.php';
include 'session.php';

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

<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
.wrapper {
            padding: 20px;
            margin: 0;
            position: absolute;
            top:10%;
            width:100%;
        }
</style>
</head>
<body>

<h2>HTML Table</h2>
<div class="wrapper">
<table>
  <tr>
    <th>Company</th>
    <th>Contact</th>
    <th>Country</th>
  </tr>
  <tr>
    <td>Alfreds Futterkiste</td>
    <td>Maria Anders</td>
    <td>Germany</td>
  </tr>
  <tr>
    <td>Centro comercial Moctezuma</td>
    <td>Francisco Chang</td>
    <td>Mexico</td>
  </tr>
  <tr>
    <td>Ernst Handel</td>
    <td>Roland Mendel</td>
    <td>Austria</td>
  </tr>
  <tr>
    <td>Island Trading</td>
    <td>Helen Bennett</td>
    <td>UK</td>
  </tr>
  <tr>
    <td>Laughing Bacchus Winecellars</td>
    <td>Yoshi Tannamuri</td>
    <td>Canada</td>
  </tr>
  <tr>
    <td>Magazzini Alimentari Riuniti</td>
    <td>Giovanni Rovelli</td>
    <td>Italy</td>
  </tr>
</table>
</div>
</body>
</html>
