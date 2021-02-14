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
$data = array();
while ($r = $stmt->fetch()) {
    $filename = $r['file'];
    $timestamp = $r['created_at'];
    $content = $r['content'];

    $post_data = new stdClass();
    $post_data->filename = $filename;
    $post_data->timestamp = $timestamp;
    $post_data->content = $content;
    array_push($data, $post_data);
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
      <thead>
        <tr>
          <th>File name</th>
          <th>Created At</th>
          <th width="10%" style="text-align:center;">Action</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
</div>
</body>
</html>
<script>
  let data = <?php echo json_encode($data); ?>;

  const formEl = document.querySelector("form");
  const tbodyEl = document.querySelector("tbody");
  const tableEl = document.querySelector("table");

  for (i = 0; i < data.length; i++) {
    tbodyEl.innerHTML += `
      <tr>
      <td>${data[i].filename}</td>
      <td>${data[i].timestamp}</td>
      <td style="text-align:center;"><button class="w3-button w3-black w3-section" id="${i}">Edit</button></td>
      </tr>
    `;
  }

  function onEditElement(e) {
    edit(JSON.parse(data[e.target.id].content));
  }
  tableEl.addEventListener("click", onEditElement);
</script>
<script src="../js/main.js"></script>

