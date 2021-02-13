<?php

$phpDockerfile =
    "FROM php:{{php-version}}-fpm-alpine3.7
     RUN docker-php-ext-install mysqli";

function generatePhpDockerfile()
{
    $version = $_POST["php-version"];
    global $phpDockerfile;
    return str_replace("{{php-version}}", $version, $phpDockerfile);
}

$apacheConf =
    "LoadModule deflate_module /usr/local/apache2/modules/mod_deflate.so
LoadModule proxy_module /usr/local/apache2/modules/mod_proxy.so
LoadModule proxy_fcgi_module /usr/local/apache2/modules/mod_proxy_fcgi.so

ServerName {{server-name}}

<VirtualHost *:{{port}}>
    ServerName {{server-name}}
    ProxyPassMatch ^/(.*\.php(/.*)?)$ fcgi://php:9000/var/www/html/$1
    DocumentRoot /var/www/html/
    <Directory /var/www/html/>
        DirectoryIndex index.php
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Send apache logs to stdout and stderr
    CustomLog {{custom-log}} common
    ErrorLog {{error-log}}
</VirtualHost>";

function generateApacheConfFile() {
    global $apacheConf;
    $host = $_POST["host"];
    $port = $_POST["port"];
    $customLog = $_POST["custom-log-dir"];
    // TODO: check if it's valid dir
    if (isBlank($customLog)) {
        // stdout
        $customLog = "/proc/self/fd/1";
    } else {
        $directory = getDirectory($customLog);
        if (!isDir($directory)) {
            return "";
        }
    }
    $errorLog = $_POST["error-log-dir"];
    if (isBlank($errorLog)) {
        // stderr
        $errorLog = "/proc/self/fd/2";
    } else {
        $directory = getDirectory($errorLog);
        if (!isDir($directory)) {
            return "";
        }
    }
    $content = str_replace("{{server-name}}", $host, $apacheConf);
    $content = str_replace("{{port}}", $port, $content);
    $content = str_replace("{{custom-log}}", $customLog, $content);
    $content = str_replace("{{error-log}}", $errorLog, $content);

    return $content;
}

$apacheDockerfile =
    "FROM httpd:{{apache-version}}-alpine
RUN apk update; \
    apk upgrade;

RUN [\"/bin/bash\",\"-c\", \"[ ! -d '{{error-log-dir}}' ] && mkdir -p {{error-log-dir}}\"]
RUN [\"/bin/bash\",\"-c\", \"[ ! -d '{{custom-log-dir}}' ] && mkdir -p {{custom-log-dir}}\"]

# Copy apache vhost file to proxy php requests to php-fpm container
COPY demo.apache.conf /usr/local/apache2/conf/demo.apache.conf
RUN echo \"Include /usr/local/apache2/conf/demo.apache.conf\" \
    >> /usr/local/apache2/conf/httpd.conf";

function generateApacheDockerfile() {
    $apacheVersion = $_POST["apache-version"];
    $errorLogDir = $_POST["error-log-dir"];
    $customLogDir = $_POST["custom-log-dir"];
    $errorLogDir = getDirectory($errorLogDir);
    $customLogDir = getDirectory($customLogDir);

    global $apacheDockerfile;
    $content = str_replace("{{apache-version}}", $apacheVersion, $apacheDockerfile);
    $content = str_replace("{{error-log-dir}}", $errorLogDir, $content);
    $content = str_replace("{{custom-log-dir}}", $customLogDir, $content);

    return $content;
}

function isBlank($str) {
    return !isset($str) || trim($str) === '';
}

function isDir($dir) {
    return $dir === "/" || preg_match('/^\/[\w\-\/]+$/', $dir) == 1;
}

function getDirectory($str) {
    $split = explode("/", $str);
    array_pop($split);
    return implode("/", $split);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phpDockerfile = generatePhpDockerfile() . "\n";
    $apacheConf = generateApacheConfFile() . "\n";
    $apacheDockerfile = generateApacheDockerfile() . "\n";

//    $zip = new ZipArchive();
//    $zip -> open("gosho.zip", ZipArchive::CREATE);
//
//    $zip -> addFromString("/apache/demo.apache.conf", $apacheConf);
//    $zip -> addFromString("/apache/Dockerfile", $apacheDockerfile);
//    $zip -> addFromString("/php/Dockerfile", $phpDockerfile);
//
//    $zip -> close();

//    header("Content-Type: application/zip");
//    header("Content-Disposition: attachment; filename=test");
//    header("Content-Length: " . filesize($zipName));

    //readfile($zipName);
}
?>