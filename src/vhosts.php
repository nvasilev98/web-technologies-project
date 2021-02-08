<?php
function generateVhostFile($hostname, $port, $errorLogDirectory)
{
    $content = "LoadModule deflate_module /usr/local/apache2/modules/mod_deflate.so
LoadModule proxy_module /usr/local/apache2/modules/mod_proxy.so
LoadModule proxy_fcgi_module /usr/local/apache2/modules/mod_proxy_fcgi.so\n\n";

    $content .= "<VirtualHost *:" . $port . ">\n";
    $content .= "\tServerName " . $hostname . "\n";
    $content .= "    DocumentRoot /var/www/html/
    <Directory /var/www/html/>
        DirectoryIndex index.php
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    CustomLog /proc/self/fd/1 common
    ErrorLog " . $errorLogDirectory . "\n" .
"</VirtualHost>";

    return $content;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hostname = $_POST["hostname"];
    $port = $_POST["port"];
    $errorLogDirectory = $_POST["error-log"];
    // If no directory is not passed decide which will be the default value - stdout and stderr ??
    if (!isset($str) || trim($errorLogDirectory) === '') {
        $errorLogDirectory = "/proc/self/fd/2";
    }
    // If directory is passed -> parse to the last '/' and check if it's valid path.
    // If it's a valid path, check if it's existing directory and if not add MKDIR to the dockerfile
    $content = generateVhostFile($hostname, $port, $errorLogDirectory);
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=apache.conf");

    // Allow multiple vhosts to be added (up to 3 ??)
    // Should we add global Server Name ? - most probably yes
    echo $content;
}

?>