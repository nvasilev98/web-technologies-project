<html>
<head>
    <title>PHP-Test</title>
</head>
<body>
<form method="post">

    <label for="version"> PHP Version
        <select name="version" id="version">
            <option value="8">8</option>
            <option value="7.4">7.4</option>
            <option value="7.3">7.3</option>
            <option value="7">7</option>
            <option value="5.6">5.6</option>
        </select>
    </label>
    <input type="submit" name="submit" value="Submit">
</form>
<?php
function create_file($filename, $content)
{
    $myfile = fopen($filename . ".Dockerfile", "w") or die("Unable to open file!");

    fwrite($myfile, $content);
    fclose($myfile);
}
function generateDockerfileContent($version) {
    $content = "FROM php:" . $version . "-apache\n";
    $content .= "COPY . /var/www/html/";

    return $content;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $version = $_POST["version"];
    $dockerfileContent = generateDockerfileContent($version);
    create_file("gosho", $dockerfileContent);
}

?>
</body>
</html>
