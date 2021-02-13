<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');

try {
  $pdo = new PDO("mysql:host=" . DB_SERVER, DB_USERNAME, DB_PASSWORD);
  // set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "CREATE DATABASE IF NOT EXISTS webproject";
  // use exec() because no results are returned
  $pdo->exec($sql);
  echo "Database is active!<br>";
  $usedb = "use webproject;";
  $pdo->query($usedb);
  echo "Using database webproject<br>";

  $usersdb = "CREATE TABLE IF NOT EXISTS users (
          id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
          username VARCHAR(50) NOT NULL UNIQUE,
          password VARCHAR(255) NOT NULL
          );";

  $pdo->exec($usersdb);
  echo "Table USERS is active!<br>";

  $filesdb = "CREATE TABLE IF NOT EXISTS files (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            file VARCHAR(50) NOT NULL,
            created_by VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            content JSON
            );";

  $pdo->exec($filesdb);

  echo "Table FILES is active!<br>";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
?>