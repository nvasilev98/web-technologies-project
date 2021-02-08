<html>

<head>
    <title>PHP-Test</title>
</head>

<body>
    <form method="post">
        Server Name: <label for="servername">
            <input type="text" servername="servername">
            <br><br>
        PHP Version: <label for="version">
                <select name="version">
                    <option value="5.6">5.6</option>
                    <option value="7">7</option>
                    <option value="7.3">7.3</option>
                    <option value="7.4">7.4</option>
                    <option value="8">8</option>
                </select>
            </label>
            <br><br>
            <input type="submit" name="submit" value="Submit">
    </form>
    <?php
    function create_file($filename, $content)
    {
        $myfile = fopen($filename . ".Dockerfile", "w") or die("Unable to open file!");

        fwrite($myfile, $content);
        fclose($myfile);
    }
    function generateDockerfileContent($version)
    {
        $content = "FROM php:" . $version . "-apache\n";
        $content .= "COPY . /var/www/html/";

        return $content;
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $version = $_POST["version"];
        $servername = $_POST["servername"];
        $dockerfileContent = generateDockerfileContent($version);
        create_file($servername, $dockerfileContent);
    }

    ?>
</body>

</html>