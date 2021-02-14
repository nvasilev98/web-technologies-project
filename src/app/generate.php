<?php

include_once 'database/DbExecutor.php';
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
const phpDockerfile =
"FROM php:{{php-version}}-fpm
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo pdo_mysql";

function generatePhpDockerfile($version)
{
    return str_replace("{{php-version}}", $version, phpDockerfile);
}

const apacheConf =
"LoadModule deflate_module /usr/local/apache2/modules/mod_deflate.so
LoadModule proxy_module /usr/local/apache2/modules/mod_proxy.so
LoadModule proxy_fcgi_module /usr/local/apache2/modules/mod_proxy_fcgi.so

ServerName {{server-name}}

<VirtualHost *:80>
    ServerName {{server-name}}
    ProxyPassMatch ^/(.*\.php(/.*)?)$ fcgi://php1:9000/var/www/html/$1
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

function generateApacheConfFile($host, $errorLog, $customLog)
{
    if (isBlank($customLog)) {
        // stdout
        $customLog = "/proc/self/fd/1";
    }
    if (isBlank($errorLog)) {
        // stderr
        $errorLog = "/proc/self/fd/2";
    }

    $content = str_replace("{{server-name}}", $host, apacheConf);
    $content = str_replace("{{custom-log}}", $customLog, $content);
    $content = str_replace("{{error-log}}", $errorLog, $content);

    return $content;
}

const apacheDockerfile =
"FROM httpd:{{apache-version}}-alpine
RUN apk update; \
    apk upgrade;

RUN [\"/bin/bash\",\"-c\", \"[ ! -d '{{error-log-dir}}' ] && mkdir -p {{error-log-dir}}\"]
RUN [\"/bin/bash\",\"-c\", \"[ ! -d '{{custom-log-dir}}' ] && mkdir -p {{custom-log-dir}}\"]

COPY project.apache.conf /usr/local/apache2/conf/project.apache.conf
RUN echo \"Include /usr/local/apache2/conf/project.apache.conf\" \
    >> /usr/local/apache2/conf/httpd.conf";

function generateApacheDockerfile($version, $errorLog, $customLog)
{
    $errorLogDir = getDirectory($errorLog);
    $customLogDir = getDirectory($customLog);

    $content = str_replace("{{apache-version}}", $version, apacheDockerfile);
    $content = str_replace("{{error-log-dir}}", $errorLogDir, $content);
    $content = str_replace("{{custom-log-dir}}", $customLogDir, $content);

    return $content;
}

const nginxDockerFile =
"FROM nginx:{{version}}

RUN [\"/bin/bash\", \"-c\", \"[ ! -d '{{error-log}}' ] && mkdir -p /{{error-log}}\"]
RUN [\"/bin/bash\", \"-c\", \"[ ! -d '{{custom-log}}' ] && mkdir -p /{{custom-log}}\"]";

function generateNginxDockerfile($nginxVersion, $errorLog, $customLog)
{
    $errorLog = getDirectory($errorLog);

    $content = str_replace('{{version}}', $nginxVersion, nginxDockerFile);
    $content = str_replace('{{error-log}}', $errorLog, $content);
    $content = str_replace('{{custom-log}}', $customLog, $content);

    return $content;
}

const nginxConf =
"
{{load-balancer}}
server {
    index index.php index.html;
    server_name {{hostname}};
    error_log  {{error-log}};
    access_log {{access-log}};
    root /var/www/html/;
    
    {{lb-pass-pass}}

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

const nginxLb =
"upstream php {
    {{servers}}
}";

const proxyPassLocation =
"   location / {
        proxy_pass http://php;
    }";

function generateNginxConf($hostname, $errorLog, $accessLog, $useLb, $serverCount)
{
    if (isBlank($errorLog)) {
        $errorLog = "/var/log/nginx/error.log";
    }
    if (isBlank($accessLog)) {
        $accessLog = "/var/log/nginx/access.log";
    }

    $content = str_replace('{{hostname}}', $hostname, nginxConf);
    $content = str_replace("{{error-log}}", $errorLog, $content);
    $content = str_replace("{{access-log}}", $accessLog, $content);

    if ($useLb === TRUE) {
        $servers = "";
        for ($i = 1; $i <= $serverCount; $i++) {
            $servers .= "server webtechnologiesproject_php" . $i . "_1:9000;";
        }

        $upstream = str_replace('{{servers}}', $servers, nginxLb);
        $content = str_replace('{{load-balancer}}', $upstream, $content);
        $content = str_replace('{{lb-pass-pass}}', proxyPassLocation, $content);
    } else {
        $content = str_replace('{{load-balancer}}', '', $content);
        $content = str_replace('{{lb-pass-pass}}', '', $content);
    }

    return $content;
}

const dockerCompose =
"version: \"3.2\"
services:
  {{php-services}}
  web:
    build: './{{server}}/'
    ports:
      - \"{{port}}:80\"
    volumes:
      - ./src/:/var/www/html/
      {{nginx-volume}}
  db:
    image: mysql:{{mysql-version}}
    ports:
      - \"3306:3306\"
    env_file:
      - .env
    environment:
      - MYSQL_USER=\${MYSQL_USER}
      - MYSQL_PASSWORD=\${MYSQL_PASSWORD}
      - MYSQL_ROOT_PASSWORD=\${MYSQL_ROOT_PASSWORD}
    volumes:
      - ./scripts:/docker-entrypoint-initdb.d
      - persistent:/var/lib/mysql
volumes:
  persistent:";

const nginxVolume =
"- ./nginx/nginx.conf:/etc/nginx/conf.d/default.conf";

function generateDockerCompose($server, $port, $mysqlVersion, $numberOfInstances)
{
    $content = str_replace('{{port}}', $port, dockerCompose);
    $content = str_replace('{{server}}', $server, $content);
    $content = str_replace('{{mysql-version}}', $mysqlVersion, $content);

    if ($server === 'apache') {
        $content = str_replace('{{nginx-volume}}', '', $content);
    } else {
        $content = str_replace('{{nginx-volume}}', nginxVolume, $content);
    }

    $phpServices = generatePhpServices($numberOfInstances);
    $content = str_replace('{{php-services}}', $phpServices, $content);

    return $content;
}

const phpServices =
"php{{number}}:
    build: './php/'
    volumes:
      - ./src/:/var/www/html/
    depends_on:
      - db";

function generatePhpServices($numberOfInstances)
{
    $content = '';
    for ($i = 1; $i <= $numberOfInstances; $i++) {
        $service = str_replace('{{number}}', $i, phpServices);
        $content .= $service;
    }
    return $content;
}

const envFile =
"MYSQL_USER={{user}}
MYSQL_PASSWORD={{password}}
MYSQL_ROOT_PASSWORD={{root-password}}";

function generateEnvFile($user, $password, $rootPass)
{
    $content = str_replace('{{user}}', $user, envFile);
    $content = str_replace('{{password}}', $password, $content);
    $content = str_replace('{{root-password}}', $rootPass, $content);

    return $content;
}

function isBlank($str)
{
    return !isset($str) || trim($str) === '';
}

function isDir($dir)
{
    return $dir === "/" || preg_match('/^\/[\w\-\/]+$/', $dir) == 1;
}

function getDirectory($str)
{
    $split = explode("/", $str);
    array_pop($split);
    return implode("/", $split);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $phpVersion = $_POST['php-version'];
    $phpDockerfile = generatePhpDockerfile($phpVersion);

    $server = $_POST['server'];
    if ($server === 'apache') {
        $hostname = $_POST['apache-host'];
        $port = $_POST['apache-port'];
        $errorLog = $_POST['apache-error-log-dir'];
        $customLog = $_POST['apache-custom-log-dir'];
        $apacheVersion = $_POST['apache-version'];
        $confFile = generateApacheConfFile($hostname, $errorLog, $customLog);
        $serverDockerFile = generateApacheDockerfile($apacheVersion, $customLog, $errorLog);
        $serverCount = 1;
    } else {
        $hostname = $_POST['nginx-host'];
        $port = $_POST['nginx-port'];
        $errorLog = $_POST['nginx-error-log-dir'];
        $customLog = $_POST['nginx-custom-log-dir'];
        $nginxVersion = $_POST['nginx-version'];
        $useLoadBalancer = $_POST['use-load-balancer'];
        if ($useLoadBalancer === FALSE) {
            $serverCount = 1;
        } else {
            $serverCount = $_POST['server-count'];
        }
        $confFile = generateNginxConf($hostname, $errorLog, $customLog, $useLoadBalancer, $serverCount);
        $serverDockerFile = generateNginxDockerfile($nginxVersion, $errorLog, $customLog);
    }

    $envFile = generateEnvFile($_POST['mysql-user'], $_POST['mysql-password'], $_POST['mysql-root']);

    $mysqlVersion = $_POST['mysql-version'];
    $dockerCompose = generateDockerCompose($server, $port, $mysqlVersion, $serverCount);

    $filename = $_POST["name"];
    $username = $_SESSION["username"];
    $json = json_encode($_POST);

    if (createFile($filename, $username, $json) === TRUE) {
        zipFilesAndDownload($filename, $phpDockerfile, $server, $serverDockerFile, $confFile, $dockerCompose, $envFile);
    }
}

function zipFilesAndDownload($filename, $phpDockerFile, $server, $serverDockerfile, $serverConf, $dockerCompose, $envFile)
{
    $zip = new ZipArchive();

    $filename .= '.zip';
    if (!$zip->open($filename, ZipArchive::CREATE|ZipArchive::OVERWRITE)) {
        exit("cannot open file!!");
    }

    $zip->addFromString('php/Dockerfile', $phpDockerFile);
    if ($server === 'apache') {
        $zip->addFromString('apache/Dockerfile', $serverDockerfile);
        $zip->addFromString('apache/project.apache.conf', $serverConf);
    } else {
        $zip->addFromString('nginx/Dockerfile', $serverDockerfile);
        $zip->addFromString('nginx/nginx.conf', $serverConf);
    }
    $zip->addFromString('docker.compose.yml', $dockerCompose);
    $zip->addFromString('.env', $envFile);
    $zip->close();

    header("Content-type: application/zip");
    header("Content-Disposition: attachment; filename=" . $filename);
    header("Content-length: " . filesize($filename));
    header("Pragma: no-cache");
    header("Expires: 0");
    readfile($filename);

    unlink($filename);
}

?>