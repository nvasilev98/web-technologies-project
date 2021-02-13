<?php

$phpDockerfile =
    "FROM php:{{php-version}}-fpm-alpine3.7
     RUN docker-php-ext-install mysqli";

function generatePhpDockerfile($version)
{
    global $phpDockerfile;
    return str_replace("{{php-version}}", $version, $phpDockerfile);
}

$apacheConf =
    "LoadModule deflate_module /usr/local/apache2/modules/mod_deflate.so
LoadModule proxy_module /usr/local/apache2/modules/mod_proxy.so
LoadModule proxy_fcgi_module /usr/local/apache2/modules/mod_proxy_fcgi.so

LISTEN {{port}}

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

    CustomLog {{custom-log}} common
    ErrorLog {{error-log}}
</VirtualHost>";

function generateApacheConfFile($host, $port, $errorLog, $customLog) {
    global $apacheConf;
    if (isBlank($customLog)) {
        // stdout
        $customLog = "/proc/self/fd/1";
    } else {
        $directory = getDirectory($customLog);
        if (!isDir($directory)) {
            return "";
        }
    }
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

function generateApacheDockerfile($version, $errorLog, $customLog) {
    $errorLogDir = getDirectory($errorLog);
    $customLogDir = getDirectory($customLog);

    global $apacheDockerfile;
    $content = str_replace("{{apache-version}}", $version, $apacheDockerfile);
    $content = str_replace("{{error-log-dir}}", $errorLogDir, $content);
    $content = str_replace("{{custom-log-dir}}", $customLogDir, $content);

    return $content;
}

$nginxDockerFile =
"FROM nginx:{{version}}

RUN [\"/bin/bash\", \"-c\", \"[ ! -d '{{error-log}}' ] && mkdir -p /{{error-log}}\"]";

function generateNginxDockerfile($nginxVersion, $errorLog) {
    global $nginxDockerFile;

    $errorLog = getDirectory($errorLog);

    $content = str_replace('{{version}}', $nginxVersion, $nginxDockerFile);
    $content = str_replace('{{error-log}}', $errorLog, $content);

    return $content;
}

$nginxConf =
"server {
    index index.php index.html;
    server_name {{hostname}};
    error_log  {{error-log}};
    access_log {{access-log}};
    root /var/www/html/;

    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param PATH_INFO \$fastcgi_path_info;
    }
}";

function generateNginxConf($hostname, $errorLog, $accessLog) {
    global $nginxConf;

    $content = str_replace('{{hostname}}', $hostname, $nginxConf);
    $content = str_replace("{{error-log}}", $errorLog, $content);
    $content = str_replace("{{access-log}}", $accessLog, $content);

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

    $hostname = $_POST["host"];
    $errorLog = $_POST["error-log-dir"];
    $customLog = $_POST["custom-log-dir"];

    $nginxConf = generateNginxConf($hostname, $errorLog, $customLog);

    header("Content-Type: application/text");
    header("Content-Disposition: attachment; filename=nginx.conf");

    echo $nginxConf;
}
?>