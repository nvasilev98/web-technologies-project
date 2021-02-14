<?php
    include 'session.php';
    include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .body {
            font: 14px sans-serif;
            text-align: center;
        }
        .wrapper {
            margin: 0;
            position: absolute;
            top:10%;
            left:50%;
            transform: translateY(-50%);
            transform: translateX(-50%);
        }
        .logo {
            width: 70%; 
            position: absolute;
            top: 150%;
            left: 50%;
            transform: translateY(-50%);
            transform: translateX(-50%);
        }
    </style>
</head>
<body>
<div class="page-header wrapper">
    <h1>Hello, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to Docker Manager!</h1>
    <div>
        <img src="../images/docker-logo.png" alt="docker" class = "logo">
    </div>
</div>
</body>
</html>